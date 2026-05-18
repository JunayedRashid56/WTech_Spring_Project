<?php
session_start();

require_once __DIR__ . '/../../config/database.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: /WTech Project/views/auth/login.php");
    exit();
}

$stmt = $pdo->query("
    SELECT categories.*, COUNT(menu_items.id) AS item_count
    FROM categories
    LEFT JOIN menu_items ON menu_items.category_id = categories.id
    GROUP BY categories.id
    ORDER BY categories.id DESC
");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

$adminPageTitle = 'Categories';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories — BiteRush Admin</title>
</head>
<body>

<?php require_once __DIR__ . '/partials/sidebar.php'; ?>

<?php if (isset($_SESSION['success'])): ?>
    <div class="adm-flash adm-flash-success">✅ <?= htmlspecialchars($_SESSION['success']) ?></div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="adm-flash adm-flash-error">❌ <?= htmlspecialchars($_SESSION['error']) ?></div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<div class="adm-card">
    <div class="adm-card-header">
        <div class="adm-card-title">🗂️ Categories <span style="color:#aaa;font-weight:500;font-size:.85rem;margin-left:6px;">(<?= count($categories) ?>)</span></div>
        <a href="add_category.php" class="adm-btn adm-btn-primary">+ Add Category</a>
    </div>

    <?php if (empty($categories)): ?>
        <div style="text-align:center;padding:60px 20px;color:#aaa;">
            <div style="font-size:2.5rem;margin-bottom:12px;">🗂️</div>
            <div style="font-weight:700;">No categories yet</div>
        </div>
    <?php else: ?>
    <table class="adm-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Category Name</th>
                <th>Items</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($categories as $category): ?>
        <tr>
            <td style="color:#aaa;font-size:.82rem;"><?= $category['id'] ?></td>
            <td>
                <div style="display:flex;align-items:center;gap:10px;">
                    <span style="width:36px;height:36px;border-radius:8px;background:#f3e5f5;display:flex;align-items:center;justify-content:center;font-size:1.1rem;">🗂️</span>
                    <strong><?= htmlspecialchars($category['name']) ?></strong>
                </div>
            </td>
            <td>
                <span style="background:#e8f0fe;color:#1565c0;padding:3px 10px;border-radius:20px;font-size:.75rem;font-weight:700;">
                    <?= $category['item_count'] ?> item<?= $category['item_count'] != 1 ? 's' : '' ?>
                </span>
            </td>
            <td>
                <div style="display:flex;gap:8px;">
                    <a href="edit_category.php?id=<?= $category['id'] ?>" class="adm-btn adm-btn-outline adm-btn-sm">Edit</a>
                    <a href="delete_category.php?id=<?= $category['id'] ?>"
                       onclick="return confirm('Delete category \'<?= htmlspecialchars(addslashes($category['name'])) ?>\'? This may affect menu items.')"
                       class="adm-btn adm-btn-danger adm-btn-sm">Delete</a>
                </div>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/partials/end.php'; ?>
</body>
</html>
<?php
session_start();

require_once __DIR__ . '/../../config/database.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: /WTech Project/views/auth/login.php");
    exit();
}

$stmt = $pdo->query("
    SELECT menu_items.*, categories.name AS category_name
    FROM menu_items
    JOIN categories ON menu_items.category_id = categories.id
    ORDER BY menu_items.id DESC
");
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$adminPageTitle = 'Menu Items';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Items — BiteRush Admin</title>
    <style>
        .toggleBtn {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 5px 13px; border-radius: 20px; border: none;
            font-size: .75rem; font-weight: 800; cursor: pointer;
            letter-spacing: .4px; transition: opacity .15s;
        }
        .toggleBtn:hover { opacity: .8; }
        .badge-active   { background: #e8f5e9; color: #1b5e20; }
        .badge-inactive { background: #f5f5f5; color: #888; }
        .item-thumb {
            width: 52px; height: 52px; border-radius: 8px;
            object-fit: cover; background: #e8f0fe;
        }
        .item-thumb-placeholder {
            width: 52px; height: 52px; border-radius: 8px;
            background: #f0f2f5; display: flex; align-items: center;
            justify-content: center; font-size: 1.3rem; color: #bbb;
        }
    </style>
</head>
<body>

<?php require_once __DIR__ . '/partials/sidebar.php'; ?>

<div class="adm-card">
    <div class="adm-card-header">
        <div class="adm-card-title">🍽️ Menu Items <span style="color:#aaa;font-weight:500;font-size:.85rem;margin-left:6px;">(<?= count($items) ?>)</span></div>
        <a href="add_menu_item.php" class="adm-btn adm-btn-primary">+ Add Item</a>
    </div>

    <?php if (empty($items)): ?>
        <div style="text-align:center;padding:60px 20px;color:#aaa;">
            <div style="font-size:2.5rem;margin-bottom:12px;">🍽️</div>
            <div style="font-weight:700;">No menu items yet</div>
        </div>
    <?php else: ?>
    <table class="adm-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Image</th>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($items as $item): ?>
        <tr id="item-row-<?= $item['id'] ?>">
            <td style="color:#aaa;font-size:.82rem;"><?= $item['id'] ?></td>
            <td>
                <?php if (!empty($item['image_path'])): ?>
                    <img class="item-thumb"
                         src="/WTech Project/public/uploads/menu/<?= htmlspecialchars($item['image_path']) ?>"
                         alt="<?= htmlspecialchars($item['name']) ?>"
                         onerror="this.style.display='none'">
                <?php else: ?>
                    <div class="item-thumb-placeholder">🍽️</div>
                <?php endif; ?>
            </td>
            <td><strong><?= htmlspecialchars($item['name']) ?></strong>
                <?php if (!empty($item['description'])): ?>
                    <div style="font-size:.78rem;color:#999;margin-top:2px;max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                        <?= htmlspecialchars($item['description']) ?>
                    </div>
                <?php endif; ?>
            </td>
            <td>
                <span style="background:#e8f0fe;color:#1565c0;padding:3px 10px;border-radius:20px;font-size:.75rem;font-weight:700;">
                    <?= htmlspecialchars($item['category_name']) ?>
                </span>
            </td>
            <td><strong>৳<?= number_format($item['price'], 0) ?></strong></td>
            <td>
                <button class="toggleBtn <?= $item['is_available'] ? 'badge-active' : 'badge-inactive' ?>"
                        data-id="<?= $item['id'] ?>">
                    <?= $item['is_available'] ? '✅ Active' : '⬜ Inactive' ?>
                </button>
            </td>
            <td>
                <div style="display:flex;gap:8px;">
                    <a href="edit_menu_item.php?id=<?= $item['id'] ?>" class="adm-btn adm-btn-outline adm-btn-sm">Edit</a>
                    <a href="delete_menu_item.php?id=<?= $item['id'] ?>"
                       onclick="return confirm('Delete \'<?= htmlspecialchars(addslashes($item['name'])) ?>\'?')"
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
<script src="/WTech Project/public/js/toggle.js"></script>
</body>
</html>
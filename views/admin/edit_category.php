<?php

session_start();

require_once __DIR__ . '/../../config/database.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: /WTech Project/views/auth/login.php");
    exit();
}

$id = (int) $_GET['id'];

$stmt = $pdo->prepare("
    SELECT * FROM categories
    WHERE id = ?
");

$stmt->execute([$id]);

$category = $stmt->fetch(PDO::FETCH_ASSOC);

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name = trim($_POST['name']);

    if (empty($name)) {

        $message = "Category name required";

    } else {

        $update = $pdo->prepare("
            UPDATE categories
            SET name = ?
            WHERE id = ?
        ");

        $update->execute([$name, $id]);

        header("Location: categories.php");
        exit();

    }

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category — BiteRush Admin</title>
</head>
<body>

<?php
$adminPageTitle = 'Edit Category';
require_once __DIR__ . '/partials/sidebar.php';
?>

<a href="categories.php" style="display:inline-flex;align-items:center;gap:6px;font-size:.88rem;font-weight:600;color:#1565c0;text-decoration:none;margin-bottom:22px;">← Back to Categories</a>

<div style="font-size:1.4rem;font-weight:900;color:#0a0a0a;margin-bottom:22px;">✏️ Edit Category</div>

<?php if ($message): ?>
    <div class="adm-flash adm-flash-error" style="margin-bottom:18px;">❌ <?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<div style="max-width:480px;">
    <div class="adm-card">
        <div class="adm-card-header"><div class="adm-card-title">Category Details</div></div>
        <div style="padding:24px;">
            <form method="POST">
                <div class="adm-field">
                    <label>Category Name</label>
                    <input type="text" name="name"
                           value="<?= htmlspecialchars($category['name']) ?>"
                           placeholder="e.g. Burgers" required autofocus>
                </div>
                <div style="display:flex;gap:12px;margin-top:8px;">
                    <button type="submit" class="adm-btn adm-btn-primary" style="flex:1;justify-content:center;padding:12px;">💾 Save Changes</button>
                    <a href="categories.php" class="adm-btn adm-btn-outline" style="flex:1;justify-content:center;padding:12px;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/partials/end.php'; ?>
</body>
</html>
<?php

session_start();

require_once __DIR__ . '/../../config/database.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {

    header("Location: /WTech Project/views/auth/login.php");
    exit();

}

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name = trim($_POST['name']);

    if (empty($name)) {

        $message = "Category name is required";

    } else {

        $stmt = $pdo->prepare("
            INSERT INTO categories(name)
            VALUES(?)
        ");

        $stmt->execute([$name]);

        $message = "Category Added Successfully";

    }

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category — BiteRush Admin</title>
</head>
<body>

<?php
$adminPageTitle = 'Add Category';
require_once __DIR__ . '/partials/sidebar.php';
?>

<a href="categories.php" style="display:inline-flex;align-items:center;gap:6px;font-size:.88rem;font-weight:600;color:#1565c0;text-decoration:none;margin-bottom:22px;">← Back to Categories</a>

<div style="font-size:1.4rem;font-weight:900;color:#0a0a0a;margin-bottom:22px;">➕ Add Category</div>

<?php if ($message && str_contains(strtolower($message), 'required')): ?>
    <div class="adm-flash adm-flash-error" style="margin-bottom:18px;">❌ <?= htmlspecialchars($message) ?></div>
<?php elseif ($message): ?>
    <div class="adm-flash adm-flash-success" style="margin-bottom:18px;">✅ <?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<div style="max-width:480px;">
    <div class="adm-card">
        <div class="adm-card-header"><div class="adm-card-title">New Category</div></div>
        <div style="padding:24px;">
            <form method="POST">
                <div class="adm-field">
                    <label>Category Name</label>
                    <input type="text" name="name"
                           value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
                           placeholder="e.g. Burgers" required autofocus>
                </div>
                <div style="display:flex;gap:12px;margin-top:8px;">
                    <button type="submit" class="adm-btn adm-btn-primary" style="flex:1;justify-content:center;padding:12px;">➕ Add Category</button>
                    <a href="categories.php" class="adm-btn adm-btn-outline" style="flex:1;justify-content:center;padding:12px;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/partials/end.php'; ?>
</body>
</html>
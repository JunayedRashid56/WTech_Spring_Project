<?php

session_start();

require_once __DIR__ . '/../../config/database.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: /WTech Project/views/auth/login.php");
    exit();
}

$categories = $pdo->query("
    SELECT * FROM categories
")->fetchAll(PDO::FETCH_ASSOC);

$errors = [];
$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $category_id = (int) $_POST['category_id'];
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = $_POST['price'];
    $is_available = isset($_POST['is_available']) ? 1 : 0;

    if (empty($name)) $errors[] = "Name is required";
    if (empty($description)) $errors[] = "Description is required";
    if (!is_numeric($price) || $price <= 0) $errors[] = "Price must be a positive number";
    if (empty($category_id)) $errors[] = "Category is required";

    $image_path = '';

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {

        $allowedMimes = ['image/jpeg', 'image/png'];
        $maxSize = 2 * 1024 * 1024;

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $_FILES['image']['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mime, $allowedMimes)) {
            $errors[] = "Image must be JPEG or PNG";
        } elseif ($_FILES['image']['size'] > $maxSize) {
            $errors[] = "Image must be 2 MB or smaller";
        } else {
            $ext = ($mime === 'image/jpeg') ? 'jpg' : 'png';
            $image_path = uniqid('item_', true) . '.' . $ext;
            $uploadDir = __DIR__ . '/../../public/uploads/menu/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $image_path);
        }
    } else {
        $errors[] = "Image is required";
    }

    if (empty($errors)) {

        $stmt = $pdo->prepare("
            INSERT INTO menu_items
            (category_id, name, description, price, image_path, is_available)
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $category_id,
            $name,
            $description,
            $price,
            $image_path,
            $is_available
        ]);

        $message = "Menu Item Added";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Menu Item — BiteRush Admin</title>
    <style>
        .mf-layout { display:grid; grid-template-columns:1fr 280px; gap:22px; align-items:start; }
        @media(max-width:860px){ .mf-layout{ grid-template-columns:1fr; } }

        .mf-img-card { background:white; border-radius:14px; box-shadow:0 2px 12px rgba(0,0,0,.06); overflow:hidden; }
        .mf-img-card-header { padding:14px 18px; border-bottom:2px solid #f0f2f5; font-size:.78rem; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:#aaa; }
        .mf-img-preview { width:100%; aspect-ratio:4/3; object-fit:cover; display:block; }
        .mf-img-placeholder { width:100%; aspect-ratio:4/3; background:#f4f6fb; display:flex; align-items:center; justify-content:center; font-size:3rem; color:#dde3f0; flex-direction:column; gap:10px; }
        .mf-img-placeholder span { font-size:.8rem; color:#bbb; font-weight:600; }
        .mf-img-card-body { padding:16px 18px; }

        .mf-toggle-wrap { display:flex; align-items:center; gap:12px; padding:14px 0; }
        .mf-toggle { position:relative; width:46px; height:26px; flex-shrink:0; }
        .mf-toggle input { opacity:0; width:0; height:0; }
        .mf-toggle-slider { position:absolute; inset:0; border-radius:26px; background:#dde3f0; cursor:pointer; transition:background .2s; }
        .mf-toggle-slider::before { content:''; position:absolute; width:20px; height:20px; border-radius:50%; background:white; left:3px; top:3px; transition:transform .2s; box-shadow:0 1px 4px rgba(0,0,0,.2); }
        .mf-toggle input:checked + .mf-toggle-slider { background:#1565c0; }
        .mf-toggle input:checked + .mf-toggle-slider::before { transform:translateX(20px); }
        .mf-toggle-label { font-size:.92rem; font-weight:700; color:#333; }
    </style>
</head>
<body>

<?php
$adminPageTitle = 'Add Menu Item';
require_once __DIR__ . '/partials/sidebar.php';
?>

<a href="menu_items.php" style="display:inline-flex;align-items:center;gap:6px;font-size:.88rem;font-weight:600;color:#1565c0;text-decoration:none;margin-bottom:22px;">← Back to Menu Items</a>

<div style="font-size:1.4rem;font-weight:900;color:#0a0a0a;margin-bottom:22px;">➕ Add Menu Item</div>

<?php if (!empty($errors)): ?>
    <div class="adm-flash adm-flash-error" style="margin-bottom:18px;">
        ❌ <?= implode(' &nbsp;·&nbsp; ', array_map('htmlspecialchars', $errors)) ?>
    </div>
<?php endif; ?>

<?php if ($message): ?>
    <div class="adm-flash adm-flash-success" style="margin-bottom:18px;">
        ✅ <?= htmlspecialchars($message) ?>
    </div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
<div class="mf-layout">

    <!-- LEFT: fields -->
    <div class="adm-card">
        <div class="adm-card-header"><div class="adm-card-title">Item Details</div></div>
        <div style="padding:20px 24px;">

            <div class="adm-field">
                <label>Category</label>
                <select name="category_id" required>
                    <option value="">Select a category…</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= (($_POST['category_id'] ?? '') == $cat['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="adm-field">
                <label>Item Name</label>
                <input type="text" name="name" placeholder="e.g. Crispy Chicken Burger"
                       value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
            </div>

            <div class="adm-field">
                <label>Description</label>
                <textarea name="description" rows="3" placeholder="Short description of the item…"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
            </div>

            <div class="adm-field">
                <label>Price (৳)</label>
                <input type="number" step="0.01" min="0.01" name="price"
                       placeholder="0.00" value="<?= htmlspecialchars($_POST['price'] ?? '') ?>" required>
            </div>

            <div class="adm-field">
                <label>Image <span style="font-weight:400;color:#aaa;">(JPEG/PNG · max 2 MB)</span></label>
                <input type="file" name="image" accept="image/jpeg,image/png" id="fileInput">
            </div>

            <div class="mf-toggle-wrap" style="border-top:2px solid #f0f2f5;margin-top:6px;">
                <label class="mf-toggle">
                    <input type="checkbox" name="is_available" value="1" checked>
                    <span class="mf-toggle-slider"></span>
                </label>
                <span class="mf-toggle-label">Available on menu</span>
            </div>

            <button type="submit" class="adm-btn adm-btn-primary" style="width:100%;justify-content:center;margin-top:18px;padding:13px;">
                ➕ Add Item
            </button>
        </div>
    </div>

    <!-- RIGHT: live preview -->
    <div style="position:sticky;top:88px;">
        <div class="mf-img-card">
            <div class="mf-img-card-header">Image Preview</div>
            <div class="mf-img-placeholder" id="imgPreview">
                <span style="font-size:2.5rem;">🖼️</span>
                <span>No image selected</span>
            </div>
            <div class="mf-img-card-body" style="font-size:.78rem;color:#aaa;text-align:center;">
                Preview updates instantly when you choose a file
            </div>
        </div>
    </div>

</div>
</form>

<?php require_once __DIR__ . '/partials/end.php'; ?>

<script>
document.getElementById('fileInput').addEventListener('change', function() {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        const wrap = document.getElementById('imgPreview');
        let img = wrap.tagName === 'IMG' ? wrap : null;
        if (!img) {
            img = document.createElement('img');
            img.id = 'imgPreview';
            img.className = 'mf-img-preview';
            wrap.replaceWith(img);
        }
        img.src = e.target.result;
    };
    reader.readAsDataURL(file);
});
</script>

</body>
</html>
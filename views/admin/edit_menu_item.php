<?php

session_start();

require_once __DIR__ . '/../../config/database.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: /WTech Project/views/auth/login.php");
    exit();
}

$id = (int) $_GET['id'];

$itemStmt = $pdo->prepare("
    SELECT * FROM menu_items
    WHERE id = ?
");

$itemStmt->execute([$id]);

$item = $itemStmt->fetch(PDO::FETCH_ASSOC);

if (!$item) {
    die("Item not found");
}

$categories = $pdo->query("
    SELECT * FROM categories
")->fetchAll(PDO::FETCH_ASSOC);

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $category_id = (int) $_POST['category_id'];
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = $_POST['price'];
    $is_available = isset($_POST['is_available']) ? 1 : 0;

    if (empty($name)) $errors[] = "Name is required";
    if (empty($description)) $errors[] = "Description is required";
    if (!is_numeric($price) || $price <= 0) $errors[] = "Price must be a positive number";

    $image_path = $item['image_path'];

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
            $newImagePath = uniqid('item_', true) . '.' . $ext;
            $uploadDir = __DIR__ . '/../../public/uploads/menu/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            if (!empty($item['image_path'])) {
                $oldFile = $uploadDir . $item['image_path'];
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }
            move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $newImagePath);
            $image_path = $newImagePath;
        }
    }

    if (empty($errors)) {

        $stmt = $pdo->prepare("
            UPDATE menu_items
            SET category_id = ?,
                name = ?,
                description = ?,
                price = ?,
                image_path = ?,
                is_available = ?
            WHERE id = ?
        ");

        $stmt->execute([
            $category_id,
            $name,
            $description,
            $price,
            $image_path,
            $is_available,
            $id
        ]);

        header("Location: menu_items.php");
        exit();
    }

    $item = array_merge($item, [
        'category_id' => $category_id,
        'name' => $name,
        'description' => $description,
        'price' => $price,
        'is_available' => $is_available,
    ]);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Menu Item — BiteRush Admin</title>
    <style>
        .mf-layout { display:grid; grid-template-columns:1fr 280px; gap:22px; align-items:start; }
        @media(max-width:860px){ .mf-layout{ grid-template-columns:1fr; } }

        /* image preview card */
        .mf-img-card { background:white; border-radius:14px; box-shadow:0 2px 12px rgba(0,0,0,.06); overflow:hidden; }
        .mf-img-card-header { padding:14px 18px; border-bottom:2px solid #f0f2f5; font-size:.78rem; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:#aaa; }
        .mf-img-preview { width:100%; aspect-ratio:4/3; object-fit:cover; display:block; background:#f4f6fb; }
        .mf-img-placeholder { width:100%; aspect-ratio:4/3; background:#f4f6fb; display:flex; align-items:center; justify-content:center; font-size:3rem; color:#dde3f0; }
        .mf-img-card-body { padding:16px 18px; }

        /* toggle switch */
        .mf-toggle-wrap { display:flex; align-items:center; gap:12px; padding:14px 0; }
        .mf-toggle { position:relative; width:46px; height:26px; flex-shrink:0; }
        .mf-toggle input { opacity:0; width:0; height:0; }
        .mf-toggle-slider {
            position:absolute; inset:0; border-radius:26px;
            background:#dde3f0; cursor:pointer; transition:background .2s;
        }
        .mf-toggle-slider::before {
            content:''; position:absolute;
            width:20px; height:20px; border-radius:50%;
            background:white; left:3px; top:3px;
            transition:transform .2s;
            box-shadow:0 1px 4px rgba(0,0,0,.2);
        }
        .mf-toggle input:checked + .mf-toggle-slider { background:#1565c0; }
        .mf-toggle input:checked + .mf-toggle-slider::before { transform:translateX(20px); }
        .mf-toggle-label { font-size:.92rem; font-weight:700; color:#333; }
    </style>
</head>
<body>

<?php
$adminPageTitle = 'Edit Menu Item';
require_once __DIR__ . '/partials/sidebar.php';
?>

<a href="menu_items.php" style="display:inline-flex;align-items:center;gap:6px;font-size:.88rem;font-weight:600;color:#1565c0;text-decoration:none;margin-bottom:22px;">← Back to Menu Items</a>

<div style="font-size:1.4rem;font-weight:900;color:#0a0a0a;margin-bottom:22px;">✏️ Edit — <?= htmlspecialchars($item['name']) ?></div>

<?php if (!empty($errors)): ?>
    <div class="adm-flash adm-flash-error" style="margin-bottom:18px;">
        ❌ <?= implode(' &nbsp;·&nbsp; ', array_map('htmlspecialchars', $errors)) ?>
    </div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
<div class="mf-layout">

    <!-- LEFT: form fields -->
    <div class="adm-card">
        <div class="adm-card-header"><div class="adm-card-title">Item Details</div></div>
        <div style="padding:20px 24px;">

            <div class="adm-field">
                <label>Category</label>
                <select name="category_id">
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $item['category_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="adm-field">
                <label>Item Name</label>
                <input type="text" name="name" placeholder="e.g. Crispy Chicken Burger"
                       value="<?= htmlspecialchars($item['name']) ?>" required>
            </div>

            <div class="adm-field">
                <label>Description</label>
                <textarea name="description" rows="3" placeholder="Short description of the item…"><?= htmlspecialchars($item['description']) ?></textarea>
            </div>

            <div class="adm-field">
                <label>Price (৳)</label>
                <input type="number" step="0.01" min="0.01" name="price"
                       placeholder="0.00" value="<?= htmlspecialchars($item['price']) ?>" required>
            </div>

            <div class="adm-field">
                <label>Replace Image <span style="font-weight:400;color:#aaa;">(JPEG/PNG · max 2 MB · leave blank to keep current)</span></label>
                <input type="file" name="image" accept="image/jpeg,image/png">
            </div>

            <div class="mf-toggle-wrap" style="border-top:2px solid #f0f2f5;margin-top:6px;">
                <label class="mf-toggle">
                    <input type="checkbox" name="is_available" value="1" <?= $item['is_available'] ? 'checked' : '' ?>>
                    <span class="mf-toggle-slider"></span>
                </label>
                <span class="mf-toggle-label">Available on menu</span>
            </div>

            <button type="submit" class="adm-btn adm-btn-primary" style="width:100%;justify-content:center;margin-top:18px;padding:13px;">
                💾 Save Changes
            </button>
        </div>
    </div>

    <!-- RIGHT: image preview -->
    <div style="position:sticky;top:88px;">
        <div class="mf-img-card">
            <div class="mf-img-card-header">Current Image</div>
            <?php if (!empty($item['image_path'])): ?>
                <img class="mf-img-preview" id="imgPreview"
                     src="/WTech Project/public/uploads/menu/<?= htmlspecialchars($item['image_path']) ?>"
                     alt="<?= htmlspecialchars($item['name']) ?>">
            <?php else: ?>
                <div class="mf-img-placeholder" id="imgPreview">🍽️</div>
            <?php endif; ?>
            <div class="mf-img-card-body" style="font-size:.78rem;color:#aaa;text-align:center;">
                Select a new file to replace
            </div>
        </div>
    </div>

</div>
</form>

<?php require_once __DIR__ . '/partials/end.php'; ?>

<script>
/* Live image preview when a new file is chosen */
document.querySelector('input[type=file][name=image]').addEventListener('change', function() {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        const preview = document.getElementById('imgPreview');
        let img = preview.tagName === 'IMG' ? preview : null;
        if (!img) {
            img = document.createElement('img');
            img.id = 'imgPreview';
            img.className = 'mf-img-preview';
            preview.replaceWith(img);
        }
        img.src = e.target.result;
    };
    reader.readAsDataURL(file);
});
</script>

</body>
</html>
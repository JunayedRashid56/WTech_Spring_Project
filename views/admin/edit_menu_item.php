<?php

session_start();

require_once __DIR__ . '/../../config/database.php';

$id = $_GET['id'];

$itemStmt = $pdo->prepare("
    SELECT * FROM menu_items
    WHERE id = ?
");

$itemStmt->execute([$id]);

$item = $itemStmt->fetch(PDO::FETCH_ASSOC);

$categories = $pdo->query("
    SELECT * FROM categories
")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $category_id = $_POST['category_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    $stmt = $pdo->prepare("
        UPDATE menu_items
        SET category_id = ?,
            name = ?,
            description = ?,
            price = ?
        WHERE id = ?
    ");

    $stmt->execute([
        $category_id,
        $name,
        $description,
        $price,
        $id
    ]);

    header("Location: menu_items.php");
    exit();

}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Edit Menu Item</title>

</head>

<body>

<h1>Edit Menu Item</h1>

<form method="POST">

<select name="category_id">

<?php foreach($categories as $category): ?>

<option value="<?php echo $category['id']; ?>"

<?php
if ($category['id'] == $item['category_id']) {
    echo "selected";
}
?>

>

<?php echo $category['name']; ?>

</option>

<?php endforeach; ?>

</select>

<br><br>

<input type="text"
       name="name"
       value="<?php echo $item['name']; ?>">

<br><br>

<textarea name="description"><?php echo $item['description']; ?></textarea>

<br><br>

<input type="number"
       step="0.01"
       name="price"
       value="<?php echo $item['price']; ?>">

<br><br>

<button type="submit">
    Update Item
</button>

</form>

</body>

</html>
<?php

session_start();

require_once __DIR__ . '/../../config/database.php';

$categories = $pdo->query("
    SELECT * FROM categories
")->fetchAll(PDO::FETCH_ASSOC);

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $category_id = $_POST['category_id'];
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = $_POST['price'];

    $image = $_FILES['image']['name'];

    $tmp = $_FILES['image']['tmp_name'];

    move_uploaded_file(
        $tmp,
        "../../public/uploads/" . $image
    );

    $stmt = $pdo->prepare("
        INSERT INTO menu_items
        (category_id, name, description, price, image)
        VALUES (?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $category_id,
        $name,
        $description,
        $price,
        $image
    ]);

    $message = "Menu Item Added";

}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Add Menu Item</title>

</head>

<body>

<h1>Add Menu Item</h1>

<form method="POST"
      enctype="multipart/form-data">

    <select name="category_id">

        <?php foreach($categories as $category): ?>

        <option value="<?php echo $category['id']; ?>">

            <?php echo $category['name']; ?>

        </option>

        <?php endforeach; ?>

    </select>

    <br><br>

    <input type="text"
           name="name"
           placeholder="Item Name">

    <br><br>

    <textarea name="description"
              placeholder="Description"></textarea>

    <br><br>

    <input type="number"
           step="0.01"
           name="price"
           placeholder="Price">

    <br><br>

    <input type="file"
           name="image">

    <br><br>

    <button type="submit">
        Add Item
    </button>

</form>

<p>
    <?php echo $message; ?>
</p>

</body>

</html>
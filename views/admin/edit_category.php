<?php

session_start();

require_once __DIR__ . '/../../config/database.php';

$id = $_GET['id'];

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
<html>

<head>

    <title>Edit Category</title>

</head>

<body>

<h1>Edit Category</h1>

<form method="POST">

    <input type="text"
           name="name"
           value="<?php echo $category['name']; ?>">

    <br><br>

    <button type="submit">
        Update Category
    </button>

</form>

<p>
    <?php echo $message; ?>
</p>

</body>

</html>
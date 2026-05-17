<?php

session_start();

require_once __DIR__ . '/../../config/database.php';

if ($_SESSION['role'] !== 'admin') {

    die("Access Denied");

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
<html>

<head>

    <title>Add Category</title>

</head>

<body>

<h1>Add Category</h1>

<form method="POST">

    <input type="text"
           name="name"
           placeholder="Category Name">

    <br><br>

    <button type="submit">
        Add Category
    </button>

</form>

<p>
    <?php echo $message; ?>
</p>

</body>

</html>
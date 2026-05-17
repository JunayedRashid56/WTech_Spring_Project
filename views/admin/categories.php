<?php

session_start();

require_once __DIR__ . '/../../config/database.php';

$stmt = $pdo->query("
    SELECT * FROM categories
");

$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Categories</title>

</head>

<body>

<h1>All Categories</h1>

<a href="add_category.php">
    Add Category
</a>

<br><br>

<table border="1" cellpadding="10">

    <tr>

        <th>ID</th>
        <th>Name</th>
        <th>Actions</th>

    </tr>

    <?php foreach($categories as $category): ?>

    <tr>

        <td>
            <?php echo $category['id']; ?>
        </td>

        <td>
            <?php echo $category['name']; ?>
        </td>

        <td>

            <a href="edit_category.php?id=<?php echo $category['id']; ?>">
                Edit
            </a>

            |

            <a href="delete_category.php?id=<?php echo $category['id']; ?>">
                Delete
            </a>

        </td>

    </tr>

    <?php endforeach; ?>

</table>

</body>

</html>
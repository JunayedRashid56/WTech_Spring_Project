<?php

session_start();

require_once __DIR__ . '/../../config/database.php';

$stmt = $pdo->query("
    SELECT menu_items.*, categories.name AS category_name
    FROM menu_items
    JOIN categories
    ON menu_items.category_id = categories.id
");

$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Menu Items</title>

</head>

<body>

<h1>Menu Items</h1>

<a href="add_menu_item.php">
    Add Menu Item
</a>

<br><br>

<table border="1" cellpadding="10">

<tr>

    <th>ID</th>
    <th>Image</th>
    <th>Name</th>
    <th>Category</th>
    <th>Price</th>
    <th>Actions</th>

</tr>

<?php foreach($items as $item): ?>

<tr>

    <td>
        <?php echo $item['id']; ?>
    </td>

    <td>

        <img src="../../public/uploads/<?php echo $item['image']; ?>"
             width="80">

    </td>

    <td>
        <?php echo $item['name']; ?>
    </td>

    <td>
        <?php echo $item['category_name']; ?>
    </td>

    <td>
        $<?php echo $item['price']; ?>
    </td>

    <td>

        <a href="edit_menu_item.php?id=<?php echo $item['id']; ?>">
            Edit
        </a>

        |

        <a href="delete_menu_item.php?id=<?php echo $item['id']; ?>">
            Delete
        </a>

    </td>

</tr>

<?php endforeach; ?>

</table>

</body>

</html>
<?php

session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {

    header("Location: ../auth/login.php");
    exit();

}

require_once __DIR__ . '/../../config/database.php';

try {

    // Total Categories
    $totalCategories = $pdo->query("
        SELECT COUNT(*) 
        FROM categories
    ")->fetchColumn();

    // Total Menu Items
    $totalItems = $pdo->query("
        SELECT COUNT(*) 
        FROM menu_items
    ")->fetchColumn();

    // Unavailable Items
    $unavailableItems = $pdo->query("
        SELECT COUNT(*) 
        FROM menu_items
        WHERE is_available = 0
    ")->fetchColumn();

} catch(PDOException $e) {

    die("SQL Error: " . $e->getMessage());

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard</title>

    <style>

        body {

            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;

        }

        h1 {

            text-align: center;
            color: #333;

        }

        .dashboard {

            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 40px;
            flex-wrap: wrap;

        }

        .card {

            background: white;
            width: 250px;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);

        }

        .card h2 {

            color: #444;

        }

        .card p {

            font-size: 35px;
            color: blue;
            font-weight: bold;

        }

    </style>

</head>

<body>

    <h1>Admin Dashboard</h1>

    <div class="dashboard">

        <div class="card">

            <h2>Total Categories</h2>

            <p>
                <?php echo $totalCategories; ?>
            </p>

        </div>

        <div class="card">

            <h2>Total Menu Items</h2>

            <p>
                <?php echo $totalItems; ?>
            </p>

        </div>

        <div class="card">

            <h2>Unavailable Items</h2>

            <p>
                <?php echo $unavailableItems; ?>
            </p>

        </div>

    </div>

</body>

</html>
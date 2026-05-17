<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Home</title>

    <link rel="stylesheet" href="../../public/css/style.css">

</head>

<body>

    <?php include '../views/partials/navbar.php'; ?>

    <div class="container">

        <h1>Welcome To Food Ordering System</h1>

        <p>
            Order your favourite food online.
        </p>

        <?php if(isset($_SESSION['user_id'])): ?>

            <h3>
                Welcome <?= htmlspecialchars($_SESSION['name']) ?>
            </h3>

        <?php endif; ?>

    </div>

</body>

</html>
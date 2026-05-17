<?php
session_start();
?>

<!DOCTYPE html>
<html>

<head>

    <title>Register</title>

    <link rel="stylesheet" href="../../public/css/style.css">

</head>

<body>

    <?php include '../partials/navbar.php'; ?>

    <div class="container">

        <h2>Register</h2>

        <?php
        if (isset($_SESSION['errors'])) {

            foreach ($_SESSION['errors'] as $error) {
                echo "<p class='error'>$error</p>";
            }

            unset($_SESSION['errors']);
        }
        ?>

        <form method="POST" action="../../controllers/AuthController.php?action=register">

            <input type="text" name="name" placeholder="Name">

            <input type="email" name="email" placeholder="Email">

            <input type="password" name="password" placeholder="Password">

            <textarea name="address" placeholder="Delivery Address"></textarea>

            <button type="submit">Register</button>

        </form>

    </div>

</body>

</html>
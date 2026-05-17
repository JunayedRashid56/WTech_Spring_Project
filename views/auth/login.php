<?php
session_start();
?>

<!DOCTYPE html>
<html>

<head>

    <title>Login</title>

    <link rel="stylesheet" href="../../public/css/style.css">

</head>

<body>

    <?php include '../partials/navbar.php'; ?>

    <div class="container">

        <h2>Login</h2>

        <?php

        if (isset($_SESSION['success'])) {

            echo "<p class='success'>" . $_SESSION['success'] . "</p>";

            unset($_SESSION['success']);
        }

        if (isset($_SESSION['error'])) {

            echo "<p class='error'>" . $_SESSION['error'] . "</p>";

            unset($_SESSION['error']);
        }
        ?>

        <form method="POST" action="../../controllers/AuthController.php?action=login">

            <input type="email" name="email" placeholder="Email">

            <input type="password" name="password" placeholder="Password">

            <label>
                <input type="checkbox" name="remember">
                Remember Me
            </label>

            <button type="submit">Login</button>

        </form>

    </div>

</body>

</html>
<?php

require_once __DIR__ . '/../../config/auth_check.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/User.php';

$userModel = new User($pdo);

$user = $userModel->findByEmail($_SESSION['email']);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Profile</title>

    <link rel="stylesheet" href="../../public/css/style.css">

</head>

<body>

    <?php include '../partials/navbar.php'; ?>

    <div class="container">

        <h2>My Profile</h2>

        <?php if (isset($_SESSION['success'])): ?>

            <p class="success">
                <?= $_SESSION['success'] ?>
            </p>

            <?php unset($_SESSION['success']); ?>

        <?php endif; ?>


        <?php if (isset($_SESSION['error'])): ?>

            <p class="error">
                <?= $_SESSION['error'] ?>
            </p>

            <?php unset($_SESSION['error']); ?>

        <?php endif; ?>


        <form method="POST"
            action="../../controllers/ProfileController.php?action=updateProfile">

            <label>Name</label>

            <input
                type="text"
                name="name"
                value="<?= htmlspecialchars($user['name']) ?>">

            <label>Email</label>

            <input
                type="email"
                name="email"
                value="<?= htmlspecialchars($user['email']) ?>">

            <label>Delivery Address</label>

            <textarea
                name="address"><?= htmlspecialchars($user['delivery_address']) ?></textarea>

            <button type="submit">
                Update Profile
            </button>

        </form>

        <hr>

        <h2>Change Password</h2>

        <form method="POST"
            action="../../controllers/ProfileController.php?action=changePassword">

            <label>Current Password</label>

            <input
                type="password"
                name="current_password"
                placeholder="Enter Current Password">

            <label>New Password</label>

            <input
                type="password"
                name="new_password"
                placeholder="Enter New Password">

            <button type="submit">
                Change Password
            </button>

        </form>

    </div>

</body>

</html>
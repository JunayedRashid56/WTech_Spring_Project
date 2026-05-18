<?php

session_start();

require_once '../config/database.php';
require_once '../models/User.php';

if (!isset($_SESSION['user_id'])) {

    header("Location: ../views/auth/login.php");
    exit;
}

$userModel = new User($pdo);

$action = $_GET['action'] ?? '';

if ($action == 'updateProfile') {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);

    $userModel->updateProfile(
        $_SESSION['user_id'],
        $name,
        $email,
        $address
    );

    $_SESSION['success'] = "Profile updated successfully";

    $_SESSION['name'] = $name;
    $_SESSION['email'] = $email;

    header("Location: ../views/profile/profile.php");
    exit;
}

if ($action == 'changePassword') {

    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];

    $user = $userModel->findByEmail($_SESSION['email']);

    if (!password_verify($current_password, $user['password_hash'])) {

        $_SESSION['error'] = "Current password incorrect";

        header("Location: ../views/profile/profile.php");
        exit;
    }

    $new_hash = password_hash(
        $new_password,
        PASSWORD_DEFAULT
    );

    $userModel->updatePassword(
        $_SESSION['user_id'],
        $new_hash
    );

    $_SESSION['success'] = "Password updated successfully";

    header("Location: ../views/profile/profile.php");
    exit;
}

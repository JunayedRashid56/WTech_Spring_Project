<?php

session_start();

require_once '../config/database.php';
require_once '../models/User.php';

$userModel = new User($pdo);

$action = $_GET['action'] ?? '';

if ($action == 'register') {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $address = trim($_POST['address']);

    $errors = [];

    if (empty($name)) {
        $errors[] = "Name is required";
    }

    if (empty($email)) {
        $errors[] = "Email is required";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters";
    }

    if (empty($address)) {
        $errors[] = "Address is required";
    }

    $existingUser = $userModel->findByEmail($email);

    if ($existingUser) {
        $errors[] = "Email already exists";
    }

    if (count($errors) > 0) {

        $_SESSION['errors'] = $errors;
        $_SESSION['old'] = $_POST;

        header("Location: ../views/auth/register.php");
        exit;
    }

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $userModel->create(
        $name,
        $email,
        $password_hash,
        $address
    );

    $_SESSION['success'] = "Registration successful";

    header("Location: ../views/auth/login.php");
    exit;
}
if ($action == 'login') {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $user = $userModel->findByEmail($email);

    if (!$user || !password_verify($password, $user['password_hash'])) {

        $_SESSION['error'] = "Invalid email or password";

        header("Location: ../views/auth/login.php");
        exit;
    }

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['name'] = $user['name'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['email'] = $user['email'];

    if (isset($_POST['remember'])) {

        $token = bin2hex(random_bytes(32));
        $hashedToken = hash('sha256', $token);

        $userModel->updateRememberToken(
            $user['id'],
            $hashedToken
        );

        setcookie(
            "remember_token",
            $token,
            time() + (86400 * 30),
            "/"
        );
    }

    if ($user['role'] === 'admin') {
        header("Location: ../views/admin/dashboard.php");
    } else {
        header("Location: ../routes/web.php");
    }
    exit;
}

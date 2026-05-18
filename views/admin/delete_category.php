<?php

session_start();

require_once __DIR__ . '/../../config/database.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: /WTech Project/views/auth/login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: categories.php");
    exit();
}

$id = (int) $_GET['id'];

$check = $pdo->prepare("SELECT COUNT(*) FROM menu_items WHERE category_id = ?");
$check->execute([$id]);

if ($check->fetchColumn() > 0) {
    $_SESSION['error'] = "Cannot delete: this category has menu items linked to it.";
    header("Location: categories.php");
    exit();
}

$stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
$stmt->execute([$id]);

$_SESSION['success'] = "Category deleted successfully.";
header("Location: categories.php");
exit();

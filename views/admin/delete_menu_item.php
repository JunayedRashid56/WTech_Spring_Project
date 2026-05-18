<?php

session_start();

require_once __DIR__ . '/../../config/database.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: /WTech Project/views/auth/login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: menu_items.php");
    exit();
}

$id = (int) $_GET['id'];

$itemStmt = $pdo->prepare("SELECT image_path FROM menu_items WHERE id = ?");
$itemStmt->execute([$id]);
$item = $itemStmt->fetch(PDO::FETCH_ASSOC);

if (!$item) {
    header("Location: menu_items.php");
    exit();
}

if (!empty($item['image_path'])) {
    $filePath = __DIR__ . '/../../public/uploads/menu/' . $item['image_path'];
    if (file_exists($filePath)) {
        unlink($filePath);
    }
}

$stmt = $pdo->prepare("DELETE FROM menu_items WHERE id = ?");
$stmt->execute([$id]);

$_SESSION['success'] = "Menu item deleted successfully.";
header("Location: menu_items.php");
exit();

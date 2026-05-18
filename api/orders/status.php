<?php

session_start();

header('Content-Type: application/json');

require_once __DIR__ . '/../../models/Order.php';

if (!isset($_SESSION['user_id'])) {

    echo json_encode([
        'ok' => false,
        'message' => 'Unauthorized'
    ]);

    exit;
}

if (!isset($_GET['id'])) {

    echo json_encode([
        'ok' => false,
        'message' => 'Missing Order ID'
    ]);

    exit;
}

$orderId = $_GET['id'];
$userId = $_SESSION['user_id'];

$orderModel = new Order();

$status = $orderModel->getOrderStatus($orderId, $userId);

if (!$status) {

    echo json_encode([
        'ok' => false,
        'message' => 'Order not found'
    ]);

    exit;
}

echo json_encode([
    'ok' => true,
    'status' => $status['status']
]);
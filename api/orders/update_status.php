<?php

session_start();

header('Content-Type: application/json');

require_once '../../models/Order.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {

    echo json_encode([
        'ok' => false,
        'message' => 'Unauthorized'
    ]);

    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['order_id']) || !isset($data['status'])) {

    echo json_encode([
        'ok' => false,
        'message' => 'Invalid Data'
    ]);

    exit;
}

$orderId = $data['order_id'];
$newStatus = $data['status'];

$orderModel = new Order();

$order = $orderModel->getSingleOrder($orderId);

if (!$order) {

    echo json_encode([
        'ok' => false,
        'message' => 'Order not found'
    ]);

    exit;
}

$currentStatus = $order['status'];

$allowedTransitions = [
    'Pending' => 'Preparing',
    'Preparing' => 'Out for Delivery',
    'Out for Delivery' => 'Delivered'
];

if (
    !isset($allowedTransitions[$currentStatus]) ||
    $allowedTransitions[$currentStatus] !== $newStatus
) {

    echo json_encode([
        'ok' => false,
        'message' => 'Invalid status transition'
    ]);

    exit;
}

$updated = $orderModel->updateOrderStatus($orderId, $newStatus);

if ($updated) {

    echo json_encode([
        'ok' => true,
        'status' => $newStatus
    ]);
}
else {

    echo json_encode([
        'ok' => false,
        'message' => 'Update Failed'
    ]);
}
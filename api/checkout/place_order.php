<?php
header('Content-Type: application/json');

session_start();

require_once '../../models/Order.php';
require_once '../../models/MenuItem.php';

if (!isset($_SESSION['user_id'])) {

    echo json_encode([
        'success' => false,
        'message' => 'Please Login'
    ]);

    exit;
}

if (empty($_SESSION['cart'])) {

    echo json_encode([
        'success' => false,
        'message' => 'Cart Empty'
    ]);

    exit;
}

$deliveryAddress = trim($_POST['delivery_address']);
$paymentMethod = trim($_POST['payment_method']);

if (empty($deliveryAddress)) {

    echo json_encode([
        'success' => false,
        'message' => 'Delivery Address Required'
    ]);

    exit;
}

$menuItem = new MenuItem();

$cartItems = [];
$grandTotal = 0;

foreach ($_SESSION['cart'] as $itemId => $quantity) {

    $item = $menuItem->getItemById($itemId);

    if ($item) {

        $item['quantity'] = $quantity;

        $grandTotal += ($item['price'] * $quantity);

        $cartItems[] = $item;
    }
}

$order = new Order();

$orderId = $order->createOrder(
    $_SESSION['user_id'],
    $grandTotal,
    $deliveryAddress,
    $paymentMethod,
    $cartItems
);

if (!$orderId) {

    echo json_encode([
        'success' => false,
        'message' => 'Order Failed'
    ]);

    exit;
}

unset($_SESSION['cart']);

$_SESSION['last_order_id'] = $orderId;
$_SESSION['last_order_items'] = $cartItems;
$_SESSION['last_order_total'] = $grandTotal;

echo json_encode([
    'success' => true,
    'order_id' => $orderId
]);

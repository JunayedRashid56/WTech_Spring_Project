<?php
header('Content-Type: application/json');

require_once '../../models/Cart.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid Request'
    ]);

    exit;
}

if (!isset($_POST['item_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Item ID Missing'
    ]);

    exit;
}

$itemId = intval($_POST['item_id']);

$cart = new Cart();

$cart->add($itemId);

echo json_encode([
    'success' => true,
    'cart_count' => $cart->getTotalCount()
]);
?>
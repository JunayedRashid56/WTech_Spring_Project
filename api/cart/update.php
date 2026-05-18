<?php
header('Content-Type: application/json');

require_once '../../models/Cart.php';
require_once '../../models/MenuItem.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false
    ]);

    exit;
}

$itemId = intval($_POST['item_id']);
$quantity = intval($_POST['quantity']);

$cart = new Cart();
$cart->update($itemId, $quantity);

$menuItem = new MenuItem();
$item = $menuItem->getItemById($itemId);

$lineTotal = $item['price'] * $quantity;

$grandTotal = 0;

foreach ($cart->getCart() as $id => $qty) {

    $cartItem = $menuItem->getItemById($id);

    $grandTotal += ($cartItem['price'] * $qty);
}

echo json_encode([
    'success' => true,
    'line_total' => $lineTotal,
    'grand_total' => $grandTotal,
    'cart_count' => $cart->getTotalCount()
]);
?>
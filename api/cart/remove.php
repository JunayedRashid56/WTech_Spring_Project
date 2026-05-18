<?php
header('Content-Type: application/json');

require_once '../../models/Cart.php';
require_once '../../models/MenuItem.php';

$itemId = intval($_POST['item_id']);

$cart = new Cart();
$cart->remove($itemId);

$menuItem = new MenuItem();

$grandTotal = 0;

foreach ($cart->getCart() as $id => $qty) {

    $cartItem = $menuItem->getItemById($id);

    $grandTotal += ($cartItem['price'] * $qty);
}

echo json_encode([
    'success' => true,
    'grand_total' => $grandTotal,
    'cart_count' => $cart->getTotalCount()
]);
?>
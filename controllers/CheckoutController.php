<?php
require_once __DIR__ . '/../models/Cart.php';
require_once __DIR__ . '/../models/MenuItem.php';

class CheckoutController
{
    private $cart;
    private $menuItem;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->cart = new Cart();
        $this->menuItem = new MenuItem();
    }

    public function index()
    {
        if (!isset($_SESSION['user_id'])) {
            die('Please Login First');
        }

        $cartItems = [];
        $grandTotal = 0;

        foreach ($this->cart->getCart() as $itemId => $quantity) {

            $item = $this->menuItem->getItemById($itemId);

            if ($item) {

                $item['quantity'] = $quantity;
                $item['total'] = $item['price'] * $quantity;

                $grandTotal += $item['total'];

                $cartItems[] = $item;
            }
        }

        require_once __DIR__ . '/../views/checkout/checkout.php';
    }
}
?>
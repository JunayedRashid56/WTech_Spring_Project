<?php
require_once __DIR__ . '/../models/Cart.php';
require_once __DIR__ . '/../models/MenuItem.php';

class CartController
{
    private $cart;
    private $menuItem;

    public function __construct()
    {
        $this->cart = new Cart();
        $this->menuItem = new MenuItem();
    }

    public function index()
    {
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

        require_once __DIR__ . '/../views/cart/index.php';
    }
}
?>
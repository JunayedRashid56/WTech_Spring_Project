<?php
class Cart
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    public function add($itemId)
    {
        if (isset($_SESSION['cart'][$itemId])) {
            $_SESSION['cart'][$itemId]++;
        } else {
            $_SESSION['cart'][$itemId] = 1;
        }
    }

    public function update($itemId, $quantity)
    {
        if ($quantity <= 0) {
            unset($_SESSION['cart'][$itemId]);
        } else {
            $_SESSION['cart'][$itemId] = $quantity;
        }
    }

    public function remove($itemId)
    {
        unset($_SESSION['cart'][$itemId]);
    }

    public function getCart()
    {
        return $_SESSION['cart'];
    }

    public function getTotalCount()
    {
        return array_sum($_SESSION['cart']);
    }
}
?>
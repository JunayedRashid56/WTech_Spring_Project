<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Cart.php';
require_once __DIR__ . '/../models/MenuItem.php';
require_once __DIR__ . '/../models/User.php';

class CheckoutController
{
    private $cart;
    private $menuItem;
    private $userModel;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        global $pdo;
        $this->cart = new Cart();
        $this->menuItem = new MenuItem();
        $this->userModel = new User($pdo);
    }

    public function index()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /WTech Project/views/auth/login.php');
            exit;
        }

        $user = $this->userModel->findByEmail($_SESSION['email']);
        $deliveryAddress = $user ? $user['delivery_address'] : '';

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
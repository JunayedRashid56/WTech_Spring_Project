<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Order.php';

class OrderController
{
    private $orderModel;

    public function __construct()
    {
        global $pdo;
        $this->orderModel = new Order($pdo);
    }

    public function myOrders()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /WTech Project/views/auth/login.php');
            exit;
        }

        $userId = $_SESSION['user_id'];

        $orders = $this->orderModel->getOrdersByUser($userId);

        $orderModel = $this->orderModel;

        require_once __DIR__ . '/../views/orders/my_orders.php';
    }
}
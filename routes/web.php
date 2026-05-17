<?php

$routes = [

    '/' => '../public/index.php',

    '/login' => '../views/auth/login.php',

    '/register' => '../views/auth/register.php',

    '/profile' => '../views/profile/profile.php'

];

?>

<?php
require_once '../controllers/MenuController.php';
require_once __DIR__ . '/../controllers/OrderController.php';

$controller = new MenuController();
$controller->index();

$controller = new OrderController();
$controller->myOrders();
?>
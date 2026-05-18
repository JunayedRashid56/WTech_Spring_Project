<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/MenuItem.php';
require_once __DIR__ . '/../controllers/MenuController.php';

$controller = new MenuController();
$controller->index();

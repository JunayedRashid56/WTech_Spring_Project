<?php
header('Content-Type: application/json');

require_once '../../models/MenuItem.php';

$search = trim($_GET['q'] ?? '');

if ($search === '') {
    $items = MenuItem::getAllAvailableItems();
} else {
    $items = MenuItem::searchItems($search);
}

echo json_encode([
    'success' => true,
    'items' => $items
]);

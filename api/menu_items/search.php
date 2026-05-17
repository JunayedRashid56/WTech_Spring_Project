<?php
header('Content-Type: application/json');

require_once '../../models/MenuItem.php';

if (!isset($_GET['q'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Search query missing'
    ]);
    exit;
}

$search = trim($_GET['q']);

$menuItem = new MenuItem();

$items = $menuItem->searchItems($search);

echo json_encode([
    'success' => true,
    'items' => $items
]);

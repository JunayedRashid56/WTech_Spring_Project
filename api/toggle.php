<?php
header('Content-Type: application/json');

require_once '../models/MenuItem.php';

$data = json_decode(file_get_contents("php://input"), true);

$id = $data['id'];

$result = MenuItem::toggleAvailability($id);

echo json_encode([
    'ok' => true,
    'is_available' => $result['is_available']
]);
?>
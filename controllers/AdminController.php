<?php

require_once __DIR__ . '/../config/database.php';

class AdminController {

    // Dashboard statistics
    public static function getDashboardData() {

        global $conn;

        // Total categories
        $categoryQuery = $conn->query("
            SELECT COUNT(*) as total_categories
            FROM categories
        ");

        $categories = $categoryQuery->fetch(PDO::FETCH_ASSOC);

        // Total menu items
        $itemQuery = $conn->query("
            SELECT COUNT(*) as total_items
            FROM menu_items
        ");

        $items = $itemQuery->fetch(PDO::FETCH_ASSOC);

        // Unavailable items
        $unavailableQuery = $conn->query("
            SELECT COUNT(*) as unavailable_items
            FROM menu_items
            WHERE is_available = 0
        ");

        $unavailable = $unavailableQuery->fetch(PDO::FETCH_ASSOC);

        return [

            'total_categories' => $categories['total_categories'],

            'total_items' => $items['total_items'],

            'unavailable_items' => $unavailable['unavailable_items']

        ];
    }

}
?>
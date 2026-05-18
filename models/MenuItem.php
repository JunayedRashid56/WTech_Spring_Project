<?php
require_once __DIR__ . '/../config/database.php';

class MenuItem
{
    // part2
    public static function getAll()
    {
        global $conn;

        $stmt = $conn->prepare("SELECT menu_items.*, categories.name AS category_name
        FROM menu_items
        JOIN categories ON menu_items.category_id = categories.id
        ORDER BY menu_items.id DESC");

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create($data)
    {
        global $conn;

        $stmt = $conn->prepare("INSERT INTO menu_items
        (category_id, name, description, price, image_path, is_available)
        VALUES (?, ?, ?, ?, ?, ?)");

        return $stmt->execute([
            $data['category_id'],
            $data['name'],
            $data['description'],
            $data['price'],
            $data['image_path'],
            $data['is_available']
        ]);
    }

    public static function toggleAvailability($id)
    {
        global $conn;

        $stmt = $conn->prepare("UPDATE menu_items
        SET is_available = NOT is_available
        WHERE id = ?");

        $stmt->execute([$id]);

        $check = $conn->prepare("SELECT is_available FROM menu_items WHERE id = ?");
        $check->execute([$id]);

        return $check->fetch(PDO::FETCH_ASSOC);
    }

    // part 3
    public static function getAllAvailableItems()
    {
        global $conn;

        $query = "SELECT menu_items.*, categories.name AS category_name
                  FROM menu_items
                  JOIN categories ON menu_items.category_id = categories.id
                  WHERE menu_items.is_available = 1
                  ORDER BY categories.name ASC";

        $stmt = $conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function searchItems($search)
    {
        global $conn;

        $query = "SELECT menu_items.*, categories.name AS category_name
                  FROM menu_items
                  JOIN categories ON menu_items.category_id = categories.id
                  WHERE menu_items.is_available = 1
                  AND menu_items.name LIKE :search
                  ORDER BY categories.name ASC";

        $stmt = $conn->prepare($query);

        $searchTerm = "%" . $search . "%";

        $stmt->bindParam(':search', $searchTerm);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getItemById($id){
        global $conn;

        $query = "SELECT * FROM menu_items WHERE id = :id";

        $stmt = $conn->prepare($query);

        $stmt->bindParam(':id', $id);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

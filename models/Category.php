<?php
require_once __DIR__ . '/../config/database.php';

class Category
{

    public static function getAll()
    {
        global $conn;

        $stmt = $conn->prepare("SELECT * FROM categories ORDER BY id DESC");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create($name)
    {
        global $conn;

        $stmt = $conn->prepare("INSERT INTO categories(name) VALUES(?)");
        return $stmt->execute([$name]);
    }

    public static function find($id)
    {
        global $conn;

        $stmt = $conn->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function update($id, $name)
    {
        global $conn;

        $stmt = $conn->prepare("UPDATE categories SET name = ? WHERE id = ?");
        return $stmt->execute([$name, $id]);
    }

    public static function delete($id)
    {
        global $conn;

        $check = $conn->prepare("SELECT COUNT(*) FROM menu_items WHERE category_id = ?");
        $check->execute([$id]);

        if ($check->fetchColumn() > 0) {
            return false;
        }

        $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
        return $stmt->execute([$id]);
    }
}

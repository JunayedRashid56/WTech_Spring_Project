<?php
require_once __DIR__ . '/../config/database.php';

class Order
{
    private $conn;

    public function __construct()
    {
        // Bridges the gap between both versions. 
        // If your database.php uses $pdo, change $conn to $pdo here.
        global $conn;
        $this->conn = $conn;
    }

    // --- CREATE ORDER METHODS (From Code 1) ---
    public function createOrder(
        $userId,
        $totalAmount,
        $deliveryAddress,
        $paymentMethod,
        $cartItems
    ) {
        try {
            $this->conn->beginTransaction();

            $query = "INSERT INTO orders
            (
                user_id,
                total_amount,
                status,
                delivery_address,
                payment_method
            )
            VALUES
            (
                :user_id,
                :total_amount,
                'Pending',
                :delivery_address,
                :payment_method
            )";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':total_amount', $totalAmount);
            $stmt->bindParam(':delivery_address', $deliveryAddress);
            $stmt->bindParam(':payment_method', $paymentMethod);

            $stmt->execute();

            $orderId = $this->conn->lastInsertId();

            foreach ($cartItems as $item) {
                $query = "INSERT INTO order_items
                (
                    order_id,
                    menu_item_id,
                    quantity,
                    unit_price
                )
                VALUES
                (
                    :order_id,
                    :menu_item_id,
                    :quantity,
                    :unit_price
                )";

                $stmt = $this->conn->prepare($query);

                $stmt->bindParam(':order_id', $orderId);
                $stmt->bindParam(':menu_item_id', $item['id']);
                $stmt->bindParam(':quantity', $item['quantity']);
                $stmt->bindParam(':unit_price', $item['price']);

                $stmt->execute();
            }

            $this->conn->commit();

            return $orderId;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    // --- FETCH ORDER METHODS (From Code 2) ---
    public function getOrdersByUser($userId)
    {
        $sql = "SELECT
                    orders.id,
                    orders.total_amount,
                    orders.status,
                    orders.created_at,
                    COUNT(order_items.id) AS item_count
                FROM orders
                JOIN order_items
                    ON orders.id = order_items.order_id
                WHERE orders.user_id = ?
                GROUP BY orders.id
                ORDER BY orders.created_at DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOrderItems($orderId)
    {
        $sql = "SELECT
                    menu_items.name,
                    order_items.quantity,
                    order_items.unit_price
                FROM order_items
                JOIN menu_items
                    ON order_items.menu_item_id = menu_items.id
                WHERE order_items.order_id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$orderId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOrderStatus($orderId, $userId)
    {
        $sql = "SELECT status
            FROM orders
            WHERE id = ?
            AND user_id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$orderId, $userId]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllOrders($status = '', $date = '')
    {
        $sql = "SELECT 
                orders.id,
                orders.total_amount,
                orders.status,
                orders.created_at,
                users.name,
                users.delivery_address
            FROM orders
            JOIN users 
                ON orders.user_id = users.id
            WHERE 1=1";

        $params = [];

        if (!empty($status)) {
            $sql .= " AND orders.status = ?";
            $params[] = $status;
        }

        if (!empty($date)) {
            $sql .= " AND DATE(orders.created_at) = ?";
            $params[] = $date;
        }

        $sql .= " ORDER BY orders.created_at DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSingleOrder($orderId)
    {
        $sql = "SELECT status
            FROM orders
            WHERE id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$orderId]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateOrderStatus($orderId, $status)
    {
        $sql = "UPDATE orders
            SET status = ?
            WHERE id = ?";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([$status, $orderId]);
    }
}

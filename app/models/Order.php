<?php
// =============================================
// app/models/Order.php — ORDER DATA LAYER
// =============================================

class Order
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // New Order ဖန်တီးခြင်း
    public function create($userId, $totalPrice, $items)
    {
        try {
            $this->pdo->beginTransaction();

            // 1. Get max Order ID
            $maxStmt = $this->pdo->query("SELECT MAX(id) FROM orders");
            $orderId = (int)$maxStmt->fetchColumn() + 1;

            // 2. Insert into orders table
            $stmt = $this->pdo->prepare("
                INSERT INTO orders (id, user_id, total_price, status, created_at) 
                VALUES (:id, :user_id, :total_price, :status, NOW())
            ");
            $stmt->execute([
                ':id' => $orderId,
                ':user_id' => $userId,
                ':total_price' => $totalPrice,
                ':status' => 1 // 1 for pending/success for now
            ]);

            // 3. Insert into order_items table
            $itemStmt = $this->pdo->prepare("
                INSERT INTO order_items (id, order_id, item_id, quantity, unit_price) 
                VALUES (:id, :order_id, :item_id, :quantity, :unit_price)
            ");

            // Get base ID for order_items
            $maxItemStmt = $this->pdo->query("SELECT MAX(id) FROM order_items");
            $nextItemId = (int)$maxItemStmt->fetchColumn() + 1;

            foreach ($items as $item) {
                $itemStmt->execute([
                    ':id' => $nextItemId++,
                    ':order_id' => $orderId,
                    ':item_id' => $item['id'],
                    ':quantity' => $item['quantity'],
                    ':unit_price' => $item['price']
                ]);
            }

            $this->pdo->commit();
            return $orderId;

        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
}

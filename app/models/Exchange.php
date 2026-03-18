<?php
// =============================================
// app/models/Exchange.php — EXCHANGE DATA LAYER
// =============================================

class Exchange
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Get all exchange items
    public function getAll($limit = 10, $offset = 0)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM Exchange ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Count all exchange items
    public function countAll()
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM Exchange");
        return $stmt->fetchColumn();
    }

    // Create new exchange item
    public function create($data)
    {
        // Get max ID and increment
        $maxStmt = $this->pdo->query("SELECT MAX(id) FROM Exchange");
        $newId = (int)$maxStmt->fetchColumn() + 1;

        $stmt = $this->pdo->prepare("INSERT INTO Exchange (id, name, point_required, stock_qty) VALUES (:id, :name, :point_required, :stock_qty)");
        return $stmt->execute([
            ':id' => $newId,
            ':name' => $data['name'],
            ':point_required' => $data['point_required'],
            ':stock_qty' => $data['stock_qty']
        ]);
    }

    // Update exchange item
    public function update($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE Exchange SET name = :name, point_required = :point_required, stock_qty = :stock_qty WHERE id = :id");
        return $stmt->execute([
            ':id' => $id,
            ':name' => $data['name'],
            ':point_required' => $data['point_required'],
            ':stock_qty' => $data['stock_qty']
        ]);
    }

    // Delete exchange item
    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM Exchange WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}

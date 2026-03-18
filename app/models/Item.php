<?php
// =============================================
// app/models/Item.php — DATABASE LAYER
// =============================================
// ဤ file သည် Database နှင့် သာ ဆက်သွယ်သည်။
// Controller မှ ခေါ်ယူ၍ သုံးသည်။
// =============================================

class Item
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // ── Categories စာရင်း ─────────────────────
    public function getCategories()
    {
        $stmt = $this->pdo->query("SELECT id, name FROM Categories ORDER BY name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ── Events စာရင်း ─────────────────────────
    public function getEvents()
    {
        $stmt = $this->pdo->query("SELECT id, event_name FROM Event ORDER BY event_name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ── Items စုစုပေါင်း အရေအတွက် (Pagination) ─
    public function countAll()
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM Items");
        return $stmt->fetchColumn();
    }

    // ── Items စာရင်း (JOIN နှင့်) ──────────────
    public function getAll($limit, $offset)
    {
        $stmt = $this->pdo->prepare("
            SELECT
                Items.id,
                Items.cate_id,
                Items.event_id,
                Items.name       AS item_name,
                Categories.name  AS category_name,
                Event.event_name,
                Items.n_price,
                Items.e_price,
                Items.point_reward,
                Items.status
            FROM Items
            LEFT JOIN Categories ON Items.cate_id = Categories.id
            LEFT JOIN Event      ON Items.event_id = Event.id
            ORDER BY Items.id ASC
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':limit',  $limit,  PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ── Item အသစ် ထည့်သွင်းခြင်း (INSERT) ──────
    public function create($data)
    {
        $maxStmt = $this->pdo->query("SELECT MAX(id) FROM Items");
        $newId   = $maxStmt->fetchColumn() + 1;

        $stmt = $this->pdo->prepare("
            INSERT INTO Items (id, cate_id, event_id, name, n_price, e_price, point_reward, status)
            VALUES (:id, :cate_id, :event_id, :name, :n_price, :e_price, :point_reward, :status)
        ");
        $stmt->execute([
            ':id'           => $newId,
            ':cate_id'      => $data['cate_id'],
            ':event_id'     => $data['event_id'],
            ':name'         => $data['name'],
            ':n_price'      => $data['n_price'],
            ':e_price'      => $data['e_price'],
            ':point_reward' => $data['point_reward'],
            ':status'       => $data['status'],
        ]);
    }

    // ── Item ပြင်ဆင်ခြင်း (UPDATE) ───────────
    public function update($id, $data)
    {
        $stmt = $this->pdo->prepare("
            UPDATE Items SET
                name         = :name,
                cate_id      = :cate_id,
                event_id     = :event_id,
                n_price      = :n_price,
                e_price      = :e_price,
                point_reward = :point_reward,
                status       = :status
            WHERE id = :id
        ");
        $stmt->execute([
            ':id'           => $id,
            ':name'         => $data['name'],
            ':cate_id'      => $data['cate_id'],
            ':event_id'     => $data['event_id'],
            ':n_price'      => $data['n_price'],
            ':e_price'      => $data['e_price'],
            ':point_reward' => $data['point_reward'],
            ':status'       => $data['status'],
        ]);
    }

    // ── Item ပယ်ဖျက်ခြင်း (DELETE - Hard Delete) ──
    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM Items WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    // ── Active Items စာရင်း (Customer အတွက်) ──────
    public function getActiveItems()
    {
        $stmt = $this->pdo->query("
            SELECT
                Items.id,
                Items.name       AS item_name,
                Categories.name  AS category_name,
                Items.n_price,
                Items.e_price,
                Items.point_reward,
                Items.status
            FROM Items
            LEFT JOIN Categories ON Items.cate_id = Categories.id
            WHERE Items.status = 1
            ORDER BY Items.id ASC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

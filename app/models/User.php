<?php
// =============================================
// app/models/User.php — USER DATA LAYER
// =============================================

class User
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Email ဖြင့် User ရှာဖွေခြင်း
    public function findByEmail($email)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // User အသစ် Register လုပ်ခြင်း
    public function create($data)
    {
        // Get max ID and increment
        $maxStmt = $this->pdo->query("SELECT MAX(id) FROM users");
        $newId = (int)$maxStmt->fetchColumn() + 1;

        $stmt = $this->pdo->prepare("
            INSERT INTO users (id, name, email, password, role, status) 
            VALUES (:id, :name, :email, :password, :role, :status)
        ");
        
        return $stmt->execute([
            ':id'       => $newId,
            ':name'     => $data['name'],
            ':email'    => $data['email'],
            ':password' => password_hash($data['password'], PASSWORD_DEFAULT),
            ':role'     => $data['role'] ?? 'customer',
            ':status'   => $data['status'] ?? 'active'
        ]);
    }

    // User ID ဖြင့် ရှာဖွေခြင်း
    public function findById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // User Point ပေါင်းထည့်ခြင်း
    public function addPoints($userId, $points)
    {
        $stmt = $this->pdo->prepare("UPDATE users SET current_point = IFNULL(current_point, 0) + :points WHERE id = :userId");
        $stmt->execute([':points' => $points, ':userId' => $userId]);
        
        // Update session if it's the current user
        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $userId) {
            $user = $this->findById($userId);
            $_SESSION['user_point'] = $user['current_point'];
        }
    }
}

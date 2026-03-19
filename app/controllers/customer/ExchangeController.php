<?php
// =============================================
// app/controllers/customer/ExchangeController.php
// =============================================

class ExchangeController
{
    private $exchangeModel;
    private $userModel;
    private $notificationModel;

    public function __construct($pdo)
    {
        require_once BASE_PATH . '/app/models/Exchange.php';
        require_once BASE_PATH . '/app/models/User.php';
        require_once BASE_PATH . '/app/models/Notification.php';
        $this->exchangeModel = new Exchange($pdo);
        $this->userModel = new User($pdo);
        $this->notificationModel = new Notification($pdo);
    }

    public function index($pdo)
    {
        // Login မဝင်ရသေးရင် Login ကို ပို့မယ်
        if (!isset($_SESSION['user_id'])) {
            header("Location: " . APP_URL . "/login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleRedeem($pdo);
        }

        $items = $this->exchangeModel->getAll(100, 0); // Get all rewards
        require_once BASE_PATH . '/app/views/customer/exchange.view.php';
    }

    private function handleRedeem($pdo)
    {
        // JSON input ကို ဖတ်ယူခြင်း
        $input = json_decode(file_get_contents('php://input'), true);
        $itemId = $input['itemId'] ?? 0;
        $userId = $_SESSION['user_id'];

        // Item အား ရှာဖွေခြင်း
        $stmt = $pdo->prepare("SELECT * FROM Exchange WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $itemId]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$item) {
            echo json_encode(['success' => false, 'message' => 'Reward item not found.']);
            exit;
        }

        if ((int)$item['stock_qty'] <= 0) {
            echo json_encode(['success' => false, 'message' => 'Out of stock.']);
            exit;
        }

        // User point စစ်ဆေးခြင်း
        $user = $this->userModel->findById($userId);
        if ($user['current_point'] < $item['point_required']) {
            echo json_encode(['success' => false, 'message' => 'Insufficient points.']);
            exit;
        }

        try {
            $pdo->beginTransaction();

            // 1. User point နှုတ်ခြင်း
            $newPoints = $user['current_point'] - $item['point_required'];
            $stmt = $pdo->prepare("UPDATE users SET current_point = :points WHERE id = :id");
            $stmt->execute([':points' => $newPoints, ':id' => $userId]);

            // 2. Exchange stock နှုတ်ခြင်း
            $newStock = (int)$item['stock_qty'] - 1;
            $stmt = $pdo->prepare("UPDATE Exchange SET stock_qty = :stock WHERE id = :id");
            $stmt->execute([':stock' => $newStock, ':id' => $itemId]);

            $pdo->commit();

            // Session point update လုပ်ခြင်း
            $_SESSION['user_point'] = $newPoints;

            $this->notificationModel->create(
                $userId,
                'Reward Redeemed',
                "Successfully exchanged for '" . $item['name'] . "'. -" . $item['point_required'] . " Points.",
                'system'
            );

            echo json_encode(['success' => true, 'message' => 'Exchanged successfully!', 'newPoints' => $newPoints]);
            exit;

        } catch (Exception $e) {
            $pdo->rollBack();
            echo json_encode(['success' => false, 'message' => 'An error occurred.']);
            exit;
        }
    }
}

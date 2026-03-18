<?php
// =============================================
// app/controllers/customer/OrderController.php
// =============================================

class OrderController
{
    private $orderModel;
    private $userModel;

    public function __construct($pdo)
    {
        require_once BASE_PATH . '/app/models/Order.php';
        require_once BASE_PATH . '/app/models/User.php';
        $this->orderModel = new Order($pdo);
        $this->userModel = new User($pdo);
    }

    // AJAX Checkout ကိုင်တွယ်ခြင်း
    public function checkout($pdo)
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Please login first']);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $items = $data['items'] ?? [];
            $totalPrice = $data['totalPrice'] ?? 0;
            $totalPoints = $data['totalPoints'] ?? 0;

            if (empty($items)) {
                echo json_encode(['success' => false, 'message' => 'Cart is empty']);
                exit;
            }

            try {
                // 1. Order သိမ်းခြင်း
                $orderId = $this->orderModel->create($_SESSION['user_id'], $totalPrice, $items);

                // 2. User Point ပေါင်းထည့်ခြင်း
                if ($totalPoints > 0) {
                    $this->userModel->addPoints($_SESSION['user_id'], $totalPoints);
                }

                echo json_encode([
                    'success' => true, 
                    'message' => 'Order placed successfully!',
                    'new_points' => $_SESSION['user_point']
                ]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => 'Order failed: ' . $e->getMessage()]);
            }
            exit;
        }
    }
}

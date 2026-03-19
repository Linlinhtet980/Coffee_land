<?php
// =============================================
// app/controllers/customer/NotificationController.php
// =============================================

class NotificationController
{
    private $notificationModel;

    public function __construct($pdo)
    {
        require_once BASE_PATH . '/app/models/Notification.php';
        $this->notificationModel = new Notification($pdo);
    }

    public function getLatest()
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        $userId = $_SESSION['user_id'];
        $notifications = $this->notificationModel->getUserNotifications($userId);
        $unreadCount = $this->notificationModel->getUnreadCount($userId);

        echo json_encode([
            'success' => true,
            'notifications' => $notifications,
            'unreadCount' => $unreadCount
        ]);
        exit;
    }

    public function markRead()
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $userId = $_SESSION['user_id'];

            if (isset($data['id']) && $data['id'] === 'all') {
                $this->notificationModel->markAllAsRead($userId);
            } elseif (isset($data['id'])) {
                $this->notificationModel->markAsRead($data['id'], $userId);
            }

            echo json_encode(['success' => true]);
            exit;
        }
    }
}

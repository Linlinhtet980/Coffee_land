<?php
// =============================================
// app/controllers/DashboardController.php
// =============================================

class DashboardController
{
    public function index($pdo)
    {
        // Admin မဟုတ်လျှင် Login သို့ ပြန်ပို့မည်
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: " . APP_URL . "/login");
            exit;
        }

        // View ကို ပြသခြင်း
        require_once BASE_PATH . '/app/views/admin/dashboard.view.php';
    }
}
?>

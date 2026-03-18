<?php
// =============================================
// app/controllers/customer/HomeController.php
// =============================================

class HomeController
{
    public function index($pdo)
    {
        require_once BASE_PATH . '/app/models/Item.php';
        $itemModel = new Item($pdo);
        $items = $itemModel->getActiveItems();

        // View ကို ပြသခြင်း
        require_once BASE_PATH . '/app/views/customer/home.view.php';
    }
}
?>

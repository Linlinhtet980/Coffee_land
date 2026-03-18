<?php
// =============================================
// app/controllers/admin/ExchangeController.php
// =============================================

class ExchangeController
{
    private $model;

    public function __construct($pdo)
    {
        require_once BASE_PATH . '/app/models/Exchange.php';
        $this->model = new Exchange($pdo);
    }

    public function index($pdo)
    {
        // Admin မဟုတ်လျှင် Login သို့ ပြန်ပို့မည်
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: " . APP_URL . "/login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePost();
        }

        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        $items = $this->model->getAll($limit, $offset);
        $total_rows = $this->model->countAll();
        $total_pages = ceil($total_rows / $limit);

        require_once BASE_PATH . '/app/views/admin/exchange.view.php';
    }

    private function handlePost()
    {
        $action = $_POST['action'] ?? '';

        if ($action === 'delete') {
            $id = $_POST['item_id'] ?? 0;
            $this->model->delete($id);
            header("Location: " . APP_URL . "/admin/exchange?msg=deleted");
            exit;
        }

        if ($action === 'save') {
            $id = !empty($_POST['item_id']) ? $_POST['item_id'] : null;
            $data = [
                'name' => $_POST['name'] ?? '',
                'point_required' => $_POST['point_required'] ?? 0,
                'stock_qty' => $_POST['stock_qty'] ?? '0'
            ];

            if ($id) {
                $this->model->update($id, $data);
                header("Location: " . APP_URL . "/admin/exchange?msg=updated");
            } else {
                $this->model->create($data);
                header("Location: " . APP_URL . "/admin/exchange?msg=added");
            }
            exit;
        }
    }
}

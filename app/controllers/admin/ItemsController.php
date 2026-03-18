<?php
// =============================================
// app/controllers/ItemsController.php — LOGIC LAYER
// =============================================
// ဤ file သည် User ၏ Request ကို ခံယူပြီး
// Model ကို ခေါ်ကာ View ကို ပြသပေးသည်။
// =============================================

class ItemsController
{
    private $model;

    public function __construct($pdo)
    {
        require_once BASE_PATH . '/app/models/Item.php';
        $this->model = new Item($pdo);
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

        $categoriesList = $this->model->getCategories();
        $eventsList     = $this->model->getEvents();

        $page        = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit       = 5;
        $offset      = ($page - 1) * $limit;
        $items       = $this->model->getAll($limit, $offset);
        $total_rows  = $this->model->countAll();
        $total_pages = ceil($total_rows / $limit);

        require_once BASE_PATH . '/app/views/admin/items.view.php';
    }

    // ── POST actions (delete / save) ──────────
    private function handlePost()
    {
        $action = $_POST['action'] ?? '';

        if ($action === 'delete') {
            $id = $_POST['item_id'] ?? 0;
            try {
                $this->model->delete($id);
                header("Location: " . APP_URL . "/admin/items?msg=deleted");
                exit;
            } catch (PDOException $e) {
                die("Delete Failed: " . $e->getMessage());
            }
        }

        if ($action === 'save') {
            $id   = !empty($_POST['item_id']) ? $_POST['item_id'] : null;
            $data = [
                'name'         => $_POST['name']         ?? '',
                'cate_id'      => !empty($_POST['cate_id'])  ? $_POST['cate_id']  : null,
                'event_id'     => !empty($_POST['event_id']) ? $_POST['event_id'] : null,
                'n_price'      => $_POST['n_price']      ?? 0,
                'e_price'      => $_POST['e_price']      ?? 0,
                'point_reward' => $_POST['point_reward'] ?? 0,
                'status'       => $_POST['status']       ?? 0,
            ];

            try {
                if ($id) {
                    $this->model->update($id, $data);
                    header("Location: " . APP_URL . "/admin/items?msg=updated");
                } else {
                    $this->model->create($data);
                    header("Location: " . APP_URL . "/admin/items?msg=added");
                }
                exit;
            } catch (PDOException $e) {
                die("Save Failed: " . $e->getMessage());
            }
        }
    }
}
?>

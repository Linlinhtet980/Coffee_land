<?php
// =============================================
// app/controllers/AuthController.php — AUTH LOGIC
// =============================================

class AuthController
{
    private $userModel;

    public function __construct($pdo)
    {
        require_once BASE_PATH . '/app/models/User.php';
        $this->userModel = new User($pdo);
    }

    // Login စာမျက်နှာ ပြခြင်းနှင့် Login ဝင်ခြင်း
    public function login($pdo)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $user = $this->userModel->findByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                // Session သိမ်းခြင်း
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['user_point'] = $user['current_point'] ?? 0;
                $_SESSION['user_image'] = $user['profile_image'] ?? null;

                // Admin ဆိုရင် Admin Dashboard သို့ပို့မည်
                if ($user['role'] === 'admin') {
                    header("Location: " . APP_URL . "/admin/dashboard");
                } else {
                    header("Location: " . APP_URL . "/home");
                }
                exit;
            } else {
                $error = "အီးမေးလ် (သို့) စကားဝှက် မှားယွင်းနေပါသည်။";
            }
        }

        require_once BASE_PATH . '/app/views/auth/login.view.php';
    }

    // Register စာမျက်နှာ ပြခြင်းနှင့် Register လုပ်ခြင်း
    public function register($pdo)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            // Validation အခြေခံ
            if ($password !== $confirm_password) {
                $error = "စကားဝှက်များ တူညီမှုမရှိပါ။";
            } elseif ($this->userModel->findByEmail($email)) {
                $error = "ဤအီးမေးလ်ကို အသုံးပြုပြီးသား ဖြစ်နေပါသည်။";
            } else {
                $data = [
                    'name' => $name,
                    'email' => $email,
                    'password' => $password
                ];

                if ($this->userModel->create($data)) {
                    header("Location: " . APP_URL . "/login?msg=registered");
                    exit;
                } else {
                    $error = "စာရင်းသွင်းရာတွင် အဆင်မပြေဖြစ်သွားပါသည်။";
                }
            }
        }

        require_once BASE_PATH . '/app/views/auth/register.view.php';
    }

    // Logout ထွက်ခြင်း
    public function logout()
    {
        session_destroy();
        header("Location: " . APP_URL . "/login");
        exit;
    }
}

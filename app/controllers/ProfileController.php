<?php
// =============================================
// app/controllers/ProfileController.php
// =============================================

class ProfileController
{
    private $userModel;

    public function __construct($pdo)
    {
        require_once BASE_PATH . '/app/models/User.php';
        $this->userModel = new User($pdo);
    }

    public function update()
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            if (empty($name) || empty($email)) {
                echo json_encode(['success' => false, 'message' => 'Name and Email are required.']);
                exit;
            }

            if (!empty($password) && $password !== $confirm_password) {
                echo json_encode(['success' => false, 'message' => 'Passwords do not match.']);
                exit;
            }

            // Check if email belongs to someone else
            $existingUser = $this->userModel->findByEmail($email);
            if ($existingUser && $existingUser['id'] != $userId) {
                echo json_encode(['success' => false, 'message' => 'Email is already taken.']);
                exit;
            }

            // Handle file upload
            $profileImage = null;
            if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = BASE_PATH . '/public/assets/images/profiles/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $fileExt = strtolower(pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                if (in_array($fileExt, $allowed)) {
                    $fileName = 'user_' . $userId . '_' . time() . '.' . $fileExt;
                    $uploadPath = $uploadDir . $fileName;
                    if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $uploadPath)) {
                        $profileImage = $fileName;
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Failed to save uploaded image.']);
                        exit;
                    }
                } else {
                    echo json_encode(['success' => false, 'message' => 'Only JPG, JPEG, PNG, and GIF allowed.']);
                    exit;
                }
            }

            $data = [
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'profile_image' => $profileImage
            ];

            if ($this->userModel->updateProfile($userId, $data)) {
                echo json_encode(['success' => true, 'message' => 'Profile updated successfully!', 'newName' => $data['name']]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update profile.']);
            }
            exit;
        }
    }
}
?>

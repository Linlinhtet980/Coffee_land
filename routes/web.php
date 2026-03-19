<?php
// =============================================
// routes/web.php — URL ROUTING
// =============================================

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Project folder name ကို path ထဲမှ ဖယ်ထုတ်ခြင်း (XAMPP အတွက်)
$base_folder = '/coffee_land/public';
$path = str_replace($base_folder, '', $uri);
$path = trim($path, '/');
$path = preg_replace('/^index\.php\/?/', '', $path); // index.php ကို လမ်းကြောင်းထဲမှ ဖယ်ထုတ်သည်
$path = str_replace('.php', '', $path);
$path = strtolower($path);

// Routing Logic
if ($path === '' || $path === 'home') {
    require_once BASE_PATH . '/app/controllers/customer/HomeController.php';
    $controller = new HomeController();
    $controller->index($pdo);
} 
elseif ($path === 'admin/dashboard') {
    require_once BASE_PATH . '/app/controllers/admin/DashboardController.php';
    $controller = new DashboardController();
    $controller->index($pdo);
} 
elseif ($path === 'admin/items') {
    require_once BASE_PATH . '/app/controllers/admin/ItemsController.php';
    $controller = new ItemsController($pdo);
    $controller->index($pdo);
} 
elseif ($path === 'admin/exchange') {
    require_once BASE_PATH . '/app/controllers/admin/ExchangeController.php';
    $controller = new ExchangeController($pdo);
    $controller->index($pdo);
} 
elseif ($path === 'login') {
    require_once BASE_PATH . '/app/controllers/AuthController.php';
    $controller = new AuthController($pdo);
    $controller->login($pdo);
} 
elseif ($path === 'register') {
    require_once BASE_PATH . '/app/controllers/AuthController.php';
    $controller = new AuthController($pdo);
    $controller->register($pdo);
} 
elseif ($path === 'logout') {
    require_once BASE_PATH . '/app/controllers/AuthController.php';
    $controller = new AuthController($pdo);
    $controller->logout();
} 
elseif ($path === 'checkout') {
    require_once BASE_PATH . '/app/controllers/customer/OrderController.php';
    $controller = new OrderController($pdo);
    $controller->checkout($pdo);
} 
elseif ($path === 'exchange') {
    require_once BASE_PATH . '/app/controllers/customer/ExchangeController.php';
    $controller = new ExchangeController($pdo);
    $controller->index($pdo);
} 
elseif ($path === 'notifications/get') {
    require_once BASE_PATH . '/app/controllers/customer/NotificationController.php';
    $controller = new NotificationController($pdo);
    $controller->getLatest();
} 
elseif ($path === 'notifications/read') {
    require_once BASE_PATH . '/app/controllers/customer/NotificationController.php';
    $controller = new NotificationController($pdo);
    $controller->markRead();
} 
else {
    http_response_code(404);
    echo "<h1>404 - Page Not Found</h1>";
    echo "<p>Path: $path</p>";
}
?>

<?php
// =============================================
// public/index.php — MAIN ENTRY POINT
// =============================================

session_start();

// Debugging (Error ပြသရန်)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Base path သတ်မှတ်ခြင်း
define('BASE_PATH', dirname(__DIR__));

// Configuration ဖိုင်ကို ခေါ်ယူခြင်း
require_once BASE_PATH . '/config/database.php';

// Asset များအတွက် URL သတ်မှတ်ခြင်း (Browser အတွက်)
// Routing မတိုင်ခင် သတ်မှတ်ထားရန် လိုအပ်သည်
$base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']);
define('ASSET_URL', rtrim($base_url, '/') . '/assets');
define('APP_URL', rtrim($base_url, '/') . '/index.php');

// Routing ဖိုင်ကို ခေါ်ယူခြင်း
require_once BASE_PATH . '/routes/web.php';
?>

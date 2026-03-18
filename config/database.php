<?php
// database.php (Database ချိတ်ဆက်ရန် အပိုင်း)
// PDO နည်းလမ်းကို အသုံးပြုပြီး MySQL ရှိ "coffee_land" database သို့ ချိတ်ဆက်ပါမည်
$host = 'localhost';
$dbname = 'coffee_land';
$username = 'root'; // XAMPP ၏ မူလ User အမည်
$password = ''; // XAMPP ၏ မူလ Password (အလွတ်)

try {
    // Database ချိတ်ဆက်ခြင်း စတင်သည့်နေရာ (Connection String)
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // ချိတ်ဆက်မှု အခက်အခဲ (သို့) Error တစ်ခုခုရှိပါက ချက်ချင်းသိနိုင်ရန် Exception အနေဖြင့် ပြသရန် သတ်မှတ်ခြင်း
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    // အကယ်၍ Database မပွင့်နေလျှင် သို့မဟုတ် Password မှားနေလျှင် အောက်ပါစာသားကို ပြသမည်ဖြစ်သည်
    die("Database connection failed: " . $e->getMessage());
}
?>

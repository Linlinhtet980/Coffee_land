<?php
require_once 'config/database.php';
echo "--- Orders Table ---\n";
$stmt = $pdo->query('DESCRIBE orders');
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));

echo "\n--- Order Items Table ---\n";
$stmt = $pdo->query('DESCRIBE order_items');
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));

<?php
require_once 'config/database.php';
$stmt = $pdo->query('DESCRIBE users');
$fields = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach($fields as $field) {
    echo $field['Field'] . " - " . $field['Type'] . "\n";
}

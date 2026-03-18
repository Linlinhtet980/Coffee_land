<?php
require_once 'config/database.php';
$stmt = $pdo->query('DESCRIBE orders');
foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $f) {
    echo $f['Field'] . " - " . $f['Type'] . "\n";
}

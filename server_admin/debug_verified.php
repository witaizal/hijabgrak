<?php
require 'db.php';
try {
    $stmt = $pdo->query('SELECT id, username, email, verified, created_at FROM users ORDER BY created_at DESC LIMIT 5');
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo '<pre>';
    print_r($users);
    echo '</pre>';
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
?>
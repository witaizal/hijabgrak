<?php
// get_users.php - Mengembalikan data pengguna dari kedua database dalam format JSON

// Koneksi ke login_db
$host_login = 'localhost';
$dbname_login = 'login_db';
$username_login = 'root';
$password_login = '';

try {
    $pdo_login = new PDO("mysql:host=$host_login;dbname=$dbname_login;charset=utf8", $username_login, $password_login);
    $pdo_login->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Koneksi login_db gagal: ' . $e->getMessage()]);
    exit;
}

// Koneksi ke hijabgrak_db
$host_hijab = 'localhost';
$dbname_hijab = 'hijabgrak_db';
$username_hijab = 'root';
$password_hijab = '';

try {
    $pdo_hijab = new PDO("mysql:host=$host_hijab;dbname=$dbname_hijab;charset=utf8", $username_hijab, $password_hijab);
    $pdo_hijab->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Koneksi hijabgrak_db gagal: ' . $e->getMessage()]);
    exit;
}

$all_users = [];

try {
    // Ambil dari login_db
    $stmt_login = $pdo_login->query('SELECT id, username, email, verified, created_at FROM users ORDER BY created_at DESC');
    $users_login = $stmt_login->fetchAll(PDO::FETCH_ASSOC);
    foreach ($users_login as $user) {
        $user['database'] = 'login_db';
        $all_users[] = $user;
    }
} catch (PDOException $e) {
    $all_users[] = ['error' => 'Error login_db: ' . $e->getMessage()];
}

try {
    // Ambil dari hijabgrak_db
    $stmt_hijab = $pdo_hijab->query('SELECT id, username, email, verified, created_at FROM users ORDER BY created_at DESC');
    $users_hijab = $stmt_hijab->fetchAll(PDO::FETCH_ASSOC);
    foreach ($users_hijab as $user) {
        $user['database'] = 'hijabgrak_db';
        $all_users[] = $user;
    }
} catch (PDOException $e) {
    $all_users[] = ['error' => 'Error hijabgrak_db: ' . $e->getMessage()];
}

// Urutkan
usort($all_users, function($a, $b) {
    return strtotime($b['created_at'] ?? '1970-01-01') <=> strtotime($a['created_at'] ?? '1970-01-01');
});

// Kembalikan sebagai JSON
header('Content-Type: application/json');
echo json_encode($all_users);
?>
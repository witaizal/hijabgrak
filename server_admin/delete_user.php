<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Ambil parameter dari URL
$user_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$db_source = isset($_GET['db']) ? $_GET['db'] : '';

// Validasi parameter
if ($user_id <= 0 || !in_array($db_source, ['login_db', 'hijabgrak_db'])) {
    die("Parameter tidak valid. ID pengguna atau database asal tidak ditemukan.");
}

// Konfigurasi koneksi berdasarkan database asal
if ($db_source === 'login_db') {
    $host = 'localhost';
    $dbname = 'login_db';
    $username = 'root';
    $password = '';
} elseif ($db_source === 'hijabgrak_db') {
    // Gunakan koneksi dari db.php (asumsikan ini untuk hijabgrak_db)
    require 'db.php'; // Pastikan file db.php ada dan mengatur $pdo untuk hijabgrak_db
    $pdo = $pdo; // Asumsikan $pdo sudah didefinisikan di db.php
} else {
    die("Database asal tidak dikenali.");
}

// Jika bukan login_db, gunakan $pdo dari db.php
if ($db_source === 'login_db') {
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Koneksi ke $db_source gagal: " . $e->getMessage());
    }
}

try {
    // Query hapus berdasarkan ID
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    
    if ($stmt->rowCount() > 0) {
        $message = "Pengguna dengan ID $user_id dari $db_source berhasil dihapus.";
    } else {
        $message = "Pengguna dengan ID $user_id dari $db_source tidak ditemukan atau sudah dihapus.";
    }
} catch (PDOException $e) {
    $message = "Error menghapus pengguna: " . $e->getMessage();
}

// Redirect kembali ke admin_users.php dengan pesan
header("Location: admin_users.php?message=" . urlencode($message));
exit;
?>
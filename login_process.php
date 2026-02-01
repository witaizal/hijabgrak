<?php
session_start();
require 'db.php';  // Pastikan db.php ada di folder yang sama
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Validasi input
    if (empty($username) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'Username dan password harus diisi.']);
        exit;
    }

    try {
        // Query untuk mencari user berdasarkan username
        $stmt = $pdo->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Log untuk debug: apakah user ditemukan
            error_log("User ditemukan: " . $user['username'] . ", Role: " . $user['role']);
            
            if (password_verify($password, $user['password'])) {
                // Login sukses
                if ($user['role'] === 'admin') {
                    $_SESSION['admin_id'] = $user['id'];
                    error_log("Admin login sukses: " . $user['username']);
                    echo json_encode(['status' => 'success', 'message' => 'Login admin berhasil! Mengarahkan ke dashboard...', 'redirect' => 'server_admin/admin_dashboard.php']);
                } else {
                    $_SESSION['user_id'] = $user['id'];
                    error_log("User login sukses: " . $user['username']);
                    echo json_encode(['status' => 'success', 'message' => 'Login berhasil! Mengarahkan ke halaman utama...', 'redirect' => 'index.php']);
                }
            } else {
                error_log("Password salah untuk: " . $username);
                echo json_encode(['status' => 'error', 'message' => 'Username atau password salah.']);
            }
        } else {
            error_log("User tidak ditemukan: " . $username);
            echo json_encode(['status' => 'error', 'message' => 'Username atau password salah.']);
        }
    } catch (PDOException $e) {
        error_log('Database error: ' . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan database.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Metode tidak diizinkan.']);
}
?>
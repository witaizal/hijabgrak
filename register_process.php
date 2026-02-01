<?php
session_start();

// Koneksi ke database (gunakan hijabgrak_db berdasarkan asumsi; sesuaikan jika perlu)
require 'db.php'; // Pastikan file db.php ada dan $pdo terhubung ke hijabgrak_db

header('Content-Type: application/json'); // Response sebagai JSON untuk AJAX

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validasi dasar
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        echo json_encode(['status' => 'error', 'message' => 'Semua field harus diisi.']);
        exit;
    }

    if ($password !== $confirm_password) {
        echo json_encode(['status' => 'error', 'message' => 'Kata sandi tidak cocok.']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Email tidak valid.']);
        exit;
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Cek apakah username atau email sudah ada
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->fetch()) {
            echo json_encode(['status' => 'error', 'message' => 'Username atau email sudah terdaftar.']);
            exit;
        }

        // Insert user baru dengan verified = 1 (langsung terverifikasi)
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, verified, created_at) VALUES (?, ?, ?, 1, NOW())");
        $stmt->execute([$username, $email, $hashed_password]);

        // Ambil ID user yang baru dibuat
        $user_id = $pdo->lastInsertId();

        // Auto-login: Set session
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
        // Tambahkan session lain jika perlu, misalnya $_SESSION['verified'] = true;

        echo json_encode(['status' => 'success', 'message' => 'Registrasi berhasil! Anda langsung terverifikasi dan login.']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Metode tidak diizinkan.']);
}
?>
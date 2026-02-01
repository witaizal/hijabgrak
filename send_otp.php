<?php
// send_otp.php

// Header untuk JSON response
header('Content-Type: application/json');

// Koneksi ke database (sesuaikan dengan konfigurasi Anda)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Koneksi database gagal.']);
    exit();
}

// Ambil data dari request JSON
$input = json_decode(file_get_contents('php://input'), true);
$email = $input['email'] ?? '';

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'message' => 'Email tidak valid.']);
    exit();
}

// Generate OTP 6 digit acak
$otp = rand(100000, 999999);

// Simpan OTP ke database (tabel otp_codes: id, email, otp, expiry)
$expiry = date('Y-m-d H:i:s', strtotime('+10 minutes')); // OTP berlaku 10 menit
$sql = "INSERT INTO otp_codes (email, otp, expiry) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE otp=?, expiry=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $email, $otp, $expiry, $otp, $expiry);

if (!$stmt->execute()) {
    echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan OTP.']);
    exit();
}

// Kirim email dengan OTP
$subject = "Kode OTP untuk Registrasi";
$message = "Kode OTP Anda adalah: $otp\n\nKode ini berlaku selama 10 menit.";
$headers = "From: noreply@yourwebsite.com\r\n";

if (mail($email, $subject, $message, $headers)) {
    echo json_encode(['status' => 'success', 'message' => 'OTP telah dikirim ke email Anda.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal mengirim email.']);
}

// Tutup koneksi
$stmt->close();
$conn->close();
?>
<?php
// Konfigurasi koneksi database
$host = 'localhost';           // Host database (jangan ubah jika lokal)
$dbname = 'hijabgrak_db';      // Nama database (ubah jika berbeda, misalnya 'login_db')
$username = 'root';            // Username MySQL (default XAMPP)
$password = '';                // Password MySQL (kosong jika default XAMPP)

try {
    // Membuat koneksi PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    // Jika berhasil, koneksi tersedia untuk digunakan di file lain
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}
?>
<?php
// Konfigurasi koneksi database
$host = 'localhost';           // Host database (jangan ubah jika lokal)
$dbname = 'login_db';      // Nama database (ubah jika berbeda, misalnya 'login_db')
$username = 'root';            // Username MySQL (default XAMPP)
$password = '';                // Password MySQL (kosong jika default XAMPP)

try {
    // Membuat koneksi PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    // Jika berhasil, koneksi tersedia untuk digunakan di file lain
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}
?>
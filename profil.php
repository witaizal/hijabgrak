<?php
// Mulai session
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Konfigurasi database (sama seperti di login_process.php)
$host = 'localhost'; // Sesuaikan jika berbeda
$dbname = 'login_db'; // Nama database
$username_db = 'root'; // Username MySQL
$password_db = ''; // Password MySQL

// Buat koneksi ke database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username_db, $password_db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}

// Ambil data profil dari database berdasarkan user_id
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT username, email, nama_lengkap, alamat FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("Data pengguna tidak ditemukan.");
}

// Ambil data dengan htmlspecialchars untuk keamanan
$username = htmlspecialchars($user['username']);
$email = htmlspecialchars($user['email'] ?? 'Tidak tersedia');
$namaLengkap = htmlspecialchars($user['nama_lengkap'] ?? 'Tidak tersedia');
$alamat = htmlspecialchars($user['alamat'] ?? 'Tidak tersedia');

// Tampilkan pesan sukses jika ada (dari edit_profil.php)
if (isset($_SESSION['success_message'])) {
    $successMessage = $_SESSION['success_message'];
    unset($_SESSION['success_message']); // Hapus setelah ditampilkan
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - HIJABGRAK</title>
    <link rel="stylesheet" href="index.css"> <!-- Gunakan CSS yang sama -->
</head>
<body>
    <!-- HEADER (sama seperti index.php) -->
    <header class="navbar">
        <div class="logo">
            <img src="gambar/x.png" width="40px" height="25px" alt="Logo HIJABGRAK">
            HIJABGRAK
        </div>
        
        <nav>
            <ul class="navbar">
                <li>Produk
                    <ul class="submenu">
                        <li><a href="produk.php">Oursis</a></li>
                        <li><a href="produk2.php">Kiyowo</a></li>
                        <li><a href="#">Aqry Hijab</a></li>
                        <li><a href="#">HIJABGRAK</a></li>
                    </ul>
                </li>
                <li>Tentang Kami
                    <ul class="submenu">
                        <li><a href="sejarah.php">Sejarah Hijab</a></li>
                        <li><a href="tim.php">Tim Kami</a></li>
                        <li><a href="https://www.google.com/maps/place/HIJABGRAK+HQ/@-6.8935581,107.8042487,17z/data=!3m1!4b1!4m6!3m5!1s0x2e68db851642a541:0x900b1feeaf50c0dd!8m2!3d-6.8935581!4d107.8042487!16s%2Fg%2F11fltvb8yn?entry=ttu&g_ep=EgoyMDI2MDEyMS4wIKXMDSoKLDEwMDc5MjA3MUgBUAM%3D">Lokasi workshop</a></li>
                        <li><a href="https://docs.google.com/forms/d/e/1FAIpQLSdouHOBTGUK8jF7uXMZDFZ_vjl1lDab8pzD3xNTvSnzaAYyAg/viewform?pli=1">Info karir & magang</a></li>
                        <li><a href="https://docs.google.com/forms/d/e/1FAIpQLSevxR6ETergcOL6W4vxqDTepNGfXddoTGfG4THIfYJzYMmU8Q/viewform?usp=pp_url">Sponsorship</a></li>
                    </ul>
                </li>
                <li>Media Sosial
                    <ul class="submenu">
                        <li><a href="https://www.instagram.com/hijabgrak/">instragram</a></li>
                        <li><a href="https://www.facebook.com/hijabgrak/?locale=id_ID">Facebook</a></li>
                        <li><a href="https://www.tiktok.com/@hijabgrak.universe">Tiktok</a></li>
                    </ul>
                </li>
                <li>Kontak
                    <ul class="submenu">
                        <li><a href="https://api.whatsapp.com/send/?phone=6281284160931&text&type=phone_number&app_absent=0">Whatsapp offline store</a></li>
                        <li><a href="https://wa.me/6285780819680">Whatsapp</a></li>
                    </ul>
                </li>
                <li><a href="https://shopee.co.id/hijabgrak" class="sale">Toko</a></li>
                <li>Kunjungan
                    <ul class="submenu">
                        <li><a href="https://docs.google.com/forms/d/e/1FAIpQLSfQ0wqJyA9jT_-vZQlmfhzNzQOzid814s-YTTm4TBAefBNjkg/viewform?usp=send_form">Kunjungan sekolah/Komunitas</a></li>
                        <li><a href="https://docs.google.com/forms/d/e/1FAIpQLSccAsEjh1jWnvgxdaw-N8NjKtt2bUZhkgVvRBiYDqoQxRv4Aw/viewform?usp=send_form">Kunjungan pelajar/penelitian</a></li>
                    </ul>
                </li>
                <li>Majalah</li>
            </ul>
        </nav>

        <!-- NAV-RIGHT: Dinamis berdasarkan status login -->
        <?php
        if (isset($_SESSION['username'])) {
            echo '<div class="nav-right">';
            echo '<input type="text" placeholder="Cari produk atau brand">';
            echo '<a href="profil.php"><button class="login-btn">' . $username . '</button></a>';
            echo '<a href="logout.php"><button class="logout-btn">Logout</button></a>';
            echo '</div>';
        } else {
            echo '<div class="nav-right">';
            echo '<input type="text" placeholder="Cari produk atau brand">';
            echo '<a href="login.php"><button class="login-btn">Login / Register</button></a>';
            echo '</div>';
        }
        ?>
    </header>

    <!-- KONTEN PROFIL -->
    <main class="profil-container">
        <div class="profil-header">
            <h1>Profil Pengguna</h1>
            <p>Selamat datang, <?php echo $username; ?>!</p>
        </div>
        
        <?php if (isset($successMessage)): ?>
            <div class="success-message"><?php echo $successMessage; ?></div>
        <?php endif; ?>
        
        <div class="profil-info">
            <div class="profil-item">
                <label>Nama Pengguna:</label>
                <span><?php echo $username; ?></span>
            </div>
            <div class="profil-item">
                <label>Nama Lengkap:</label>
                <span><?php echo $namaLengkap; ?></span>
            </div>
            <div class="profil-item">
                <label>Email:</label>
                <span><?php echo $email; ?></span>
            </div>
            <div class="profil-item">
                <label>Alamat:</label>
                <span><?php echo $alamat; ?></span>
            </div>
            <!-- Tambahkan field lain jika diperlukan, misalnya tanggal lahir -->
        </div>
        
        <div class="profil-actions">
            <a href="edit_profil.php"><button class="edit-btn">Edit Profil</button></a> <!-- Link ke halaman edit -->
            <a href="logout.php"><button class="logout-btn">Logout</button></a>
        </div>
    </main>

    <!-- FOOTER (sama seperti index.php) -->
    <footer>
        <p>&copy; 2026 HIJABGRAK. All Rights Reserved.</p>
    </footer>
</body>
</html>
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

// Ambil data profil saat ini dari database
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT username, email, nama_lengkap, alamat FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("Data pengguna tidak ditemukan.");
}

// Inisialisasi variabel untuk form
$namaLengkap = htmlspecialchars($user['nama_lengkap'] ?? '');
$email = htmlspecialchars($user['email'] ?? '');
$alamat = htmlspecialchars($user['alamat'] ?? '');
$username = htmlspecialchars($user['username']); // Username tidak bisa diedit

// Proses form jika dikirim via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $namaLengkapBaru = trim($_POST['nama_lengkap']);
    $emailBaru = trim($_POST['email']);
    $alamatBaru = trim($_POST['alamat']);

    // Validasi input
    $errors = [];
    if (empty($namaLengkapBaru)) {
        $errors[] = "Nama lengkap tidak boleh kosong.";
    }
    if (empty($emailBaru) || !filter_var($emailBaru, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email tidak valid.";
    }
    if (empty($alamatBaru)) {
        $errors[] = "Alamat tidak boleh kosong.";
    }

    // Cek apakah email sudah digunakan oleh user lain
    if (!empty($emailBaru) && $emailBaru !== $user['email']) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->execute([$emailBaru, $user_id]);
        if ($stmt->fetch()) {
            $errors[] = "Email sudah digunakan oleh pengguna lain.";
        }
    }

    if (empty($errors)) {
        // Update database
        $stmt = $pdo->prepare("UPDATE users SET nama_lengkap = ?, email = ?, alamat = ? WHERE id = ?");
        $stmt->execute([$namaLengkapBaru, $emailBaru, $alamatBaru, $user_id]);

        // Redirect ke profil dengan pesan sukses
        $_SESSION['success_message'] = "Profil berhasil diperbarui!";
        header("Location: profil.php");
        exit;
    } else {
        // Tampilkan error
        $errorMessage = implode("<br>", $errors);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil - HIJABGRAK</title>
    <link rel="stylesheet" href="index.css"> <!-- Gunakan CSS yang sama -->
    <style>
        /* Style tambahan untuk halaman edit profil */
        .edit-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .edit-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .edit-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .form-group {
            display: flex;
            flex-direction: column;
        }
        .form-group label {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .form-group input {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }
        .edit-actions {
            text-align: center;
            margin-top: 20px;
        }
        .edit-actions button {
            padding: 10px 20px;
            margin: 0 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .save-btn {
            background-color: #28a745;
            color: white;
        }
        .cancel-btn {
            background-color: #6c757d;
            color: white;
        }
    </style>
</head>
<body>
    <!-- HEADER (sama seperti profil.php) -->
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

    <!-- KONTEN EDIT PROFIL -->
    <main class="edit-container">
        <div class="edit-header">
            <h1>Edit Profil</h1>
            <p>Perbarui informasi Anda di bawah ini.</p>
        </div>
        
        <?php if (isset($errorMessage)): ?>
            <div class="error-message"><?php echo $errorMessage; ?></div>
        <?php endif; ?>
        
        <form class="edit-form" action="edit_profil.php" method="POST">
            <div class="form-group">
                <label for="nama_lengkap">Nama Lengkap:</label>
                <input type="text" id="nama_lengkap" name="nama_lengkap" value="<?php echo $namaLengkap; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>
            </div>
            <div class="form-group">
                <label for="alamat">Alamat:</label>
                <input type="text" id="alamat" name="alamat" value="<?php echo $alamat; ?>" required>
            </div>
            <!-- Username tidak bisa diedit -->
            <div class="form-group">
                <label>Nama Pengguna:</label>
                <input type="text" value="<?php echo $username; ?>" disabled>
            </div>
            
            <div class="edit-actions">
                <button type="submit" class="save-btn">Simpan Perubahan</button>
                <a href="profil.php"><button type="button" class="cancel-btn">Batal</button></a>
            </div>
        </form>
    </main>

    <!-- FOOTER (sama seperti profil.php) -->
    <footer>
        <p>&copy; 2026 HIJABGRAK. All Rights Reserved.</p>
    </footer>
</body>
</html>
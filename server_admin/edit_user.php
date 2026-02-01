<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');  // Redirect ke login utama jika bukan admin
    exit;
}

// Ambil parameter dari URL
$user_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$db_source = isset($_GET['db']) ? $_GET['db'] : '';

// Validasi parameter
if ($user_id <= 0 || !in_array($db_source, ['login_db', 'hijabgrak_db'])) {
    die("Parameter tidak valid. ID pengguna atau database asal tidak ditemukan.");
}

// Koneksi ke database berdasarkan db_source
if ($db_source === 'login_db') {
    $host = 'localhost';
    $dbname = 'login_db';
    $username = 'root';
    $password = '';
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Koneksi ke $db_source gagal: " . $e->getMessage());
    }
} elseif ($db_source === 'hijabgrak_db') {
    require 'db.php'; // Asumsikan $pdo sudah didefinisikan di db.php
} else {
    die("Database asal tidak dikenali.");
}

// Ambil data pengguna untuk ditampilkan di form
$user_data = null;
try {
    $stmt = $pdo->prepare("SELECT id, username, email, verified FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$user_data) {
        die("Pengguna dengan ID $user_id tidak ditemukan di $db_source.");
    }
} catch (PDOException $e) {
    die("Error mengambil data pengguna: " . $e->getMessage());
}

// Proses update jika form disubmit
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_username = trim($_POST['username'] ?? '');
    $new_email = trim($_POST['email'] ?? '');
    $new_verified = isset($_POST['verified']) ? 1 : 0;

    // Validasi input
    if (empty($new_username) || empty($new_email)) {
        $message = 'Username dan email tidak boleh kosong.';
    } elseif (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Email tidak valid.';
    } else {
        // Cek apakah username atau email sudah digunakan oleh user lain
        try {
            $stmt_check = $pdo->prepare("SELECT id FROM users WHERE (username = ? OR email = ?) AND id != ?");
            $stmt_check->execute([$new_username, $new_email, $user_id]);
            if ($stmt_check->fetch()) {
                $message = 'Username atau email sudah digunakan oleh pengguna lain.';
            } else {
                // Update data
                $stmt_update = $pdo->prepare("UPDATE users SET username = ?, email = ?, verified = ? WHERE id = ?");
                $stmt_update->execute([$new_username, $new_email, $new_verified, $user_id]);
                $message = 'Pengguna berhasil diupdate.';
                // Redirect setelah sukses
                header("Location: admin_users.php?message=" . urlencode($message));
                exit;
            }
        } catch (PDOException $e) {
            $message = 'Error update: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Pengguna - HIJABGRAK Admin</title>
    <link rel="stylesheet" href="admin_users.css">
</head>
<body>
    <header class="navbar">
        <div class="logo">HIJABGRAK Admin</div>
        <nav>
            <ul class="navbar">
                <li><a href="admin_dashboard.php">Dashboard</a></li>
                <li><a href="admin_users.php">Kelola Pengguna</a></li>
                <li><a href="admin_logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <section class="users-section">
        <h2>Edit Pengguna</h2>
        <p>Edit data pengguna dari database: <strong><?php echo htmlspecialchars($db_source); ?></strong></p>

        <!-- Tabel data pengguna (untuk tampilan modern) -->
        <table class="user-data-table">
            <thead>
                <tr>
                    <th>Field</th>
                    <th>Nilai Saat Ini</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>ID</td>
                    <td><?php echo htmlspecialchars($user_data['id']); ?></td>
                </tr>
                <tr>
                    <td>Username</td>
                    <td><?php echo htmlspecialchars($user_data['username']); ?></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><?php echo htmlspecialchars($user_data['email']); ?></td>
                </tr>
                <tr>
                    <td>Status Verifikasi</td>
                    <td><?php echo $user_data['verified'] ? 'Terverifikasi' : 'Belum'; ?></td>
                </tr>
            </tbody>
        </table>

        <?php if ($message): ?>
            <p class="message <?php echo strpos($message, 'berhasil') !== false ? 'success' : 'error'; ?>"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user_data['username']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user_data['email']); ?>" required>
            </div>
            <div class="form-group checkbox-group">
                <label for="verified">
                    <input type="checkbox" id="verified" name="verified" <?php echo $user_data['verified'] ? 'checked' : ''; ?>> Terverifikasi
                </label>
            </div>
            <button type="submit">Update Pengguna</button>
        </form>

        <a href="admin_users.php" class="back-link">Kembali ke Kelola Pengguna</a>
    </section>
</body>
</html>
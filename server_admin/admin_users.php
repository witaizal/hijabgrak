<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Koneksi ke login_db
$host_login = 'localhost';
$dbname_login = 'login_db';
$username_login = 'root';
$password_login = '';

try {
    $pdo_login = new PDO("mysql:host=$host_login;dbname=$dbname_login;charset=utf8", $username_login, $password_login);
    $pdo_login->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi ke login_db gagal: " . $e->getMessage());
}

require 'db.php'; // Koneksi ke hijabgrak_db

// Fungsi untuk load tabel (dimodifikasi untuk gabung data dari kedua DB)
function loadUsersTable($pdo_login, $pdo_hijab) {
    $all_users = [];
    try {
        // Ambil data dari login_db
        $stmt_login = $pdo_login->query('SELECT id, username, email, verified, created_at FROM users ORDER BY created_at DESC');
        $users_login = $stmt_login->fetchAll(PDO::FETCH_ASSOC);
        foreach ($users_login as $user) {
            $user['database'] = 'login_db'; // Tambah kolom asal
            $all_users[] = $user;
        }
    } catch (PDOException $e) {
        $all_users[] = ['error' => 'Error login_db: ' . $e->getMessage()];
    }

    try {
        // Ambil data dari hijabgrak_db
        $stmt_hijab = $pdo_hijab->query('SELECT id, username, email, verified, created_at FROM users ORDER BY created_at DESC');
        $users_hijab = $stmt_hijab->fetchAll(PDO::FETCH_ASSOC);
        foreach ($users_hijab as $user) {
            $user['database'] = 'hijabgrak_db'; // Tambah kolom asal
            $all_users[] = $user;
        }
    } catch (PDOException $e) {
        $all_users[] = ['error' => 'Error hijabgrak_db: ' . $e->getMessage()];
    }

    // Urutkan gabungan berdasarkan created_at descending
    usort($all_users, function($a, $b) {
        return strtotime($b['created_at'] ?? '1970-01-01') <=> strtotime($a['created_at'] ?? '1970-01-01');
    });

    $html = '';
    if (!empty($all_users)) {
        foreach ($all_users as $user) {
            if (isset($user['error'])) {
                $html .= '<tr><td colspan="7">' . htmlspecialchars($user['error']) . '</td></tr>';
                continue;
            }
            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($user['id']) . '</td>';
            $html .= '<td>' . htmlspecialchars($user['username']) . '</td>';
            $html .= '<td>' . htmlspecialchars($user['email'] ?? 'N/A') . '</td>';
            $html .= '<td>' . ($user['verified'] ? 'Terverifikasi' : 'Belum') . '</td>';
            $html .= '<td>' . htmlspecialchars($user['created_at']) . '</td>';
            $html .= '<td>' . htmlspecialchars($user['database']) . '</td>'; // Kolom baru
            $html .= '<td>';
            $html .= '<a href="edit_user.php?id=' . $user['id'] . '&db=' . $user['database'] . '" class="edit-btn">Edit</a> '; // Tambah param db
            $html .= '<a href="delete_user.php?id=' . $user['id'] . '&db=' . $user['database'] . '" class="delete-btn" onclick="return confirm(\'Yakin hapus pengguna ini?\')">Hapus</a>'; // Tambah param db
            $html .= '</td>';
            $html .= '</tr>';
        }
    } else {
        $html = '<tr><td colspan="7">Tidak ada pengguna ditemukan.</td></tr>';
    }
    return $html;
}

// ... (bagian proses tambah pengguna tetap sama seperti sebelumnya)

?>
<!-- Sisanya dari kode HTML dan JavaScript tetap sama -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Pengguna - HIJABGRAK Admin</title>
    <link rel="stylesheet" href="admin_users.css">
</head>
<body>
    <header class="navbar">
        <div class="logo">HIJABGRAK Admin</div>
        <nav>
            <ul class="navbar">
                <li><a href="admin_dashboard.php">Dashboard</a></li>
                <li><a href="admin_logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <section class="users-section">
        <h2>Kelola Pengguna</h2>
        <p>Kelola daftar pengguna HIJABGRAK. <span id="last-update">Terakhir update: <span id="update-time">-</span></span></p>

        <!-- Form Tambah Pengguna -->
       <!-- Tabel Pengguna -->
        <div class="users-table">
            <h3>Daftar Pengguna</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Status Verifikasi</th>
                        <th>Tanggal Dibuat</th>
                        <th>Database Asal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="users-tbody">
                    <?php echo loadUsersTable($pdo_login, $pdo); // PERBAIKAN: Panggil dengan kedua koneksi ?>
                </tbody>
            </table>
        </div>
    </section>


    <!-- Tambahkan script untuk polling -->
    <script>
        function updateUsersTable() {
    fetch('get_users.php')
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error('Error:', data.error);
                return;
            }
            let html = '';
            if (data.length > 0) {
                data.forEach(user => {
                    if (user.error) {
                        html += '<tr><td colspan="7">' + user.error + '</td></tr>';
                        return;
                    }
                    html += '<tr>';
                    html += '<td>' + user.id + '</td>';
                    html += '<td>' + user.username + '</td>';
                    html += '<td>' + (user.email || 'N/A') + '</td>';
                    html += '<td>' + (user.verified ? 'Terverifikasi' : 'Belum') + '</td>';
                    html += '<td>' + user.created_at + '</td>';
                    html += '<td>' + user.database + '</td>'; // Kolom baru
                    html += '<td>';
                    html += '<a href="edit_user.php?id=' + user.id + '&db=' + user.database + '" class="edit-btn">Edit</a> ';
                    html += '<a href="delete_user.php?id=' + user.id + '&db=' + user.database + '" class="delete-btn" onclick="return confirm(\'Yakin hapus pengguna ini?\')">Hapus</a>';
                    html += '</td>';
                    html += '</tr>';
                });
            } else {
                html = '<tr><td colspan="7">Tidak ada pengguna ditemukan.</td></tr>';
            }
            document.getElementById('users-tbody').innerHTML = html;
            document.getElementById('update-time').textContent = new Date().toLocaleTimeString();
        })
        .catch(error => console.error('Error fetching users:', error));
}
    </script>
</body>
</html>
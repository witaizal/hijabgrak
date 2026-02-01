<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location:login.php');  // Redirect ke login utama jika bukan admin
    exit;
}
require 'db.php';


// Hitung jumlah produk
$stmt = $pdo->query("SELECT COUNT(*) FROM products");
$product_count = $stmt->fetchColumn();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - HIJABGRAK</title>
    <link rel="stylesheet" href="admin_dashboard.css">
</head>
<body>
    <header class="navbar">
        <div class="logo">HIJABGRAK Admin</div>
        <nav>
				<ul class="navbar">
                <li><a href="admin_users.php">Kelola Pengguna</a></li>
				    <li>Kelola produk
                    <ul class="submenu">
                        <li><a href="admin_products.php">Oursis</a></li>
                        <li><a href="">Kiyowo</a></li>
                        <li><a href="">Aqry Hijab</a></li>
                        <li><a href="">HIJABGRAK</a></li>
                    </ul>
                </li>
                <li><a href="admin_logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <section class="products-section">
        <h2>Dashboard Admin</h2>
        <p>Selamat datang di panel admin HIJABGRAK.</p>
        <div>
            <h3>Ringkasan</h3>
            <p>Jumlah Produk: <?php echo $product_count; ?></p>
            <!-- Tambahkan ringkasan lainnya jika diperlukan -->
        </div>
    </section>
</body>
</html>
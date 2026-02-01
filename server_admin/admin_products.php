<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');  // Redirect ke login utama jika bukan admin
    exit;
}
require 'db.php';
// Sisanya tetap sama...

// Tambah produk
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $brand = $_POST['brand'];
    $image = $_POST['image'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    $stmt = $pdo->prepare("INSERT INTO products (name, brand, image, description, price) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$name, $brand, $image, $description, $price]);
    header('Location: admin_products.php');
    exit;
}

// Hapus produk
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: admin_products.php');
    exit;
}

// Ambil semua produk
$stmt = $pdo->query("SELECT * FROM products");
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Produk - HIJABGRAK</title>
    <link rel="stylesheet" href="admin_produk.css">
</head>
<body>
    <header class="navbar">
        <div class="logo">HIJABGRAK Admin</div>
        <nav>
            <ul class="navbar">
                <li><a href="admin_dashboard.php">Dashboard</a></li>
                <li>Kelola produk
                    <ul class="submenu">
                        <li><a href="admin_products.php">Oursis</a></li>
                        <li><a href="produk2.php">Kiyowo</a></li>
                        <li><a href="produk3.php">Aqry Hijab</a></li>
                        <li><a href="produk4.php">HIJABGRAK</a></li>
                    </ul>
                </li>
                <li><a href="admin_logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <section class="products-section">
        <h2>Kelola Produk</h2>

        <!-- Form Tambah Produk -->
        <form method="POST">
            <input type="text" name="name" placeholder="Nama Produk" required>
            <input type="text" name="brand" placeholder="Brand" required>
            <input type="text" name="image" placeholder="URL Gambar">
            <textarea name="description" placeholder="Deskripsi"></textarea>
            <input type="number" step="0.01" name="price" placeholder="Harga" required>
            <button type="submit" name="add_product">Tambah Produk</button>
        </form>

        <!-- Daftar Produk -->
        <h3>Daftar Produk</h3>
        <div class="products-container">
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                    <h3><?php echo $product['name']; ?></h3>
                    <p><?php echo $product['description']; ?></p>
                    <p>Harga: Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></p>
                    <a href="admin_edit_product.php?id=<?php echo $product['id']; ?>">Edit</a>
                    <a href="?delete=<?php echo $product['id']; ?>" onclick="return confirm('Hapus produk ini?')">Hapus</a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</body>
</html>
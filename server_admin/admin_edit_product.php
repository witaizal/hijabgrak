<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');  // Redirect ke login utama jika bukan admin
    exit;
}
require 'db.php';
// Sisanya tetap sama...
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $brand = $_POST['brand'];
    $image = $_POST['image'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    $stmt = $pdo->prepare("UPDATE products SET name = ?, brand = ?, image = ?, description = ?, price = ? WHERE id = ?");
    $stmt->execute([$name, $brand, $image, $description, $price, $id]);
    header('Location: admin_products.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Produk - HIJABGRAK</title>
    <link rel="stylesheet" href="admin_login.css">
</head>
<body>
    <header class="navbar">
        <div class="logo">HIJABGRAK Admin</div>
        <nav>
            <ul class="navbar">
                <li><a href="admin_dashboard.php">Dashboard</a></li>
                <li><a href="admin_products.php">Kelola Produk</a></li>
                <li><a href="admin_logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <section class="products-section">
        <h2>Edit Produk</h2>
        <form method="POST">
            <input type="text" name="name" value="<?php echo $product['name']; ?>" required>
            <input type="text" name="brand" value="<?php echo $product['brand']; ?>" required>
            <input type="text" name="image" value="<?php echo $product['image']; ?>">
            <textarea name="description"><?php echo $product['description']; ?></textarea>
            <input type="number" step="0.01" name="price" value="<?php echo $product['price']; ?>" required>
            <button type="submit">Update Produk</button>
        </form>
    </section>
</body>
</html>
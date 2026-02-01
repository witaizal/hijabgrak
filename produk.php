	<?php
// Sertakan koneksi database
echo "Current directory: " . __DIR__ . "<br>";
require 'db.php';

// Query untuk mengambil semua produk
$stmt = $pdo->prepare("SELECT * FROM products ORDER BY id DESC");
$stmt->execute();
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk - Perusahaan Hijab</title>
    <link rel="stylesheet" href="produk.css">
</head>
<body>
    <!-- Tombol Menu Hamburger -->
    <button id="menuToggle" class="menu-btn">☰ Menu</button>

    <!-- Navigasi Menu -->
    <nav id="navMenu" class="nav-menu">
        <ul>
            <li><a href="index.php">Back</a></li>
        </ul>
    </nav>

    <header>
        <h1>Produk Oursis</h1>
        <p>Jelajahi koleksi hijab berkualitas tinggi dari Perusahaan Hijab.</p>
    </header>
    
    <section class="products">
        <?php if (count($products) > 0): ?>
            <?php foreach ($products as $product): ?>
                <div class="product">
                    <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    <h3><?php echo htmlspecialchars($product['name']); ?> (<?php echo htmlspecialchars($product['brand']); ?>)</h3>
                    <p><?php echo htmlspecialchars($product['description']); ?></p>
                    <p><strong>Harga: Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></strong></p>
                    <!-- Link beli default ke Shopee, atau sesuaikan berdasarkan brand -->
                    <a href="https://shopee.co.id/oursis?entryPoint=ShopBySearch&searchKeyword=oursis" class="btn" target="_blank">Beli Sekarang</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Tidak ada produk tersedia saat ini.</p>
        <?php endif; ?>
		<?php
// Sertakan koneksi database
echo "Current directory: " . __DIR__ . "<br>";
require 'db.php';

// Query untuk mengambil produk Oursis saja
$stmt = $pdo->prepare("SELECT * FROM products WHERE brand = 'Oursis' ORDER BY id DESC");
$stmt->execute();
$products = $stmt->fetchAll();
?>

<!-- Sisanya sama seperti kode asli produk.php -->
    </section>

    <footer>
        <p>&copy; 2023 Perusahaan Hijab. Semua hak dilindungi.</p>
    </footer>

    <script>
        // JavaScript untuk Toggle Menu
        const menuToggle = document.getElementById('menuToggle');
        const navMenu = document.getElementById('navMenu');

        menuToggle.addEventListener('click', function() {
            // Toggle class 'active' untuk show/hide menu
            navMenu.classList.toggle('active');
        });
    </script>
</body>
</html>
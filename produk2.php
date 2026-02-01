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
        <h1>Produk Kyiowo</h1>
        <p>Jelajahi koleksi hijab berkualitas tinggi dari Perusahaan Hijab.</p>
    </header>
    <section class="products">
        <div class="product">
            <img src="https://via.placeholder.com/250x200?text=Hijab+Instan" alt="Hijab Instan">
            <h3>Hijab besti m</h3>
            <p>Mudah dipakai, cocok untuk sehari-hari. Bahan katun lembut.</p>
            <a href="https://shopee.co.id/oursis?entryPoint=ShopBySearch&searchKeyword=oursis" class="btn" target="_blank">Beli Sekarang</a>
        </div>
        <div class="product">
            <img src="https://via.placeholder.com/250x200?text=Hijab+Segi+Empat" alt="Hijab Segi Empat">
            <h3>Hijab besti s</h3>
            <p>Desain klasik dengan bahan premium. Tersedia berbagai warna.</p>
            <a href="https://shopee.co.id/hijabgrak" class="btn" target="_blank">Beli Sekarang</a>
        </div>
        <div class="product">
            <img src="https://via.placeholder.com/250x200?text=Hijab+Bergo" alt="Hijab Bergo">
            <h3>Hijab besti xs</h3>
            <p>Stylish dan nyaman untuk acara formal. Dengan aksen bordir.</p>
            <a href="https://shopee.co.id/hijabgrak" class="btn" target="_blank">Beli Sekarang</a>
        </div>
        <div class="product">
            <img src="https://via.placeholder.com/250x200?text=Hijab+Syari" alt="Hijab Syari">
            <h3>Hijab pinguin</h3>
            <p>Model syar'i dengan panjang ideal. Bahan chiffon ringan.</p>
            <a href="https://shopee.co.id/hijabgrak" class="btn" target="_blank">Beli Sekarang</a>
        </div>
        <div class="product">
            <img src="https://via.placeholder.com/250x200?text=Hijab+Sport" alt="Hijab Sport">
            <h3>Hijab Sport</h3>
            <p>Cocok untuk olahraga, bahan breathable dan cepat kering.</p>
            <a href="https://shopee.co.id/hijabgrak" class="btn" target="_blank">Beli Sekarang</a>
        </div>
        <div class="product">
            <img src="https://via.placeholder.com/250x200?text=Hijab+Premium" alt="Hijab Premium">
            <h3>Hijab Premium</h3>
            <p>Koleksi eksklusif dengan desain mewah. Terbatas stok.</p>
            <a href="https://shopee.co.id/hijabgrak" class="btn" target="_blank">Beli Sekarang</a>
        </div>
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
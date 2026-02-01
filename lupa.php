<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Sandi - HIJABGRAK</title>
    <link rel="stylesheet" href="lupa.css"> <!-- Pastikan file CSS Anda tersedia -->
    <style>
       
    </style>
</head>
<body>
    <!-- HEADER -->
    <header>
        <div class="logo">
            <img src="gambar/m.png" width="40px" height="25px" alt="Logo HIJABGRAK">
            HIJABGRAK
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main>
        <div class="forgot-password-container">
            <h2>Lupa Kata Sandi</h2>
            <p>Masukkan alamat email yang terdaftar untuk menerima link reset kata sandi.</p>
            <form id="forgotPasswordForm" action="#" method="POST"> <!-- Action diubah ke # untuk simulasi -->
                <input type="email" name="email" id="email" placeholder="Alamat Email" required>
                <button type="submit">Kirim Link Reset</button>
            </form>
            <div id="message" class="message"></div> <!-- Elemen untuk pesan -->
            <div class="back-link">
                <a href="index.php">Kembali ke Halaman Utama</a>
            </div>
        </div>
    </main>

    <!-- FOOTER -->
    <footer>
        <p>&copy; 2026 HIJABGRAK. All Rights Reserved.</p>
    </footer>

    <!-- JavaScript untuk menangani submit form -->
    <script>
        document.getElementById('forgotPasswordForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Mencegah submit default
            
            const email = document.getElementById('email').value.trim(); // Trim untuk menghilangkan spasi
            const messageDiv = document.getElementById('message');
            
            // Validasi sederhana
            if (!email || !email.includes('@') || !email.includes('.')) {
                messageDiv.className = 'message error';
                messageDiv.textContent = 'Harap masukkan alamat email yang valid.';
                messageDiv.style.display = 'block';
                return;
            }
            
            // Simulasi pengiriman (dalam dunia nyata, kirim ke server)
            // Tampilkan pesan sukses dengan email yang diisi
            messageDiv.className = 'message success';
            messageDiv.textContent = `Link reset kata sandi telah dikirim ke ${email}. Periksa inbox Anda!`;
            messageDiv.style.display = 'block';
            
            // Reset form
            document.getElementById('forgotPasswordForm').reset();
            
            // Redirect simulasi setelah 3 detik (lebih lama untuk membaca pesan)
            setTimeout(function() {
                window.location.href = 'index.php'; // Atau halaman konfirmasi
            }, 3000);
        });
    </script>
</body>
</html>
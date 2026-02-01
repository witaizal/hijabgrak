<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Website Elegan</title>
    <link rel="stylesheet" href="daftar.css">
    <style>
      
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Daftar Akun Baru</h2>

        <form id="registerForm" action="register_process.php" method="POST">
            <input type="text" id="username" name="username" placeholder="Nama Pengguna" required>

            <input type="email" id="email" name="email" placeholder="Email" required>

            <input type="password" id="password" name="password" placeholder="Kata Sandi" required>

            <input type="password" id="confirm_password" name="confirm_password" placeholder="Konfirmasi Kata Sandi" required>

            

            <button type="submit">Daftar</button>
        </form>

        <!-- Tambahkan div untuk pesan -->
        <div id="message" class="message"></div>

        <div class="login-link">
            <p>Sudah punya akun? <a href="login.php">Masuk di sini</a></p>
        </div>
    </div>

   <!-- Bagian script di daftar.php, ubah redirect -->
<script>
    document.getElementById('registerForm').addEventListener('submit', function(event) {
        event.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('register_process.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            const messageDiv = document.getElementById('message');
            if (data.status === 'success') {
                messageDiv.className = 'message success';
                messageDiv.innerHTML = data.message;
                // Ubah redirect: langsung ke halaman utama setelah sukses
                setTimeout(() => window.location.href = 'login.php', 2000); // Ganti 'index.php' dengan halaman utama Anda
            } else {
                messageDiv.className = 'message error';
                messageDiv.innerHTML = data.message;
            }
            messageDiv.style.display = 'block';
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan. Coba lagi.');
        });
    });
</script>
</body>

</html>
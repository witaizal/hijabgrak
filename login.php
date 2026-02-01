<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Website Elegan</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <button id="menuToggle" class="menu-btn">☰ Menu</button>
    <nav id="navMenu" class="nav-menu">
        <ul>
            <li><a href="index.php">Back</a></li>
        </ul>
    </nav>
    <div class="login-container">
        <h2>Masuk ke Akun Anda</h2>
        <form id="loginForm" action="login_process.php" method="POST">
            <input type="text" id="username" name="username" placeholder="Nama Pengguna" required>
            <input type="password" id="password" name="password" placeholder="Kata Sandi" required>
            <button type="submit">Masuk</button>
        </form>
        <div id="message" class="message"></div> <!-- Div untuk pesan error/sukses -->
        <div class="forgot-password">
            <a href="lupa.php">Lupa Kata Sandi?</a>
        </div>
        <div class="register-link">
            <p>Belum punya akun? <a href="daftar.php">Daftar di sini</a></p>
        </div>
    </div>

    <script>
        // JavaScript untuk Toggle Menu
        const menuToggle = document.getElementById('menuToggle');
        const navMenu = document.getElementById('navMenu');

        menuToggle.addEventListener('click', function() {
            // Toggle class 'active' untuk show/hide menu
            navMenu.classList.toggle('active');
        });

        // Event listener untuk submit form login
        document.getElementById('loginForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Mencegah submit default (tidak redirect)
            
            const formData = new FormData(this);
            console.log('Mengirim data:', Object.fromEntries(formData)); // Log data yang dikirim (untuk debug)
            
            fetch('login_process.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log('Respons status:', response.status); // Log status HTTP
                if (!response.ok) {
                    throw new Error('Respons server tidak OK: ' + response.status);
                }
                return response.json();
            })
            // Di dalam event listener submit form (sudah ada di kode Anda)
				.then(data => {
					console.log('Data respons:', data);
					const messageDiv = document.getElementById('message');
					if (data.status === 'success') {
						messageDiv.className = 'message success';
						messageDiv.innerHTML = data.message;
						// Gunakan data.redirect untuk redirect dinamis
						setTimeout(() => window.location.href = data.redirect, 2000);
					} else {
						messageDiv.className = 'message error';
						messageDiv.innerHTML = data.message;
					}
					messageDiv.style.display = 'block';
				})
            .catch(error => {
                console.error('Error:', error);
                const messageDiv = document.getElementById('message');
                messageDiv.className = 'message error';
                messageDiv.innerHTML = 'Terjadi kesalahan: ' + error.message;
                messageDiv.style.display = 'block';
            });
        });
    </script>
</body>
</html>
<?php
session_start();
session_destroy();  // Hapus semua session
// Redirect ke login.php di website utama
header('Location: http://localhost/izal/login.php');  // Gunakan path relatif jika di folder sama
// Atau gunakan absolut jika di folder berbeda: header
exit;
?>
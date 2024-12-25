<?php
session_start();
session_unset();
session_destroy(); // Menghapus semua session
header('Location: beranda.php'); // Redirect ke halaman beranda
exit();
?>

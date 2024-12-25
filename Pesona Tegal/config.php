<!-- // config.php -->
<?php
$host = "localhost"; // Nama host (biasanya localhost untuk pengembangan lokal)
$username = "root";  // Username MySQL
$password = "";      // Password MySQL (kosong jika default di XAMPP)
$database = "pesona_tegal"; // Nama database kamu

// Membuat koneksi
$conn = new mysqli($host, $username, $password, $database);

// Periksa koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
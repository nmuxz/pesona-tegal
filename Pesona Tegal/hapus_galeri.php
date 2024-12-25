<?php
session_start();
include 'config.php';
// Pastikan koneksi ke database sudah dilakukan sebelumnya

// Ambil ID galeri yang akan dihapus dari GET
$id_galeri = intval($_GET['id']);

// Pastikan kategori juga diterima dengan benar dari GET
$kategori = isset($_GET['kategori']) ? trim($_GET['kategori']) : '';

// Jika kategori tidak ditemukan di URL, coba ambil dari session
if (empty($kategori) && isset($_SESSION['kategori'])) {
    $kategori = $_SESSION['kategori'];
}

// Simpan kategori ke session untuk penggunaan berikutnya
if (!empty($kategori)) {
    $_SESSION['kategori'] = $kategori;
}

// Query untuk mengambil foto dari galeri yang akan dihapus
$query = "SELECT foto FROM galeri WHERE id = ?";
$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Query error: " . $conn->error);
}
$stmt->bind_param("i", $id_galeri);
$stmt->execute();
$result = $stmt->get_result();
$galeri = $result->fetch_assoc();

// Jika data ditemukan, hapus foto dari server
if ($galeri) {
    // Hapus foto dari folder
    $file_path = "uploads/" . $galeri['foto'];
    if (file_exists($file_path)) {
        unlink($file_path);
    }

    // Hapus data galeri dari database
    $delete_query = "DELETE FROM galeri WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    if (!$stmt) {
        die("Query error: " . $conn->error);
    }
    $stmt->bind_param("i", $id_galeri);
    $stmt->execute();
}

// Redirect kembali ke halaman galeri sesuai kategori setelah penghapusan
if (!empty($kategori)) {
    header("Location: admin_galeri.php?kategori=" . urlencode($kategori));
} else {
    header("Location: admin_galeri.php");
}
exit();
?>

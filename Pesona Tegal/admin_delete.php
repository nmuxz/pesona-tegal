<?php
include 'config.php';

// Mendapatkan ID dan kategori dari parameter URL
$id = intval($_GET['id']);
$kategori = $_GET['kategori'] ?? '';

// Validasi kategori
$allowed_categories = ['wisata', 'kuliner', 'oleh_oleh']; // Disesuaikan dengan tabel utama
if (!in_array($kategori, $allowed_categories)) {
    $_SESSION['error'] = "Kategori tidak valid.";
    header("Location: admin_list.php?kategori=$kategori");
    exit;
}

try {
    // Ambil informasi foto utama sebelum menghapus data
    $query = $conn->prepare("SELECT foto_utama FROM $kategori WHERE id = ?");
    $query->bind_param("i", $id);
    $query->execute();
    $result = $query->get_result();
    $data = $result->fetch_assoc();

    if (!$data) {
        throw new Exception("Data tidak ditemukan.");
    }

    $fotoUtama = $data['foto_utama'];
    $fotoPath = "uploads/$kategori/$fotoUtama";

    // Hapus data dari database
    $deleteQuery = $conn->prepare("DELETE FROM $kategori WHERE id = ?");
    $deleteQuery->bind_param("i", $id);
    $deleteQuery->execute();

    if ($deleteQuery->affected_rows > 0) {
        // Hapus foto utama jika ada
        if (file_exists($fotoPath)) {
            unlink($fotoPath);
        }
        $_SESSION['message'] = "Data berhasil dihapus.";
    } else {
        throw new Exception("Gagal menghapus data.");
    }
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
}

// Redirect kembali ke halaman admin_list.php dengan kategori yang sesuai
header("Location: admin_list.php?kategori=" . urlencode($kategori));
exit;
?>

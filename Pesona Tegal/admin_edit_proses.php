<?php
// Koneksi ke database
include 'config.php';

// Ambil data dari form
$id = $_POST['id'];
$kategori = $_POST['kategori'];
$subkategori = $_POST['subkategori']; // Ambil subkategori dari form
$nama = $_POST['nama'];
$deskripsi = $_POST['deskripsi'];
$foto_utama_lama = isset($_POST['foto_utama_lama']) ? $_POST['foto_utama_lama'] : null;

// Tentukan nama kolom berdasarkan kategori
$namaKolom = "";
$subkategoriKolom = "";
$deskripsiKolom = "deskripsi";
if ($kategori === "wisata") {
    $namaKolom = "nama_wisata";
    $subkategoriKolom = "subkategori";
} elseif ($kategori === "kuliner") {
    $namaKolom = "nama_kuliner";
    $subkategoriKolom = "subkategori";
} elseif ($kategori === "oleh_oleh") {
    $namaKolom = "nama_oleh_oleh";
    $subkategoriKolom = "subkategori";
} else {
    die("Kategori tidak valid.");
}

// Proses upload foto baru jika ada
$foto_utama = $foto_utama_lama; // Gunakan foto lama secara default
if (!empty($_FILES['foto_utama']['name'])) {
    $targetDir = "uploads/" . $kategori . "/" . $subkategori . "/";
    $foto_utama = basename($_FILES['foto_utama']['name']);
    $targetFile = $targetDir . $foto_utama;

    // Buat direktori jika belum ada
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    // Coba upload file baru
    if (move_uploaded_file($_FILES['foto_utama']['tmp_name'], $targetFile)) {
        // Hapus file lama jika ada
        if ($foto_utama_lama && file_exists("uploads/" . $kategori . "/" . $foto_utama_lama)) {
            unlink("uploads/" . $kategori . "/" . $foto_utama_lama);
        }
    } else {
        echo "Gagal mengupload foto.";
        exit;
    }
}

// Query update data termasuk subkategori dan deskripsi
$query = "UPDATE $kategori SET $namaKolom = ?, $subkategoriKolom = ?, $deskripsiKolom = ?, foto_utama = ? WHERE id = ?";
$stmt = $conn->prepare($query);

// Debug query jika terjadi kesalahan
if (!$stmt) {
    die("Query Error: " . $conn->error);
}

$stmt->bind_param("ssssi", $nama, $subkategori, $deskripsi, $foto_utama, $id);

// Eksekusi query
if ($stmt->execute()) {
    echo "Data berhasil diupdate.";
    header("Location: admin_list.php?kategori=$kategori");
} else {
    echo "Gagal mengupdate data: " . $stmt->error;
}
?>

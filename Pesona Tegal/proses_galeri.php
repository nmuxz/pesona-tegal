<?php
include 'config.php';

$kategori = $_POST['kategori'];
$id_kategori = intval($_POST['id_kategori']);
$status = $_POST['status'] ?? 'aktif';
$foto = $_FILES['foto'];

// Validasi kategori
$allowed_categories = ['wisata', 'kuliner', 'oleh_oleh'];
if (!in_array($kategori, $allowed_categories)) {
    die("Kategori tidak valid.");
}

// Dapatkan subkategori dari tabel kategori yang relevan
$query_subkategori = "SELECT subkategori FROM $kategori WHERE id = ?";
$stmt = $conn->prepare($query_subkategori);
$stmt->bind_param('i', $id_kategori);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $subkategori = $row['subkategori'];
} else {
    die("ID kategori tidak valid.");
}

// Tentukan direktori upload berdasarkan kategori dan subkategori
$target_dir = "uploads/galeri/$kategori/" . strtolower(str_replace(' ', '_', $subkategori)) . "/";
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0777, true); // Buat folder jika belum ada
}

// Nama file upload
$target_file = $target_dir . basename($foto['name']);

// Proses upload file
if (move_uploaded_file($foto['tmp_name'], $target_file)) {
    // Simpan ke database
    $query = "INSERT INTO galeri (id_kategori, kategori, foto, status) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('isss', $id_kategori, $kategori, $target_file, $status);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Galeri berhasil ditambahkan.";
    } else {
        $_SESSION['message'] = "Gagal menyimpan galeri ke database.";
    }
} else {
    $_SESSION['message'] = "Gagal mengupload file.";
}

header("Location: admin_galeri.php?kategori=$kategori");
exit;

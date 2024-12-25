<?php
include 'config.php';

$id = intval($_GET['id'] ?? 0);
$kategori = $_GET['kategori'] ?? '';

// Validasi kategori
$allowed_categories = ['wisata', 'kuliner', 'oleh_oleh'];
if (!in_array($kategori, $allowed_categories)) {
    echo '';
    exit;
}

// Query untuk mendapatkan nama kategori
$query = "SELECT nama_$kategori AS nama FROM $kategori WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

echo $data['nama'] ?? ''; // Kembalikan nama atau string kosong jika tidak ditemukan

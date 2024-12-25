<?php
include 'config.php'; // Koneksi ke database

// Pastikan koneksi berhasil
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kategori = $_POST['kategori'];
    $subkategori = $_POST['subkategori'];
    $nama = $_POST['nama'];
    $deskripsi = $_POST['deskripsi'];
    $foto = $_FILES['foto_utama'];
    $rating = $_POST['rating'];  // Menangani input rating
    $latitude = $_POST['latitude']; // Menangani input latitude
    $longitude = $_POST['longitude']; // Menangani input longitude

    // Mengubah format subkategori dari underscore_case menjadi Proper Case
    $subkategori = str_replace('_', ' ', $subkategori);
    $subkategori = ucwords($subkategori);

    // Validasi subkategori sesuai kategori
    $allowedSubcategories = [
        'wisata' => ['Wisata Alam', 'Wisata Taman', 'Wisata Sejarah'],
        'kuliner' => ['Cafe', 'Rumah Makan'],
        'oleh_oleh' => ['Barang', 'Makanan'],
    ];

    if (!isset($allowedSubcategories[$kategori]) || !in_array($subkategori, $allowedSubcategories[$kategori])) {
        die("Subkategori tidak valid untuk kategori $kategori");
    }

    // Lokasi direktori untuk upload gambar
    $targetDir = "uploads/" . $kategori . "/" . $subkategori . "/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    // Path file
    $targetFile = $targetDir . basename($foto['name']);

    // Memeriksa ekstensi file gambar
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($imageFileType, $allowedTypes)) {
        if (move_uploaded_file($foto['tmp_name'], $targetFile)) {
            $id_item = null; // Variabel umum untuk menyimpan ID item
            if ($kategori == 'wisata') {
                $stmt = $conn->prepare("INSERT INTO wisata (subkategori, nama_wisata, foto_utama, deskripsi, latitude, longitude) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssdd", $subkategori, $nama, $targetFile, $deskripsi, $latitude, $longitude);
            } elseif ($kategori == 'kuliner') {
                $stmt = $conn->prepare("INSERT INTO kuliner (subkategori, nama_kuliner, foto_utama, deskripsi, latitude, longitude) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssdd", $subkategori, $nama, $targetFile, $deskripsi, $latitude, $longitude);
            } elseif ($kategori == 'oleh_oleh') {
                $stmt = $conn->prepare("INSERT INTO oleh_oleh (subkategori, nama_oleh_oleh, foto_utama, deskripsi, latitude, longitude) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssdd", $subkategori, $nama, $targetFile, $deskripsi, $latitude, $longitude);
            }

            if ($stmt->execute()) {
                $id_item = $conn->insert_id;

                // Tambahkan rating
                if ($rating >= 1 && $rating <= 5) {
                    $stmt_rating = $conn->prepare("INSERT INTO rating (id_item, kategori, rating) VALUES (?, ?, ?)");
                    $stmt_rating->bind_param("iss", $id_item, $kategori, $rating);
                    $stmt_rating->execute();
                } else {
                    header("Location: admin_add.php?status=invalid_rating");
                    exit;
                }

                header("Location: admin_add.php?status=success");
            } else {
                die("Error: " . $stmt->error);
            }
        } else {
            header("Location: admin_add.php?status=error");
        }
    } else {
        header("Location: admin_add.php?status=invalid_image_type");
    }
}
?>
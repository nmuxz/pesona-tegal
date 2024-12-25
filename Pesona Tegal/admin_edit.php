<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_edit.php");
    exit;
}

// Koneksi ke database
include 'config.php';

// Ambil parameter `id` dan `kategori` dari URL
$id = $_GET['id'] ?? null;
$kategori = $_GET['kategori'] ?? null;

// Validasi input
if (!$id || !$kategori || !in_array($kategori, ['wisata', 'kuliner', 'oleh_oleh'])) {
    echo "Parameter tidak valid.";
    exit;
}

// Query untuk mengambil data berdasarkan ID
$query = "SELECT * FROM $kategori WHERE id = ?";
$stmt = $conn->prepare($query);

if (!$stmt) {
    die("Query Error: " . $conn->error);
}

$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
} else {
    echo "Data tidak ditemukan.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data - <?php echo ucfirst($kategori); ?></title>
    <link rel="stylesheet" href="admin_edit.css">
</head>
<body>
    <div class="container">
        <h1>Edit Data - <?php echo ucfirst($kategori); ?></h1>
        <form action="admin_edit_proses.php" method="post" enctype="multipart/form-data">
            <!-- Hidden Inputs -->
            <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
            <input type="hidden" name="kategori" value="<?php echo $kategori; ?>">
            <input type="hidden" name="foto_utama_lama" value="<?php echo $data['foto_utama']; ?>">

            <!-- Input Nama berdasarkan Kategori -->
            <label for="nama">Nama:</label>
            <input type="text" name="nama" id="nama" value="<?php 
                echo $kategori === 'wisata' ? $data['nama_wisata'] : 
                     ($kategori === 'kuliner' ? $data['nama_kuliner'] : 
                     $data['nama_oleh_oleh']); ?>" required><br>

            <!-- Input Subkategori -->
            <label for="subkategori">Subkategori:</label>
            <select name="subkategori" id="subkategori" required>
                <?php
                $subkategoriList = $kategori === 'wisata' ? ['Wisata Alam', 'Wisata Sejarah', 'Wisata Taman'] :
                                   ($kategori === 'kuliner' ? ['Cafe', 'Rumah Makan'] : 
                                   ['Barang', 'Makanan']);
                foreach ($subkategoriList as $subkategori) {
                    $selected = $subkategori === $data['subkategori'] ? 'selected' : '';
                    echo "<option value='$subkategori' $selected>$subkategori</option>";
                }
                ?>
            </select><br>

            <!-- Input Deskripsi -->
            <label for="deskripsi">Deskripsi:</label>
            <textarea name="deskripsi" id="deskripsi" rows="4" required><?php echo $data['deskripsi']; ?></textarea><br>

            <!-- Input Foto Utama -->
            <label for="foto_utama">Foto Utama:</label>
            <input type="file" name="foto_utama" id="foto_utama"><br>
            <img src="<?php echo $data['foto_utama']; ?>" alt="Foto Utama" width="200"><br>

            <!-- Tombol Simpan -->
            <button type="submit">Simpan</button>
        </form>
    </div>
</body>
</html>

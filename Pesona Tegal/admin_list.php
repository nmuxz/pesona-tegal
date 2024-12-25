<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

include 'config.php';

$kategori = $_GET['kategori'] ?? '';

// Pastikan kategori valid sebelum melakukan query
$allowed_categories = ['wisata', 'kuliner', 'oleh_oleh'];
if (!in_array($kategori, $allowed_categories)) {
    die("Kategori tidak valid.");
}

// Menampilkan pesan sukses atau error jika ada
if (isset($_SESSION['message'])) {
    echo "<script>alert('" . $_SESSION['message'] . "');</script>";
    unset($_SESSION['message']);
}

// Pastikan kategori valid sebelum melakukan query
$allowed_categories = ['wisata', 'kuliner', 'oleh_oleh'];
if (!in_array($kategori, $allowed_categories)) {
    die("Kategori tidak valid.");
}

$result = $conn->query("SELECT * FROM $kategori");

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Kelola <?= htmlspecialchars(ucwords($kategori)) ?></title>
    <link rel="stylesheet" href="admin_list.css">
</head>

<body>
    <div class="container">
        <h1>Kelola Data <?= htmlspecialchars(ucwords($kategori)) ?></h1>
        <a href="admin_add.php?kategori=<?= $kategori ?>" class="btn-add">Tambah Data</a>
        <a href="admin_galeri.php?kategori=<?= $kategori ?>" class="btn-add">Kelola Galeri</a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Foto Utama</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['nama_' . $kategori]) ?></td>
                        <td>
                            <img src="<?= htmlspecialchars($row['foto_utama']) ?>" alt="Foto <?= htmlspecialchars($row['nama_' . $kategori]) ?>" class="thumbnail">
                        </td>

                        <td>
                            <a href="admin_edit.php?id=<?= $row['id'] ?>&kategori=<?= $kategori ?>" class="btn-edit">Edit</a>
                            <a href="admin_delete.php?id=<?= $row['id'] ?>&kategori=<?= $kategori ?>" class="btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>

</html>
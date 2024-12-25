<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_galeri.php");
    exit;
}

include 'config.php';

// Pastikan kategori valid sebelum melakukan query
$kategori = $_GET['kategori'] ?? '';
$allowed_categories = ['wisata', 'kuliner', 'oleh_oleh'];
if (!in_array($kategori, $allowed_categories)) {
    die("Kategori tidak valid.");
}

// Menampilkan pesan sukses atau error jika ada
if (isset($_SESSION['message'])) {
    echo "<script>alert('" . $_SESSION['message'] . "');</script>";
    unset($_SESSION['message']);
}

if (!empty($kategori)) {
    $_SESSION['kategori'] = $kategori;
}

// Ambil data ID dan nama kategori untuk dropdown
$query_categories = "SELECT id, nama_$kategori AS nama FROM $kategori";
$result_categories = $conn->query($query_categories);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Kelola Galeri <?= htmlspecialchars(ucwords($kategori)) ?></title>
    <link rel="stylesheet" href="admin_galeri.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container">
        <h1>Kelola Galeri <?= htmlspecialchars(ucwords($kategori)) ?></h1>
        <form id="galeriForm" action="proses_galeri.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="kategori" value="<?= htmlspecialchars($kategori) ?>">
            <label for="id_kategori">ID Kategori:</label>
            <select id="id_kategori" name="id_kategori" required>
                <option value="">Pilih ID</option>
                <?php while ($row = $result_categories->fetch_assoc()): ?>
                    <option value="<?= htmlspecialchars($row['id']) ?>">
                        <?= htmlspecialchars($row['id']) ?> - <?= htmlspecialchars($row['nama']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <label for="nama_kategori">Nama Kategori:</label>
            <input type="text" id="nama_kategori" name="nama_kategori" readonly>
            <label for="foto">Upload Foto:</label>
            <input type="file" id="foto" name="foto" required>
            <label for="status">Status:</label>
            <select id="status" name="status">
                <option value="aktif" selected>Aktif</option>
                <option value="nonaktif">Nonaktif</option>
            </select>
            <button type="submit">Simpan</button>
        </form>
        <hr>
        <h2>Galeri yang Sudah Ada</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ID Kategori</th>
                    <th>Kategori</th>
                    <th>Foto</th>
                    <th>Status</th>
                    <th>Dibuat Pada</th>
                    <th>Diupdate Pada</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query_galeri = "SELECT * FROM galeri WHERE kategori = ?";
                $stmt = $conn->prepare($query_galeri);
                $stmt->bind_param('s', $kategori);
                $stmt->execute();
                $result_galeri = $stmt->get_result();

                while ($row = $result_galeri->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['id_kategori']) ?></td>
                        <td><?= htmlspecialchars(ucwords($row['kategori'])) ?></td>
                        <td>
                            <img src="uploads/galeri/<?= htmlspecialchars($row['foto']) ?>" alt="Foto" class="thumbnail">
                        </td>
                        <td><?= htmlspecialchars(ucwords($row['status'])) ?></td>
                        <td><?= htmlspecialchars($row['created_at']) ?></td>
                        <td><?= htmlspecialchars($row['updated_at']) ?></td>
                        <td>
                            <a href="hapus_galeri.php?id=<?= $row['id'] ?>" class="delete-btn" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function () {
            $('#id_kategori').change(function () {
                const id = $(this).val();
                if (id) {
                    $.ajax({
                        url: 'get_kategori_name.php',
                        method: 'GET',
                        data: {
                            id: id,
                            kategori: '<?= $kategori ?>'
                        },
                        success: function (response) {
                            $('#nama_kategori').val(response);
                        },
                        error: function () {
                            alert('Gagal mengambil data nama kategori.');
                        }
                    });
                } else {
                    $('#nama_kategori').val('');
                }
            });
        });
    </script>
</body>

</html>

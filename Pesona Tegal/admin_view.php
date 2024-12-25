<?php
include 'config.php'; // Koneksi ke database
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

// Fetch data with rating calculation
$wisata_query = $conn->query("SELECT wisata.*, IFNULL(AVG(rating.rating), 0) AS rating FROM wisata LEFT JOIN rating ON rating.id = wisata.id AND rating.kategori = 'wisata' GROUP BY wisata.id");
$kuliner_query = $conn->query("SELECT kuliner.*, IFNULL(AVG(rating.rating), 0) AS rating FROM kuliner LEFT JOIN rating ON rating.id = kuliner.id AND rating.kategori = 'kuliner' GROUP BY kuliner.id");
$oleh_oleh_query = $conn->query("SELECT oleh_oleh.*, IFNULL(AVG(rating.rating), 0) AS rating FROM oleh_oleh LEFT JOIN rating ON rating.id = oleh_oleh.id AND rating.kategori = 'oleh_oleh' GROUP BY oleh_oleh.id");

// Check for errors in the query
if (!$wisata_query) {
    die("Error in wisata query: " . $conn->error);
}
if (!$kuliner_query) {
    die("Error in kuliner query: " . $conn->error);
}
if (!$oleh_oleh_query) {
    die("Error in oleh_oleh query: " . $conn->error);
}

// Fetch results
$wisata = $wisata_query->fetch_all(MYSQLI_ASSOC);
$kuliner = $kuliner_query->fetch_all(MYSQLI_ASSOC);
$oleh_oleh = $oleh_oleh_query->fetch_all(MYSQLI_ASSOC);

// Fungsi untuk menampilkan bintang berdasarkan rating
function renderStars($rating) {
    $fullStars = floor($rating);
    $halfStar = $rating - $fullStars >= 0.5 ? 1 : 0;
    $emptyStars = 5 - $fullStars - $halfStar;

    return str_repeat('★', $fullStars) . str_repeat('☆', $emptyStars);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kategori</title>
    <link rel="stylesheet" href="admin_view.css">
</head>
<body>
    <div class="container">
        <h1>Data Kategori</h1>

        <!-- Data Wisata -->
        <h2>Wisata</h2>
        <?php if (count($wisata) > 0): ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Subkategori</th>
                        <th>Nama</th>
                        <th>Deskripsi</th>
                        <th>Foto</th>
                        <th>Rating</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($wisata as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['subkategori']) ?></td>
                            <td><?= htmlspecialchars($row['nama_wisata']) ?></td>
                            <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                            <td><img src="<?= htmlspecialchars($row['foto_utama']) ?>" alt="<?= htmlspecialchars($row['nama_wisata']) ?>" class="thumbnail"></td>
                            <td>
                                <?= renderStars($row['rating']) ?><br>
                                (<?= number_format($row['rating'], 1) ?>)
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Tidak ada data wisata.</p>
        <?php endif; ?>

        <!-- Data Kuliner -->
        <h2>Kuliner</h2>
        <?php if (count($kuliner) > 0): ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Subkategori</th>
                        <th>Nama</th>
                        <th>Deskripsi</th>
                        <th>Foto</th>
                        <th>Rating</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($kuliner as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['subkategori']) ?></td>
                            <td><?= htmlspecialchars($row['nama_kuliner']) ?></td>
                            <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                            <td><img src="<?= htmlspecialchars($row['foto_utama']) ?>" alt="<?= htmlspecialchars($row['nama_kuliner']) ?>" class="thumbnail"></td>
                            <td>
                                <?= renderStars($row['rating']) ?><br>
                                (<?= number_format($row['rating'], 1) ?>)
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Tidak ada data kuliner.</p>
        <?php endif; ?>

        <!-- Data Oleh-oleh -->
        <h2>Oleh-oleh</h2>
        <?php if (count($oleh_oleh) > 0): ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Subkategori</th>
                        <th>Nama</th>
                        <th>Deskripsi</th>
                        <th>Foto</th>
                        <th>Rating</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($oleh_oleh as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['subkategori']) ?></td>
                            <td><?= htmlspecialchars($row['nama_oleh_oleh']) ?></td>
                            <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                            <td><img src="<?= htmlspecialchars($row['foto_utama']) ?>" alt="<?= htmlspecialchars($row['nama_oleh_oleh']) ?>" class="thumbnail"></td>
                            <td>
                                <?= renderStars($row['rating']) ?><br>
                                (<?= number_format($row['rating'], 1) ?>)
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Tidak ada data oleh-oleh.</p>
        <?php endif; ?>
    </div>
</body>
</html>

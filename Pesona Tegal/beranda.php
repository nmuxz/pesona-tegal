<!-- medofikasi masuk, daftar, keluar -->
<?php
session_start();

include 'config.php';

if (isset($_SESSION['error_message'])) {
    echo "<script>alert('" . $_SESSION['error_message'] . "');</script>";
    unset($_SESSION['error_message']);  // Menghapus pesan setelah ditampilkan
}
// Cek apakah pengguna sudah login
$isLoggedIn = isset($_SESSION['email']); // 'user_id' di-set saat login berhasil

/// Query untuk mendapatkan data wisata berdasarkan rating tertinggi
$query_wisata = "SELECT w.id, w.nama_wisata, w.deskripsi, w.foto_utama 
FROM wisata w
LEFT JOIN rating r ON w.id = r.id_item AND r.kategori = 'wisata'
GROUP BY w.id
ORDER BY AVG(r.rating) DESC
LIMIT 4";

$result_wisata = $conn->query($query_wisata);

// Query untuk mendapatkan data kuliner berdasarkan rating tertinggi
$query_kuliner = "SELECT k.id, k.nama_kuliner, k.deskripsi, k.foto_utama 
 FROM kuliner k
 LEFT JOIN rating r ON k.id = r.id_item AND r.kategori = 'kuliner'
 GROUP BY k.id
 ORDER BY AVG(r.rating) DESC
 LIMIT 4";

$result_kuliner = $conn->query($query_kuliner);

// Query untuk mendapatkan data oleh-oleh berdasarkan rating tertinggi
$query_oleholeh = "SELECT o.id, o.nama_oleh_oleh, o.deskripsi, o.foto_utama 
   FROM oleh_oleh o
   LEFT JOIN rating r ON o.id = r.id_item AND r.kategori = 'oleh_oleh'
   GROUP BY o.id
   ORDER BY AVG(r.rating) DESC
   LIMIT 4";

$result_oleholeh = $conn->query($query_oleholeh);
?>
<!-- ========================================== -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesona Tegal</title>
    <link rel="stylesheet" href="beranda.css">
</head>

<body data-logged-in="<?= $isLoggedIn ? 'true' : 'false' ?>">
    <!-- Header -->
    <header class="main-header">
        <nav class="navbar">
            <img id="profile-logo" src="asset/Logo.png" alt="Logo Pesona Tegal" class="logo">
            <ul class="nav-links">
                <li><a href="beranda.php">Beranda</a></li>
                <li class="dropdown">
                    <a href="#">Wisata <span class="dropdown-arrow">▼</span></a>
                    <ul class="dropdown-menu">
                        <li><a href="wisata-alam.php">Wisata Alam</a></li>
                        <li><a href="wisata-taman.php">Wisata Taman</a></li>
                        <li><a href="wisata-sejarah.php">Wisata Sejarah</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#">Kuliner <span class="dropdown-arrow">▼</span></a>
                    <ul class="dropdown-menu">
                        <li><a href="kuliner-cafe.php">Cafe</a></li>
                        <li><a href="kuliner-rumahmakan.php">Rumah Makan</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#">Oleh-oleh <span class="dropdown-arrow">▼</span></a>
                    <ul class="dropdown-menu">
                        <li><a href="oleh-makanan.php">Makanan</a></li>
                        <li><a href="oleh-barang.php">Barang</a></li>
                    </ul>
                </li>
            </ul>
            <div class="auth-buttons">
                <!-- Tampilkan tombol berdasarkan status login -->
                <?php if ($isLoggedIn): ?>
                    <button class="btn" id="btn-keluar">Keluar</button>
                <?php else: ?>
                    <button class="btn" id="btn-masuk">Masuk</button>
                    <button class="btn" id="btn-daftar">Daftar</button>
                <?php endif; ?>
            </div>
            <div class="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <div class="menu">
                <ul class="categories">
                    <img src="asset/Logo.png" alt="Logo Pesona Tegal" class="logo1">
                    <a>Pesona Tegal</a>
                    <li class="category">
                        <a href="beranda.php">Beranda</a>
                    </li>
                    <li class="category">
                        <span>Wisata</span>
                        <ul class="subcategories">
                            <li><a href="wisata-alam.php">Wisata Alam</a></li>
                            <li><a href="wisata-taman.php">Wisata Taman</a></li>
                            <li><a href="wisata-sejarah.php">Wisata Sejarah</a></li>
                        </ul>
                    </li>
                    <li class="category">
                        <span>Kuliner</span>
                        <ul class="subcategories">
                            <li><a href="kuliner-cafe.php">Cafe</a></li>
                            <li><a href="kuliner-rumahmakan.php">Rumah Makan</a></li>
                        </ul>
                    </li>
                    <li class="category">
                        <span>Oleh-oleh</span>
                        <ul class="subcategories">
                            <li><a href="oleh-makanan.php">Makanan</a></li>
                            <li><a href="oleh-barang.php">Barang</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-content">
            <h1>YUK EXPLORE TEGAL DENGAN GEMBIRA</h1>
            <p>Liburan bukan hanya sebuah tujuan, tapi juga sebuah keadaan pikiran.</p>
            <?php if ($isLoggedIn): ?>
                <button class="btn-cta" id="open-register-btn" style="display: none;">Daftar Sekarang</button>
            <?php else: ?>
                <button class="btn-cta" id="open-register-btn">Daftar Sekarang</button>
            <?php endif; ?>
        </div>
    </section>
    <section class="wisata-list">
        <div class="card">
            <h2>Rekomendasi Wisata</h2>
        </div>
    </section>
    <section class="wisata-list">
        <?php
        if ($result_wisata->num_rows > 0) {
            while ($row = $result_wisata->fetch_assoc()) {
                $wisata_id = $row['id'];
                $query_rating = "SELECT AVG(rating) AS avg_rating FROM rating WHERE id_item = $wisata_id AND kategori = 'wisata'";
                $result_rating = $conn->query($query_rating);
                $avg_rating = 0;
                if ($result_rating->num_rows > 0) {
                    $rating_data = $result_rating->fetch_assoc();
                    $avg_rating = round($rating_data['avg_rating'], 1);
                }
                echo '
            <div class="card">
                <img src="' . $row['foto_utama'] . '" alt="' . htmlspecialchars($row['nama_wisata']) . '">
                <div class="card-body">
                    <h3>' . htmlspecialchars($row['nama_wisata']) . '</h3>
                    <p>' . htmlspecialchars(substr($row['deskripsi'], 0, 100)) . '...</p>
                    <div class="rating">' . renderStars($avg_rating) . ' (' . $avg_rating . ')</div>
                    <a href="detail.php?id=' . $row['id'] . '&category=wisata" class="btn">Lihat Detail</a>
                </div>
            </div>';
            }
        } else {
            echo "<p>Tidak ada wisata yang tersedia.</p>";
        }
        ?>
    </section>
    <section class="wisata-list">
        <div class="card">
            <h2>Rekomendasi Kuliner</h2>
        </div>
    </section>
    <section class="wisata-list">
        <?php
        if ($result_kuliner->num_rows > 0) {
            while ($row = $result_kuliner->fetch_assoc()) {
                $kuliner_id = $row['id'];
                $query_rating = "SELECT AVG(rating) AS avg_rating FROM rating WHERE id_item = $kuliner_id AND kategori = 'kuliner'";
                $result_rating = $conn->query($query_rating);
                $avg_rating = 0;
                if ($result_rating->num_rows > 0) {
                    $rating_data = $result_rating->fetch_assoc();
                    $avg_rating = round($rating_data['avg_rating'], 1);
                }
                echo '
            <div class="card">
                <img src="' . $row['foto_utama'] . '" alt="' . htmlspecialchars($row['nama_kuliner']) . '">
                <div class="card-body">
                    <h3>' . htmlspecialchars($row['nama_kuliner']) . '</h3>
                    <p>' . htmlspecialchars(substr($row['deskripsi'], 0, 100)) . '...</p>
                    <div class="rating">' . renderStars($avg_rating) . ' (' . $avg_rating . ')</div>
                    <a href="detail.php?id=' . $row['id'] . '&category=kuliner" class="btn">Lihat Detail</a>
                </div>
            </div>';
            }
        } else {
            echo "<p>Tidak ada kuliner yang tersedia.</p>";
        }
        ?>
    </section>

    <section class="wisata-list">
        <div class="card">
            <h2>Rekomendasi Oleh-Oleh</h2>
        </div>
    </section>

    <section class="wisata-list">
        <?php
        if ($result_oleholeh->num_rows > 0) {
            while ($row = $result_oleholeh->fetch_assoc()) {
                $oleholeh_id = $row['id'];
                $query_rating = "SELECT AVG(rating) AS avg_rating FROM rating WHERE id_item = $oleholeh_id AND kategori = 'oleh_oleh'";
                $result_rating = $conn->query($query_rating);
                $avg_rating = 0;
                if ($result_rating->num_rows > 0) {
                    $rating_data = $result_rating->fetch_assoc();
                    $avg_rating = round($rating_data['avg_rating'], 1);
                }
                echo '
            <div class="card">
                <img src="' . $row['foto_utama'] . '" alt="' . htmlspecialchars($row['nama_oleh_oleh']) . '">
                <div class="card-body">
                    <h3>' . htmlspecialchars($row['nama_oleh_oleh']) . '</h3>
                    <p>' . htmlspecialchars(substr($row['deskripsi'], 0, 100)) . '...</p>
                    <div class="rating">' . renderStars($avg_rating) . ' (' . $avg_rating . ')</div>
                    <a href="detail.php?id=' . $row['id'] . '&category=oleh_oleh" class="btn">Lihat Detail</a>
                </div>
            </div>';
            }
        } else {
            echo "<p>Tidak ada oleh-oleh yang tersedia.</p>";
        }
        ?>
    </section>

    <?php
    // Fungsi untuk menampilkan rating dalam bentuk bintang
    function renderStars($rating)
    {
        $stars = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $rating) {
                $stars .= '<span class="star filled">&#9733;</span>'; // Bintang penuh
            } elseif ($i - 0.5 <= $rating) {
                $stars .= '<span class="star half">&#9733;</span>'; // Bintang setengah
            } else {
                $stars .= '<span class="star">&#9733;</span>'; // Bintang kosong
            }
        }
        return $stars;
    }
    ?>

    <!-- Footer -->
    <footer class="main-footer">
        <div class="footer-content">
            <div class="footer-logo">
                <img src="asset/Logo.png" alt="Logo Pesona Tegal" />
                <p>Pesona Tegal</p>
                <p>Jl. Prof. Dr. Suharso No.45, Mangunjaya, Tegal</p>
            </div>
            <div class="footer-links">
                <h4>Kategori</h4>
                <ul class="footer-category">
                    <ul class="category-item">
                        <a href="beranda.php" class="category-link">Beranda</a>
                    </ul>
                    <ul class="category-item">
                        <a class="category-link">Wisata</a>
                        <ul class="sub-category">
                            <li><a href="wisata-alam.php">Wisata Alam</a></li>
                            <li><a href="wisata-taman.php">Wisata Taman</a></li>
                            <li><a href="wisata-sejarah.php">Wisata Sejarah</a></li>
                        </ul>
                    </ul>
                    <ul class="category-item">
                        <a class="category-link">Kuliner</a>
                        <ul class="sub-category">
                            <li><a href="kuliner-cafe.php">Cafe</a></li>
                            <li><a href="kuliner-rumahmakan.php">Rumah Makan</a></li>
                        </ul>
                    </ul>
                    <ul class="category-item">
                        <a class="category-link">Oleh-oleh</a>
                        <ul class="sub-category">
                            <li><a href="oleh-makanan.php">Makanan</a></li>
                            <li><a href="oleh-barang.php">Barang</a></li>
                        </ul>
                    </ul>
                </ul>
            </div>
            <div class="footer-socials">
                <h4>Ikuti Kami</h4>
                <a href="https://instagram.com/youraccount" target="_blank">
                    <img src="asset/Instagram.png" alt="Instagram Pesona Tegal" />
                </a>
                <a href="https://tiktok.com/@youraccount" target="_blank">
                    <img src="asset/TikTok.png" alt="TikTok Pesona Tegal" />
                </a>
            </div>
        </div>
    </footer>

    <!-- Modal Login -->
    <?php include 'login-modal.php'; ?>
    <!-- Modal Registrasi -->
    <?php include 'registrasi-modal.php'; ?>

    <script src="javascript.js" defer></script>

</body>

</html>
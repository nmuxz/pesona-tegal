<?php
session_start();

include 'config.php';

if (isset($_SESSION['error_message'])) {
    echo "<script>alert('" . $_SESSION['error_message'] . "');</script>";
    unset($_SESSION['error_message']);  // Menghapus pesan setelah ditampilkan
}

// Cek apakah pengguna sudah login
$isLoggedIn = isset($_SESSION['email']); // 'user_id' di-set saat login berhasil

// Query untuk mengambil data 
$query = "SELECT * FROM wisata WHERE subkategori = 'Wisata Taman'";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wisata Alam</title>
    <link rel="stylesheet" href="wisata-taman.css"> <!-- Link ke file CSS -->
</head>

<body data-logged-in="<?= $isLoggedIn ? 'true' : 'false' ?>">
    <!-- Header -->
    <header class="main-header">
        <nav class="navbar">
            <img src="asset/Logo.png" alt="Logo Pesona Tegal" class="logo">
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
    <!-- Background dan Judul -->
    <section class="hero">
        <div class="container">
            <h1>WISATA TAMAN</h1>
        </div>
    </section>

    <!-- Menampilkan Data Wisata Alam-->
    <section class="wisata-list">
        <?php
        // Menampilkan data wisata alam dalam format kartu
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Menghitung rata-rata rating untuk setiap wisata
                $wisata_id = $row['id'];
                $query_rating = "SELECT AVG(rating) AS avg_rating FROM rating WHERE id_item = $wisata_id AND kategori = 'wisata'";
                $result_rating = $conn->query($query_rating);
                $avg_rating = 0;
                if ($result_rating->num_rows > 0) {
                    $rating_data = $result_rating->fetch_assoc();
                    $avg_rating = round($rating_data['avg_rating'], 1); // Dibulatkan ke 1 desimal
                }

                // Menampilkan kartu wisata dengan rating
                echo '
                <div class="card">
                    <img src="' . $row['foto_utama'] . '" alt="' . $row['nama_wisata'] . '">
                    <div class="card-body">
                        <h3>' . $row['nama_wisata'] . '</h3>
                        <p>' . substr($row['deskripsi'], 0, 100) . '...</p> <!-- Menampilkan 100 karakter pertama -->
                        <div class="rating">' . renderStars($avg_rating) . ' (' . $avg_rating . ')</div>
                        <a href="detail.php?id=' . $row['id'] . '&category=wisata" class="btn">Lihat Detail</a>
                    </div>
                </div>';
            }
        } else {
            echo "<p>Tidak ada wisata taman yang tersedia.</p>";
        }

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
    </section>

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
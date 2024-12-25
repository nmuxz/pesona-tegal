<?php
session_start();

include 'config.php';

if (isset($_SESSION['error_message'])) {
    echo "<script>alert('" . $_SESSION['error_message'] . "');</script>";
    unset($_SESSION['error_message']); // Menghapus pesan setelah ditampilkan
}

// Cek apakah pengguna sudah login
$isLoggedIn = isset($_SESSION['email']);

// Ambil parameter ID dan kategori dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$category = isset($_GET['category']) ? $_GET['category'] : '';
$id_item = isset($_GET['id_item']) ? intval($_GET['id_item']) : $id; // Gunakan $id jika $id_item tidak ada

// Validasi kategori
$valid_categories = ['wisata', 'kuliner', 'oleh_oleh'];
if (!in_array($category, $valid_categories)) {
    echo "<script>console.error('Kategori tidak valid: " . htmlspecialchars($category) . "');</script>";
    die('Kategori tidak valid.');
}

// Ambil data detail dari database, termasuk latitude dan longitude
$query = "SELECT *, latitude, longitude FROM $category WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
if (!$data) {
    die('Data tidak ditemukan.');
}

// Variabel untuk Google Maps
$latitude = $data['latitude'];
$longitude = $data['longitude'];
$googleMapsLink = "https://www.google.com/maps?q=$latitude,$longitude";

// Ambil ulasan terkait
$query_reviews = "SELECT r.rating, r.komentar, u.name FROM rating r 
                  JOIN users u ON r.id_user = u.id 
                  WHERE r.id_item = ? AND r.kategori = ?";
$stmt_reviews = $conn->prepare($query_reviews);
$stmt_reviews->bind_param('is', $id_item, $category);
$stmt_reviews->execute();
$reviews = $stmt_reviews->get_result();

// Tambahkan ulasan baru
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['komentar'], $_POST['rating'])) {
    if (isset($_SESSION['id'])) {
        $id_user = $_SESSION['id']; // Ambil id_user dari session
        $komentar = htmlspecialchars($_POST['komentar']);
        $rating = intval($_POST['rating']);

        $query_insert = "INSERT INTO rating (id_item, kategori, id_user, rating, komentar, created_at) 
                         VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt_insert = $conn->prepare($query_insert);
        $stmt_insert->bind_param('isiss', $id_item, $category, $id_user, $rating, $komentar);

        if ($stmt_insert->execute()) {
            header("Location: detail.php?id=$id&category=$category");
            exit;
        } else {
            echo "Gagal menambahkan ulasan.";
        }
    } else {
        echo "Harap login untuk memberikan ulasan.";
    }
}

// Ambil data galeri terkait
$query_gallery = "SELECT * FROM galeri WHERE id_kategori = ? AND kategori = ? AND status = 'aktif'";
$stmt_gallery = $conn->prepare($query_gallery);
$stmt_gallery->bind_param('is', $id, $category);
$stmt_gallery->execute();
$gallery = $stmt_gallery->get_result();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wisata Alam</title>
    <link rel="stylesheet" href="detail.css"> <!-- Link ke file CSS -->
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


    <section class="detail">
        <div class="detail-header">
            <img src="<?= htmlspecialchars($data['foto_utama']) ?>" alt="Foto <?= htmlspecialchars($data['nama_' . $category]) ?>">
            <div class="detail-overlay">
                <h1><?= htmlspecialchars($data['nama_' . $category]) ?></h1>
            </div>
        </div>
        <div id="galeri" class="content">
            <h2>Galeri</h2>
            <?php if (isset($gallery) && $gallery instanceof mysqli_result && $gallery->num_rows > 0): ?>
                <div class="gallery-grid">
                    <?php while ($photo = $gallery->fetch_assoc()): ?>
                        <?php
                        // Validasi data foto
                        $fotoSrc = htmlspecialchars($photo['foto']);
                        $altText = !empty($photo['alt']) ? htmlspecialchars($photo['alt']) : 'Foto Galeri';
                        ?>
                        <div class="gallery-item">
                            <img src="<?= $fotoSrc ?>" alt="<?= $altText ?>" class="gallery-img">
                        </div>
                    <?php endwhile; ?>
                </div>

                <!-- Modal Lightbox -->
                <div id="lightbox" class="lightbox">
                    <span class="close">x</span>
                    <img id="lightbox-image" src="" alt="Lightbox Image">
                </div>
            <?php else: ?>
                <p>Tidak ada foto di galeri.</p>
            <?php endif; ?>
        </div>



        <div id="deskripsi" class="content">
            <a href="<?= $googleMapsLink ?>" class="btn-maps" target="_blank">Lihat Lokasi</a>
            <h2>Deskripsi</h2>
            <p><?= nl2br(htmlspecialchars($data['deskripsi'])) ?></p>
        </div>

        <div id="ulasan" class="content">
            <h2>Ulasan</h2>
            <div class="ulasan-horizontal">
                <?php while ($review = $reviews->fetch_assoc()): ?>
                    <div class="ulasan-card">
                        <div class="rating-box">
                            <span class="rating-value"><?= htmlspecialchars($review['rating']) ?> / 5</span>
                        </div>
                        <div class="ulasan-content">
                            <strong class="name"><?= htmlspecialchars($review['name']) ?></strong>
                            <p class="komentar"><?= htmlspecialchars($review['komentar']) ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <?php if (isset($_SESSION['email'])): ?>
                <form method="POST" class="form-ulasan">
                    <label for="rating">Rating:</label>
                    <input type="number" name="rating" id="rating" min="1" max="5" step="0.1" placeholder="1-5" required>

                    <label for="komentar">Komentar:</label>
                    <textarea name="komentar" id="komentar" required></textarea>

                    <button type="submit">Kirim Ulasan</button>
                </form>
            <?php else: ?>
                <p>Harap <a href="#">login</a> untuk memberikan ulasan.</p>
            <?php endif; ?>
        </div>

    </section>
    <div id="lightbox" class="lightbox">
        <span class="close">&times;</span>
        <img class="lightbox-content" id="lightbox-image" src="" alt="Full Foto">
    </div>
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
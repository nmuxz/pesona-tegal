<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data</title>
    <link rel="stylesheet" href="admin_add.css">
    <script>
        function updateSubkategori() {
            const kategori = document.getElementById('kategori').value;
            const subkategori = document.getElementById('subkategori');
            subkategori.innerHTML = ''; // Clear current options

            let options = [];

            if (kategori === 'wisata') {
                options = ['Wisata Alam', 'Wisata Taman', 'Wisata Sejarah'];
            } else if (kategori === 'kuliner') {
                options = ['Cafe', 'Rumah Makan'];
            } else if (kategori === 'oleh_oleh') {
                options = ['Barang', 'Makanan'];
            }

            options.forEach(option => {
                const opt = document.createElement('option');
                opt.value = option.toLowerCase().replace(' ', '_');
                opt.textContent = option;
                subkategori.appendChild(opt);
            });

            console.log(`Kategori terpilih: ${kategori}`);
            console.log(`Subkategori pertama: ${subkategori.options[0]?.value || 'Tidak ada subkategori'}`);
        }

        window.onload = updateSubkategori; // Update subkategori on page load
    </script>
</head>
<body>
    <div class="container">
        <h1>Tambah Data</h1>
        <form action="admin_add_process.php" method="POST" enctype="multipart/form-data">
            <label for="kategori">Kategori:</label>
            <select name="kategori" id="kategori" required onchange="updateSubkategori()">
                <option value="wisata">Wisata</option>
                <option value="kuliner">Kuliner</option>
                <option value="oleh_oleh">Oleh-oleh</option>
            </select>

            <label for="subkategori">Subkategori:</label>
            <select name="subkategori" id="subkategori">
                <!-- Subkategori options will be populated by JavaScript -->
            </select>

            <label for="nama">Nama:</label>
            <input type="text" name="nama" id="nama" required>

            <label for="deskripsi">Deskripsi:</label>
            <textarea name="deskripsi" id="deskripsi" required></textarea>

            <label for="foto_utama">Foto Utama:</label>
            <input type="file" name="foto_utama" id="foto_utama" accept="image/*" required>

            <label for="rating">Rating:</label>
            <input type="number" name="rating" id="rating" min="1" max="5" step="0.1" placeholder="1-5" required>

            <!-- Input untuk Latitude -->
            <label for="latitude">Garis Lintang (Latitude):</label>
            <input type="text" name="latitude" id="latitude" placeholder="Contoh: -6.8748" required>

            <!-- Input untuk Longitude -->
            <label for="longitude">Garis Bujur (Longitude):</label>
            <input type="text" name="longitude" id="longitude" placeholder="Contoh: 109.1234" required>

            <button type="submit">Tambah</button>
        </form>
    </div>
</body>
</html>
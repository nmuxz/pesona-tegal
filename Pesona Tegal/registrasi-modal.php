<!-- Bagian Registrasi -->
<div id="registration-modal" class="modal">
        <div class="modal-content">
            <span class="close-btn" id="close-register">×</span>
            <div class="form-header">
                <img src="asset/Logo.png" alt="Pesona Tegal Logo" class="logo-registrasi">
                <h2>Registrasi</h2>
                <p>Silahkan buat akun dengan mengisi data diri anda</p>
            </div>
            <form id="registration-form" action="register.php" method="POST">
                <label for="name">Nama</label>
                <input type="text" id="nama" name="name" placeholder="Masukkan Nama Kamu" required>

                <label for="origin">Asal</label>
                <input type="text" id="asal" name="origin" placeholder="Masukkan Asal Kamu" required>

                <label for="email">Email</label>
                <input type="email" id="gmail" name="email" placeholder="Masukkan Email Kamu" required>

                <label for="password">Password</label>
                <input type="password" id="pass" name="password" placeholder="Masukkan Password" required>

                <label for="confirm-password">Konfirmasi Password</label>
                <input type="password" id="confirm-pass" name="confirm-password" placeholder="Konfirmasi Password"
                    required>

                <button type="submit">Daftar</button>
                <p>Sudah punya akun? <a href="#" id="open-login">Masuk</a></p>
            </form>
        </div>
    </div>
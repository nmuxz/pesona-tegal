<!-- Popup Modal for Login -->
<div id="login-modal" class="modal">
    <div class="modal-content">
        <span class="close-btn" id="close-login">×</span>
        <div class="form-header">
            <img src="asset/Logo.png" alt="Pesona Tegal Logo" class="logo-registrasi">
            <h2>Selamat Datang!</h2>
            <p>Silahkan login terlebih dahulu</p>
        </div>
        <form id="login-popup-form" action="login.php" method="POST">
            <label for="email">Email</label>
            <input type="email" id="popup-email" name="email" placeholder="Masukkan Email Kamu" required>
            <label for="password">Password</label>
            <input type="password" id="popup-password" name="password" placeholder="Masukkan Password Kamu"
                required>
            <label>
                <input type="checkbox" id="popup-remember-me" name="remember-me"> Ingat saya
            </label>
            <button type="submit">Masuk</button>

            <p>Belum punya akun? <a href="#" id="open-register-from-login">Buat Akun</a></p>
            <p><a href="#">Lupa Password</a></p>
        </form>
    </div>
</div>
let users = [];

function showRegister() {
    document.getElementById("loginForm").style.display = "none";
    document.getElementById("registerForm").style.display = "flex";
}

function showLogin() {
    document.getElementById("registerForm").style.display = "none";
    document.getElementById("loginForm").style.display = "flex";
}

function register() {
    const nama = document.getElementById("registerNama").value;
    const asal = document.getElementById("registerAsal").value;
    const email = document.getElementById("registerEmail").value;
    const password = document.getElementById("registerPassword").value;
    const confirmPassword = document.getElementById("registerConfirmPassword").value;

    if (password !== confirmPassword) {
        alert("Password tidak cocok!");
        return;
    }

    if (nama && asal && email && password) {
        users.push({ nama, asal, email, password });
        alert("Registrasi berhasil!");
        showLogin();
    } else {
        alert("Isi semua data dengan benar!");
    }
}

function login() {
    const email = document.getElementById("loginEmail").value;
    const password = document.getElementById("loginPassword").value;

    const user = users.find(user => user.email === email && user.password === password);

    if (user) {
        alert("Login berhasil!");
        // Arahkan ke halaman berikutnya jika diperlukan
    } else {
        alert("Email atau password salah!");
    }
}

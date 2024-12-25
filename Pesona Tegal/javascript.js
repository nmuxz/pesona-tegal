document.addEventListener('DOMContentLoaded', function () {
    // === DROPDOWN MENU ===
    document.querySelectorAll('.dropdown').forEach((dropdown) => {
        dropdown.addEventListener('mouseover', () => {
            const menu = dropdown.querySelector('.dropdown-menu');
            if (menu) menu.style.display = 'block';
        });

        dropdown.addEventListener('mouseout', () => {
            const menu = dropdown.querySelector('.dropdown-menu');
            if (menu) menu.style.display = 'none';
        });
    });

    // === SLIDER FUNCTIONALITY ===
    function setupSlider(sliderContainer) {
        const prevButton = sliderContainer.querySelector('.prev');
        const nextButton = sliderContainer.querySelector('.next');
        const slides = sliderContainer.querySelector('.slides');

        if (prevButton && nextButton && slides) {
            const scrollStep = 200;

            prevButton.addEventListener('click', () => {
                slides.scrollBy({
                    left: -scrollStep,
                    behavior: 'smooth',
                });
            });

            nextButton.addEventListener('click', () => {
                slides.scrollBy({
                    left: scrollStep,
                    behavior: 'smooth',
                });
            });
        }
    }

    document.querySelectorAll('.slider').forEach((slider) => {
        setupSlider(slider);
    });



    // === SEARCH BAR FUNCTIONALITY ===
    const searchBar = document.querySelector('.search-bar input');
    if (searchBar) {
        searchBar.addEventListener('input', (e) => {
            const searchQuery = e.target.value.toLowerCase();
            document.querySelectorAll('.recommendation-cards .card').forEach((card) => {
                const cardTitle = card.querySelector('h3').textContent.toLowerCase();
                card.style.display = cardTitle.includes(searchQuery) ? 'block' : 'none';
            });
        });
    }



    // === MODAL HANDLING ===
    // Ambil elemen tombol dan modal
    const btnMasuk = document.getElementById('btn-masuk');
    const btnDaftar = document.getElementById('btn-daftar');
    const btnKeluar = document.getElementById('btn-keluar');
    const loginModal = document.getElementById('login-modal');
    const registerModal = document.getElementById('registration-modal');
    const closeLogin = document.getElementById('close-login');
    const closeRegister = document.getElementById('close-register');
    const openRegisterFromLogin = document.getElementById('open-register-from-login');
    const openLoginFromRegister = document.getElementById('open-login');
    const btnDaftarSekarang = document.getElementById('open-register-btn');
    // Cek apakah user sudah login berdasarkan atribut `data-is-logged-in` pada body
    const isLoggedIn = document.body.dataset.LoggedIn === 'true';

    // Fungsi untuk mengatur visibilitas modal
    function toggleModal(modal, isVisible) {
        if (modal) modal.style.display = isVisible ? 'flex' : 'none';
    }


    if (btnDaftarSekarang && registerModal) {
        btnDaftarSekarang.addEventListener('click', () => {
            toggleModal(loginModal, false); // Pastikan modal login ditutup
            toggleModal(registerModal, true); // Tampilkan modal registrasi
        });
    }
    // Fungsi untuk mengatur visibilitas tombol berdasarkan status login
    function updateButtonVisibility() {
        if (btnMasuk && btnDaftar && btnKeluar) { // Pastikan semua elemen ada
            if (isLoggedIn) {
                // Jika user sudah login
                btnMasuk.style.display = 'none'; // Sembunyikan tombol Masuk
                btnDaftar.style.display = 'none'; // Sembunyikan tombol Daftar
                btnKeluar.style.display = 'block'; // Tampilkan tombol Keluar
            } else {
                // Jika user belum login
                btnMasuk.style.display = 'block'; // Tampilkan tombol Masuk
                btnDaftar.style.display = 'block'; // Tampilkan tombol Daftar
                btnKeluar.style.display = 'none'; // Sembunyikan tombol Keluar
            }
        } else {

        }
    }

    // Panggil fungsi untuk mengatur visibilitas tombol saat halaman dimuat
    updateButtonVisibility();

    // Event listener untuk tombol Masuk
    btnMasuk?.addEventListener('click', () => {
        toggleModal(registerModal, false); // Pastikan modal register tertutup
        toggleModal(loginModal, true); // Tampilkan modal login
    });

    // Event listener untuk tombol Daftar
    btnDaftar?.addEventListener('click', () => {
        toggleModal(loginModal, false); // Pastikan modal login tertutup
        toggleModal(registerModal, true); // Tampilkan modal register
    });

    // Event listener untuk tombol X di modal login
    closeLogin?.addEventListener('click', () => toggleModal(loginModal, false));

    // Event listener untuk tombol X di modal register
    closeRegister?.addEventListener('click', () => toggleModal(registerModal, false));

    // Event listener untuk menutup modal jika klik di luar modal
    window.addEventListener('click', (event) => {
        if (event.target === loginModal) toggleModal(loginModal, false);
        if (event.target === registerModal) toggleModal(registerModal, false);
    });

    // Event listener untuk peralihan dari modal login ke register
    openRegisterFromLogin?.addEventListener('click', () => {
        toggleModal(loginModal, false);
        toggleModal(registerModal, true);
    });

    // Event listener untuk peralihan dari modal register ke login
    openLoginFromRegister?.addEventListener('click', () => {
        toggleModal(registerModal, false);
        toggleModal(loginModal, true);
    });

    // Event listener untuk tombol Keluar
    if (btnKeluar) {
        btnKeluar.addEventListener('click', () => {
            window.location.href = 'logout.php'; // Arahkan ke halaman logout
        });
    }

    // Mengatur zoom foto galeri
    const lightbox = document.getElementById("lightbox");
    const lightboxImage = document.getElementById("lightbox-image");
    const closeBtn = document.querySelector(".lightbox .close");

    if (lightbox && lightboxImage) {
        document.querySelectorAll(".gallery-item img").forEach(img => {
            img.addEventListener("click", () => {
                lightboxImage.src = img.src;
                lightbox.style.display = "block";
            });
        });

        lightbox.addEventListener("click", (e) => {
            if (!lightboxImage.contains(e.target)) {
                lightbox.style.display = "none";
            }
        });
    }

    if (closeBtn) {
        closeBtn.addEventListener("click", () => {
            lightbox.style.display = "none";
        });
    }

    // Mengatur hamburger
    const hamburger = document.querySelector('.hamburger');
    const menu = document.querySelector('.menu');
    const categories = document.querySelectorAll('.category');

    if (hamburger && menu) {
        hamburger.addEventListener('click', (event) => {
            event.stopPropagation(); // Mencegah klik pada hamburger menutup menu
            menu.classList.toggle('active');
        });

        document.addEventListener('click', (event) => {
            if (!menu.contains(event.target) && !hamburger.contains(event.target) && menu.classList.contains('active')) {
                menu.classList.remove('active');
            }
        });
    }

    if (categories) {
        categories.forEach(category => {
            category.addEventListener('click', () => {
                category.classList.toggle('open');
            });
        });
    }


});

// Modal Konfirmasi Penghapusan
document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', function (event) {
        const confirmation = confirm("Apakah Anda yakin ingin menghapus data ini?");
        if (!confirmation) {
            event.preventDefault();
        }
    });
});

// Validasi Formulir Tambah/Edit
function validateForm(event) {
    const requiredFields = document.querySelectorAll('[required]');
    let isValid = true;

    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            isValid = false;
            field.style.border = "2px solid red";
        } else {
            field.style.border = "";
        }
    });

    if (!isValid) {
        alert("Mohon lengkapi semua bidang yang wajib diisi.");
        event.preventDefault();
    }
}

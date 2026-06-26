document.addEventListener('DOMContentLoaded', function () {
    // Sidebar toggle for mobile/responsive
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebar = document.querySelector('.sidebar');
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('show');
        });
    }

    // Auto format ribuan saat mengetik di input nominal
    document.querySelectorAll('.input-nominal').forEach(el => {
        el.addEventListener('input', function () {
            let v = this.value.replace(/\D/g, '');
            this.value = v ? parseInt(v).toLocaleString('id-ID') : '';
            const hiddenTarget = document.getElementById(this.dataset.target);
            if (hiddenTarget) {
                hiddenTarget.value = v;
            }
        });
    });

    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(el => {
        el.addEventListener('click', function () {
            const input = document.querySelector(this.dataset.target);
            if (input) {
                if (input.type === 'password') {
                    input.type = 'text';
                    this.innerHTML = '<i class="fas fa-eye-slash"></i>';
                } else {
                    input.type = 'password';
                    this.innerHTML = '<i class="fas fa-eye"></i>';
                }
            }
        });
    });

    // Auto calculation jumlah bersih produksi
    const inputProduksi = document.querySelector('input[name="jumlah_produksi"]');
    const inputGagal    = document.querySelector('input[name="jumlah_gagal"]');
    const displayBersih = document.getElementById('jumlah_bersih_display');

    function hitungBersih() {
        if (inputProduksi && displayBersih) {
            const prod = parseInt(inputProduksi.value) || 0;
            const gag  = (inputGagal && parseInt(inputGagal.value)) || 0;
            const bers = Math.max(0, prod - gag);
            displayBersih.value = bers + ' unit';
        }
    }

    if (inputProduksi) inputProduksi.addEventListener('input', hitungBersih);
    if (inputGagal)    inputGagal.addEventListener('input', hitungBersih);

    // Auto-fill satuan dari dropdown produk
    const selectProduk = document.getElementById('produk_select');
    const inputSatuan  = document.getElementById('satuan_display');
    if (selectProduk && inputSatuan) {
        selectProduk.addEventListener('change', function () {
            const selectedOpt = this.options[this.selectedIndex];
            const satuan = selectedOpt ? selectedOpt.dataset.satuan : '';
            inputSatuan.value = satuan || '-';
        });
    }
});

// Helper functions triggered by onclick in blade
function verifikasiForm(formId) {
    SwalHelper.confirmVerify(() => {
        document.getElementById(formId).submit();
    });
}

function hapusForm(formId) {
    SwalHelper.confirmDelete(() => {
        document.getElementById(formId).submit();
    });
}

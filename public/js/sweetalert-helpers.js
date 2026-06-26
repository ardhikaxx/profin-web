const SwalHelper = {
    success: (message, callback) => {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: message,
            confirmButtonColor: '#198754',
            timer: 2500,
            timerProgressBar: true,
        }).then(() => { if (callback) callback(); });
    },

    error: (message) => {
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: message,
            confirmButtonColor: '#dc3545',
        });
    },

    warning: (message) => {
        Swal.fire({
            icon: 'warning',
            title: 'Perhatian!',
            text: message,
            confirmButtonColor: '#ffc107',
        });
    },

    confirmDelete: (callback) => {
        Swal.fire({
            icon: 'warning',
            title: 'Konfirmasi Hapus',
            text: 'Data yang dihapus tidak dapat dikembalikan. Lanjutkan?',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-trash me-1"></i> Ya, Hapus',
            cancelButtonText: 'Batal',
        }).then((result) => { if (result.isConfirmed && callback) callback(); });
    },

    confirmLogout: (formId) => {
        Swal.fire({
            icon: 'question',
            title: 'Konfirmasi Logout',
            text: 'Apakah Anda yakin ingin keluar dari sistem?',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-sign-out-alt me-1"></i> Ya, Logout',
            cancelButtonText: 'Batal',
        }).then((result) => { if (result.isConfirmed) document.getElementById(formId).submit(); });
    },

    confirmVerify: (callback) => {
        Swal.fire({
            icon: 'question',
            title: 'Konfirmasi Verifikasi',
            text: 'Data yang telah diverifikasi tidak dapat diubah oleh karyawan. Lanjutkan?',
            showCancelButton: true,
            confirmButtonColor: '#1B6B3A',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-check-circle me-1"></i> Ya, Verifikasi',
            cancelButtonText: 'Batal',
        }).then((result) => { if (result.isConfirmed && callback) callback(); });
    },

    flashFromSession: () => {
        const success = document.querySelector('meta[name="flash-success"]')?.content;
        const error   = document.querySelector('meta[name="flash-error"]')?.content;
        if (success) SwalHelper.success(success);
        if (error)   SwalHelper.error(error);
    }
};

document.addEventListener('DOMContentLoaded', SwalHelper.flashFromSession);

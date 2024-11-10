// public/js/user.js

// Function untuk menampilkan modal konfirmasi delete
function deleteUser(id) {
    deleteId = id;
    $('#deleteModal').modal('show');
}

// Handle delete confirmation
$('#confirmDelete').click(function() {
    if (deleteId) {
        $.ajax({
            url: `/user/${deleteId}`,
            type: 'DELETE',
            data: {
                "_token": csrfToken // Using global CSRF token variable
            },
            success: function(response) {
                if (response.success) {
                    // Refresh DataTable
                    table.ajax.reload();

                    // Tampilkan pesan sukses
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
                $('#deleteModal').modal('hide');
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Terjadi kesalahan saat menghapus data!',
                });
                $('#deleteModal').modal('hide');
            }
        });
    }
});

// Reset deleteId ketika modal ditutup
$('#deleteModal').on('hidden.bs.modal', function() {
    deleteId = null;
});


function updateUsernameFields() {
    let kecamatanId = $('#kecamatan_id').val();
    let kelurahanId = $('#kelurahan_id').val();
    let tpsId = $('#tps_id').val();

    // Jika tidak ada pilihan wilayah (role kota)
    if (!kecamatanId && !kelurahanId && !tpsId) {
        $('#username_auto_group').hide();
        $('#username_manual_group').show();
    } else {
        $('#username_auto_group').show();
        $('#username_manual_group').hide();
        updateUsernamePreview();
    }
}

function updateUsernamePreview() {
    let kecamatanId = $('#kecamatan_id').val();
    let kelurahanId = $('#kelurahan_id').val();
    let tpsId = $('#tps_id').val();
    let username = '';

    if (kecamatanId && !kelurahanId && !tpsId) {
        // User kecamatan
        let kecamatanNama = $('#kecamatan_id option:selected').data('nama');
        username = kecamatanNama ? kecamatanNama.toLowerCase().replace(/\s+/g, '_') : '';
    }
    else if (kecamatanId && kelurahanId) {
        // User kelurahan atau TPS
        let kecamatanKode = $('#kecamatan_id option:selected').data('kode');
        let kelurahanKode = $('#kelurahan_id option:selected').data('kode');

        username = kecamatanKode + '.' + kelurahanKode;

        if (tpsId) {
            let tpsNo = $('#tps_id option:selected').text();
            if (tpsNo && tpsNo !== 'Pilih TPS') {
                username += '.' + tpsNo;
            }
        }
    }

    $('#username_preview').val(username);
}

// Ketika Kecamatan dipilih
$('#kecamatan_id').change(function() {
    let kecamatanId = $(this).val();  // Ambil ID Kecamatan yang dipilih

    // Reset dropdown Kelurahan dan TPS
    $('#kelurahan_id').html('<option value="">Pilih Kelurahan</option>');
    $('#tps_id').html('<option value="">Pilih TPS</option>');

    updateUsernameFields();

    // Jika Kecamatan dipilih, ambil data Kelurahan yang terkait
    if (kecamatanId) {
        $.get('/kelurahan/by-kecamatan/' + kecamatanId, function(data) {
            // Iterasi data Kelurahan yang diterima dari server
            $.each(data, function(index, kelurahan) {
                // Tambahkan option Kelurahan ke dropdown dengan data-kode
                let option = new Option(kelurahan.nama_kelurahan, kelurahan.id);
                $(option).attr('data-kode', kelurahan.kode_kelurahan);
                $('#kelurahan_id').append(option);
            });
        });
    }
});

// Ketika Kelurahan dipilih
$('#kelurahan_id').change(function() {
    let kelurahanId = $(this).val();  // Ambil ID Kelurahan yang dipilih

    // Reset dropdown TPS
    $('#tps_id').html('<option value="">Pilih TPS</option>');

    updateUsernameFields();

    // Jika Kelurahan dipilih, ambil data TPS yang terkait
    if (kelurahanId) {
        $.get('/tps/by-kelurahan/' + kelurahanId, function(data) {
            // Periksa format data dan tampilkan dengan benar
            $.each(data, function(index, tps) {
                // Pastikan tps adalah objek dengan atribut yang sesuai
                if (tps.no_tps && tps.id) {
                    $('#tps_id').append(new Option(tps.no_tps, tps.id)); // Menambahkan no_tps dan id
                }
            });
        });
    }
});

// Ketika TPS dipilih
$('#tps_id').change(function() {
    updateUsernameFields();
});

// Update username fields saat halaman dimuat
$(document).ready(function() {
    updateUsernameFields();
});


// Function to show delete confirmation modal
function deleteKelurahan(id) {
    deleteId = id;
    $('#deleteModal').modal('show');
}

// Handle delete confirmation
$('#confirmDelete').click(function() {
    if (deleteId) {
        $.ajax({
            url: `/kelurahan/${deleteId}`,
            type: 'DELETE',
            data: {
                "_token": csrfToken // Use a global variable or data attribute for csrf token
            },
            success: function(response) {
                if (response.success) {
                    // Refresh the DataTable
                    table.ajax.reload();
                    // Show success message
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
            error: function(xhr) {
                // Show error message
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

// Reset deleteId when modal is closed
$('#deleteModal').on('hidden.bs.modal', function() {
    deleteId = null;
});

// Show import error modal if csv import failed
if (csvImportError) {
    $('#importModal').modal('show');
}

// Show success message if import was successful
if (importSuccessMessage) {
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: importSuccessMessage,
        showConfirmButton: false,
        timer: 1500
    });
}

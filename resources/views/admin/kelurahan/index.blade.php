@extends('layouts.app')

@section('title', 'Data Kelurahan')

@push('style')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Data Kelurahan</h1>
        </div>

        <div class="section-body">
            <h2 class="section-title">Tabel Data Kelurahan</h2>
            <p class="section-lead">
                Halaman ini menampilkan data Kelurahan yang diambil dari server menggunakan server-side processing.
                Anda dapat menambahkan, mengedit, atau menghapus data Kelurahan di sini.
            </p>
            <a href="{{ route('kelurahan.create') }}" class="btn btn-warning mb-3">Tambah Kelurahan</a>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ $errors->first() }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h4>Daftar Kelurahan</h4>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="kelurahanTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Kode Kecamatan</th>
                                        <th>Nama Kecamatan</th>
                                        <th>Nama Kelurahan</th>
                                        <th>Kode Kelurahan</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data akan dimuat melalui server-side processing menggunakan DataTables -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal for delete confirmation -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus kelurahan ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Hapus</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>

    <script>
        let table;
        let deleteId = null;

        $(document).ready(function() {
            table = $('#kelurahanTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('kelurahan.index') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'kode_kecamatan',
                        name: 'kecamatan.kode_kecamatan'
                    },

                    {
                        data: 'nama_kecamatan',
                        name: 'kecamatan.nama_kecamatan'
                    },
                    {
                        data: 'nama_kelurahan',
                        name: 'nama_kelurahan'
                    },
                    {
                        data: 'kode_kelurahan',
                        name: 'kode_kelurahan'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                pageLength: 10,
                lengthMenu: [10, 25, 50, 100],
                drawCallback: function() {
                    $('#kelurahanTable .dropdown-toggle').dropdown();
                }
            });
        });

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
                        "_token": "{{ csrf_token() }}"
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
    </script>
@endpush

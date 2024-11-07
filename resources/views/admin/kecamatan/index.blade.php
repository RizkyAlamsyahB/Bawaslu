@extends('layouts.app')

@section('title', 'Data Kecamatan')

@push('style')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Data Kecamatan</h1>
        </div>

        <div class="section-body">
            <h2 class="section-title">Tabel Data Kecamatan</h2>
            <p class="section-lead">
                Halaman ini menampilkan data Kecamatan yang diambil dari server menggunakan server-side processing.
                Anda dapat menambahkan, mengedit, atau menghapus data Kecamatan di sini.
            </p>
            <a href="{{ route('kecamatan.create') }}" class="btn btn-warning mb-3">Tambah Kecamatan</a>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif



            <div class="card">
                <div class="card-header">
                    <h4>Daftar Kecamatan</h4>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="kecamatanTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nama Kecamatan</th>
                                        <th>Kode Kecamatan</th>
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
                    Apakah Anda yakin ingin menghapus kecamatan ini?
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
        $(document).ready(function() {
            var deleteUrl = ''; // Variable to store delete URL
            var table = $('#kecamatanTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('kecamatan.index') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'nama_kecamatan',
                        name: 'nama_kecamatan'
                    },
                    {
                        data: 'kode_kecamatan',
                        name: 'kode_kecamatan'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                drawCallback: function() {
                    $('#kecamatanTable .dropdown-toggle').dropdown();
                }
            });

            // Open delete modal
            $(document).on('click', '.deleteButton', function(e) {
                e.preventDefault();
                deleteUrl = $(this).data('url'); // Get the delete URL from button's data attribute
                $('#deleteModal').modal('show'); // Show modal
            });

            // Confirm delete action
            $('#confirmDelete').on('click', function() {
                $.ajax({
                    url: deleteUrl,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#deleteModal').modal('hide'); // Hide modal
                        table.ajax.reload(); // Reload DataTable
                    }
                });
            });
        });
    </script>
@endpush

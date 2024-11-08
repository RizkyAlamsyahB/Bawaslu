@extends('layouts.app')

@section('title', 'Data TPS')

@push('style')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Data TPS</h1>
        </div>

        <div class="section-body">
            <h2 class="section-title">Tabel Data TPS</h2>
            <p class="section-lead">
                Halaman ini menampilkan data TPS yang diambil dari server menggunakan server-side processing.
                Anda dapat menambahkan, mengedit, atau menghapus data TPS di sini.
            </p>
            <a href="{{ route('tps.create') }}" class="btn btn-warning mb-3">Tambah TPS</a>

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
                    <h4>Daftar TPS</h4>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="tpsTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>No TPS</th>
                                        <th>Kelurahan</th>
                                        <th>Kecamatan</th>
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
                    Apakah Anda yakin ingin menghapus TPS ini?
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
            var table = $('#tpsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('tps.index') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'no_tps',
                        name: 'no_tps'
                    },
                    {
                        data: 'kelurahan',
                        name: 'kelurahan'
                    },
                    {
                        data: 'kecamatan',
                        name: 'kecamatan'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            var deleteUrl; // Menyimpan URL penghapusan sementara

            // Event untuk tombol hapus untuk menampilkan modal
            $('#tpsTable').on('click', '.btn-delete', function() {
                deleteUrl = $(this).data('url'); // Mendapatkan URL hapus dari tombol
                $('#deleteModal').modal('show'); // Tampilkan modal konfirmasi
            });

            // Event untuk tombol konfirmasi hapus di dalam modal
            $('#confirmDelete').on('click', function() {
                $.ajax({
                    url: deleteUrl,
                    type: 'DELETE',
                    data: {
                        "_token": "{{ csrf_token() }}" // Token CSRF untuk keamanan
                    },
                    success: function(response) {
                        $('#deleteModal').modal('hide'); // Sembunyikan modal setelah sukses
                        table.ajax.reload(); // Reload data tabel
                        alert(response.success); // Notifikasi berhasil
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan saat menghapus data.');
                    }
                });
            });
        });
    </script>
@endpush

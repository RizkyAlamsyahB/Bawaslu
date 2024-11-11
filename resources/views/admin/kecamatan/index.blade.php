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
            <div class="mb-3">
                <a href="{{ route('kecamatan.create') }}" class="btn btn-warning">Tambah Kecamatan</a>
                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#importModal">
                    Import Kecamatan
                </button>
            </div>

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
                    <h4>Daftar Kecamatan</h4>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="kecamatanTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Kode Kecamatan</th>
                                        <th>Nama Kecamatan</th>
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

    <!-- Modal for import -->
    <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import Data Kecamatan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('kecamatan.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="btn btn-warning">
                                Upload CSV <input type="file" class="d-none" name="csv_file" required accept=".csv">
                            </label>

                            <small class="form-text text-muted">
                                Format CSV yang diharapkan:<br>
                                <code>KODE KECAMATAN,NAMA KECAMATAN</code><br>
                                Contoh isi:<br>
                                <code>01,Karang Pilang</code><br>
                                <code>02,Wonocolo</code><br><br>
                                Catatan:<br>
                                • Kode kecamatan akan otomatis diformat menjadi 2 digit<br>
                                • Data yang sudah ada akan dilewati<br>
                                • Maksimal ukuran file: 2MB
                            </small>
                        </div>
                        @error('csv_file')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning">Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

   <!-- Modal for delete confirmation -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus data ini?
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
            table = $('#kecamatanTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('kecamatan.index') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'kode_kecamatan',
                        name: 'kode_kecamatan'
                    },
                    {
                        data: 'nama_kecamatan',
                        name: 'nama_kecamatan'
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
                    $('#kecamatanTable .dropdown-toggle').dropdown();
                }
            });
        });
        // Define CSRF token and session data for JavaScript
        const csrfToken = "{{ csrf_token() }}";
        const csvImportError = {{ $errors->has('csv_file') ? 'true' : 'false' }};
        const importSuccessMessage = "{{ session('import_success') }}";
    </script>
    <script src="{{ asset('js/kecamatan.js') }}"></script>
@endpush

@extends('layouts.app')

@section('title', 'Data Kelurahan')

@push('style')
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
            </p>

            <div class="mb-3">
                <a href="{{ route('kelurahan.create') }}" class="btn btn-warning">Tambah Kelurahan</a>
                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#importModal">
                    Import Kelurahan
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

            @if (session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    {{ session('warning') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h4>Daftar Kelurahan</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="kelurahanTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Kode Kecamatan</th>
                                    <th>Kode Kelurahan</th>
                                    <th>Nama Kelurahan</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Import -->
    <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import Data Kelurahan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('kelurahan.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="btn btn-warning">
                                Upload CSV <input type="file" class="d-none" name="csv_file" required accept=".csv">
                            </label>
                            <small class="form-text text-muted">
                                Format CSV yang diharapkan:<br>
                                <code>KODE KECAMATAN,KODE KELURAHAN,KELURAHAN</code><br>
                                Contoh isi:<br>
                                <code>01,1001,Karang Pilang</code><br>
                                <code>01,1002,Kebraon</code><br><br>
                                Catatan:<br>
                                • Kode kecamatan harus sudah terdaftar di sistem<br>
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

    <!-- Modal Delete -->
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
                        name: 'kode_kecamatan',
                    },
                    {
                        data: 'kode_kelurahan',
                        name: 'kode_kelurahan'
                    },
                    {
                        data: 'nama_kelurahan',
                        name: 'nama_kelurahan'
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

        const csrfToken = "{{ csrf_token() }}"; // Pass CSRF token to JavaScript
        const csvImportError = @json($errors->has('csv_file'));
        const importSuccessMessage = @json(session('import_success'));
    </script>
    <script src="{{ asset('js/kelurahan.js') }}"></script>
@endpush

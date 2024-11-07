@extends('layouts.app')

@section('title', 'Data Kecamatan')

@push('style')
    <!-- CSS untuk DataTables Bootstrap 5 -->
    <link href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap5.min.css" rel="stylesheet">
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
        <a href="{{ route('kecamatan.create') }}" class="btn btn-warning">Tambah Kecamatan</a>

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
</section>

@endsection

@push('scripts')
    <!-- JS untuk DataTables -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#kecamatanTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('kecamatan.index') }}',  // URL untuk mengambil data
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex' }, // Kolom untuk nomor urut
                    { data: 'name', name: 'name' },  // Kolom untuk nama kecamatan
                    { data: 'code', name: 'code' },  // Kolom untuk kode kecamatan
                    { data: 'action', name: 'action', orderable: false, searchable: false } // Kolom untuk aksi
                ]
            });
        });
    </script>
@endpush


@extends('layouts.app')

@section('title', 'Data Pengguna Hak Pilih DPK')

@push('style')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
@endpush

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Data Pengguna Hak Pilih DPK</h1>
    </div>

    <div class="section-body">
        <h2 class="section-title">Tabel Data Pengguna Hak Pilih DPK</h2>
        <p class="section-lead">
            Halaman ini menampilkan data pengguna hak pilih DPK yang diambil dari server menggunakan server-side processing.
            Anda dapat menambahkan, mengedit, atau menghapus data pengguna hak pilih DPK di sini.
        </p>
        <a href="{{ route('pengguna_hak_pilih_dpk.create') }}" class="btn btn-warning mb-3">Tambah Pengguna Hak Pilih DPK</a>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="penggunaHakPilihDpkTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tipe Pemilihan</th>
                                <th>Laki-laki</th>
                                <th>Perempuan</th>
                                <th>Jumlah</th>
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
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#penggunaHakPilihDpkTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('pengguna_hak_pilih_dpk.index') }}',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                    { data: 'tipe_pemilihan', name: 'tipe_pemilihan' },
                    { data: 'laki_laki', name: 'laki_laki' },
                    { data: 'perempuan', name: 'perempuan' },
                    { data: 'jumlah', name: 'jumlah' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });
        });
    </script>
@endpush
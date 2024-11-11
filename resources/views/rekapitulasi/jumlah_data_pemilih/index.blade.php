@extends('layouts.app')

@section('title', 'Data Jumlah Pemilih')

@push('style')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
@endpush

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Data Jumlah Pemilih</h1>
    </div>

    <div class="section-body">
        <h2 class="section-title">Tabel Data Jumlah Pemilih</h2>
        <p class="section-lead">
            Halaman ini menampilkan data jumlah pemilih yang diambil dari server menggunakan server-side processing.
            Anda dapat menambahkan, mengedit, atau menghapus data jumlah pemilih di sini.
        </p>
        <a href="{{ route('jumlah_data_pemilih.create') }}" class="btn btn-warning mb-3">Tambah Jumlah Pemilih</a>

        <div class="card">
        @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="jumlahDataPemilihTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tipe Pemilihan</th>
                                <th>Laki-laki</th>
                                <th>Perempuan</th>
                                <th>Jumlah</th>
                                @if (auth()->user()->hasRole('super_admin'))
    <th>Actions</th>
@endif
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
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
    $('#jumlahDataPemilihTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('jumlah_data_pemilih.index') }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex' },
            { data: 'tipe_pemilihan', name: 'tipe_pemilihan' },
            { data: 'laki_laki', name: 'laki_laki' },
            { data: 'perempuan', name: 'perempuan' },
            { data: 'jumlah', name: 'jumlah' },
            @if (auth()->user()->hasRole('super_admin'))
                { data: 'action', name: 'action', orderable: false, searchable: false }
            @endif
        ],
        columnDefs: [
            { targets: 0, orderable: false, searchable: false } // Make the index column non-orderable and non-searchable
        ],
        order: [[1, 'asc']]
    });
});

    </script>

<script> function deleteJumlahDataPemilih(id) { if (confirm('Apakah Anda yakin ingin menghapus data ini?')) { $.ajax({ url: '{{ route('jumlah_data_pemilih.index') }}/' + id, type: 'DELETE', data: { _token: '{{ csrf_token() }}' }, success: function(response) { alert('Data berhasil dihapus'); $('#jumlahDataPemilihTable').DataTable().ajax.reload(); }, error: function(xhr) { alert('Terjadi kesalahan saat menghapus data'); } }); } } </script>

   <script>
    let deleteId = null;

    function openDeleteModal(id) {
        deleteId = id; // Simpan ID untuk dihapus
        $('#deleteModal').modal('show'); // Tampilkan modal
    }

    $('#confirmDelete').click(function() {
        if (deleteId) {
            $.ajax({
                url: '{{ route('jumlah_data_pemilih.index') }}/' + deleteId,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    alert('Data berhasil dihapus');
                    $('#jumlahDataPemilihTable').DataTable().ajax.reload();
                    $('#deleteModal').modal('hide'); // Sembunyikan modal setelah menghapus
                },
                error: function(xhr) {
                    alert('Terjadi kesalahan saat menghapus data');
                    $('#deleteModal').modal('hide'); // Sembunyikan modal jika terjadi error
                }
            });
        }
    });
</script>

@endpush

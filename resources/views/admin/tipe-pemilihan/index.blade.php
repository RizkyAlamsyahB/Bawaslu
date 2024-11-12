<!-- resources/views/admin/tipe-pemilihan/index.blade.php -->

@extends('layouts.app')

@section('title', 'Data Tipe Pemilihan')

@push('style')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Data Tipe Pemilihan</h1>
        </div>

        <div class="section-body">
            <h2 class="section-title">Tabel Data Tipe Pemilihan</h2>
            <p class="section-lead">
                Halaman ini menampilkan data Tipe Pemilihan.
            </p>
            <div class="mb-3">
                <a href="{{ route('tipe_pemilihan.create') }}" class="btn btn-primary">Tambah Tipe Pemilihan</a>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="tipePemilihanTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Tipe Pemilihan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal untuk konfirmasi delete -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Penghapusan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus tipe pemilihan ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <form id="deleteForm" action="" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
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
            $('#tipePemilihanTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('tipe_pemilihan.index') }}',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'nama', name: 'nama' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });
        });

        function deleteConfirmation(id) {
    $('#deleteForm').attr('action', '{{ route('tipe_pemilihan.destroy', ':id') }}'.replace(':id', id));
    $('#deleteModal').modal('show');
}
    </script>
@endpush

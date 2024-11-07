@extends('layouts.app')

@section('title', 'Data TPS')

@push('style')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
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

            <!-- Tombol Create -->
            <a href="{{ route('tps.create') }}" class="btn btn-warning mb-3">Tambah Data TPS</a>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="tpsTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Kelurahan</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data akan di-load melalui server-side -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('scripts')
    <!-- Include DataTables JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#tpsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('tps.index') }}', // Sesuaikan dengan route index Anda
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'code',
                        name: 'code'
                    },
                    {
                        data: 'kelurahan.name',
                        name: 'kelurahan.name'
                    }, // Mengakses nama kelurahan
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });
    </script>
@endpush

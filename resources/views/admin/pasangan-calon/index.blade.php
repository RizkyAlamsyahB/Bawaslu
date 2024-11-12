@extends('layouts.app')

@section('title', 'Data Pasangan Calon')

@push('style')
   <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
@endpush

@section('content')
   <section class="section">
       <div class="section-header">
           <h1>Data Pasangan Calon</h1>
       </div>

       <div class="section-body">
           <h2 class="section-title">Tabel Data Pasangan Calon</h2>
           <p class="section-lead">
               Halaman ini menampilkan data Pasangan Calon.
           </p>
           <div class="mb-3">
               <a href="{{ route('pasangan_calon.create') }}" class="btn btn-primary">Tambah Pasangan Calon</a>
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
                       <table class="table table-bordered" id="pasanganCalonTable">
                           <thead>
                               <tr>
                                   <th>#</th>
                                   <th>Nomor Urut</th>
                                   <th>Nama Pasangan</th>
                                   <th>Tipe Pemilihan</th>
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
                   Apakah Anda yakin ingin menghapus pasangan calon ini?
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
           $('#pasanganCalonTable').DataTable({
               processing: true,
               serverSide: true,
               ajax: '{{ route('pasangan_calon.index') }}',
               columns: [
                   { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                   { data: 'nomor_urut', name: 'nomor_urut' },
                   { data: 'nama_pasangan', name: 'nama_pasangan' },
                   { data: 'tipe_pemilihan', name: 'tipe_pemilihan' },
                   { data: 'action', name: 'action', orderable: false, searchable: false }
               ]
           });
       });

       function deleteConfirmation(id) {
           $('#deleteForm').attr('action', '{{ route('pasangan_calon.destroy', ':id') }}'.replace(':id', id));
           $('#deleteModal').modal('show');
       }
   </script>
@endpush

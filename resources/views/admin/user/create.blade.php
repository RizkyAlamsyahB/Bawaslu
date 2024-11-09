@extends('layouts.app')

@section('title', 'Tambah User')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Tambah User</h1>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('user.store') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="phone">Telepon</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>

                        <div class="form-group">
                            <label for="kecamatan_id">Kecamatan</label>
                            <select class="form-control" id="kecamatan_id" name="kecamatan_id">
                                <option value="">Pilih Kecamatan</option>
                                @foreach ($kecamatans as $kecamatan)
                                    <option value="{{ $kecamatan->id }}">{{ $kecamatan->nama_kecamatan }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="kelurahan_id">Kelurahan</label>
                            <select class="form-control" id="kelurahan_id" name="kelurahan_id">
                                <option value="">Pilih Kelurahan</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="tps_id">TPS</label>
                            <select class="form-control" id="tps_id" name="tps_id">
                                <option value="">Pilih TPS</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('user.index') }}" class="btn btn-secondary">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
    <script>
        // Ketika Kecamatan dipilih
        $('#kecamatan_id').change(function() {
            let kecamatanId = $(this).val();  // Ambil ID Kecamatan yang dipilih

            // Reset dropdown Kelurahan dan TPS
            $('#kelurahan_id').html('<option value="">Pilih Kelurahan</option>');
            $('#tps_id').html('<option value="">Pilih TPS</option>');

            // Jika Kecamatan dipilih, ambil data Kelurahan yang terkait
            if (kecamatanId) {
                $.get('/kelurahan/by-kecamatan/' + kecamatanId, function(data) {
                    // Iterasi data Kelurahan yang diterima dari server
                    $.each(data, function(id, nama_kelurahan) {
                        // Tambahkan option Kelurahan ke dropdown
                        $('#kelurahan_id').append(new Option(nama_kelurahan, id));
                    });
                });
            }
        });

        // Ketika Kelurahan dipilih
        $('#kelurahan_id').change(function() {
            let kelurahanId = $(this).val();  // Ambil ID Kelurahan yang dipilih

            // Reset dropdown TPS
            $('#tps_id').html('<option value="">Pilih TPS</option>');

            // Jika Kelurahan dipilih, ambil data TPS yang terkait
            if (kelurahanId) {
                $.get('/get-tps-by-kelurahan/' + kelurahanId, function(data) {
                    // Periksa format data dan tampilkan dengan benar
                    $.each(data, function(index, tps) {
                        // Pastikan tps adalah objek dengan atribut yang sesuai
                        if (tps.no_tps && tps.id) {
                            $('#tps_id').append(new Option(tps.no_tps, tps.id)); // Menambahkan no_tps dan id
                        }
                    });
                });
            }
        });

        // Jika Kecamatan dipilih terlebih dahulu, bisa mengambil TPS berdasarkan Kecamatan (untuk form tanpa Kelurahan)
        $('#kecamatan_id').change(function() {
            let kecamatanId = $(this).val();  // Ambil ID Kecamatan yang dipilih
            if (kecamatanId) {
                $.get('/tps/by-kecamatan/' + kecamatanId, function(data) {
                    $('#tps_id').html('<option value="">Pilih TPS</option>');  // Reset TPS dropdown
                    $.each(data, function(index, tps) {
                        // Pastikan data TPS valid
                        if (tps.no_tps && tps.id) {
                            $('#tps_id').append(new Option(tps.no_tps, tps.id));  // Tambahkan TPS ke dropdown
                        }
                    });
                });
            }
        });
    </script>
    @endpush


@endsection

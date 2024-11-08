@extends('layouts.app')

@section('title', 'Tambah Pengguna')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Tambah Pengguna</h1>
    </div>

    <div class="section-body">
        <h2 class="section-title">Formulir Tambah Pengguna</h2>
        <p class="section-lead">
            Halaman ini memungkinkan Anda untuk menambahkan data pengguna baru.
        </p>

        <div class="card">
            <div class="card-header">
                <h4>Formulir Pengguna</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('user.store') }}" method="POST">
                    @csrf
                    <!-- Nama -->
                    <div class="form-group">
                        <label for="name">Nama Lengkap</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>

                    <!-- Nomor Telepon -->
                    <div class="form-group">
                        <label for="phone">Nomor Telepon</label>
                        <input type="text" id="phone" name="phone" class="form-control" required>
                    </div>

                    <!-- Username -->
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" class="form-control" required>
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>

                    <!-- Role -->
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select id="role" name="role" class="form-control" required>
                            <option value="">-- Pilih Role --</option>
                            <option value="tps">TPS</option>
                            <option value="kecamatan">Kecamatan</option>
                            <option value="kelurahan">Kelurahan</option>
                            <option value="kota">Kota</option>
                            <option value="super_admin">Super Admin</option>
                        </select>
                    </div>

                    <!-- Kecamatan -->
                    <div class="form-group">
                        <label for="kecamatan_id">Kecamatan</label>
                        <select id="kecamatan_id" name="kecamatan_id" class="form-control">
                            <option value="">-- Pilih Kecamatan --</option>
                            @foreach($kecamatans as $kecamatan)
                                <option value="{{ $kecamatan->id }}">{{ $kecamatan->nama_kecamatan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Kelurahan -->
                    <div class="form-group">
                        <label for="kelurahan_id">Kelurahan</label>
                        <select id="kelurahan_id" name="kelurahan_id" class="form-control">
                            <option value="">-- Pilih Kelurahan --</option>
                            @foreach($kelurahans as $kelurahan)
                                <option value="{{ $kelurahan->id }}">{{ $kelurahan->nama_kelurahan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- TPS -->
                    <div class="form-group">
                        <label for="tps_id">TPS</label>
                        <select id="tps_id" name="tps_id" class="form-control">
                            <option value="">-- Pilih TPS --</option>
                            @foreach($tps as $t)
                                <option value="{{ $t->id }}">{{ $t->no_tps }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tombol Submit -->
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('user.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
    // Memperbarui dropdown kelurahan berdasarkan kecamatan yang dipilih
    $('#kecamatan_id').change(function() {
        let kecamatanId = $(this).val();
        $.ajax({
            url: '/get-kelurahans/' + kecamatanId,
            method: 'GET',
            success: function(data) {
                $('#kelurahan_id').empty().append('<option value="">-- Pilih Kelurahan --</option>');
                data.forEach(function(kelurahan) {
                    $('#kelurahan_id').append('<option value="' + kelurahan.id + '">' + kelurahan.name + '</option>');
                });
            }
        });
    });

    // Memperbarui dropdown TPS berdasarkan kelurahan yang dipilih
    $('#kelurahan_id').change(function() {
        let kelurahanId = $(this).val();
        $.ajax({
            url: '/get-tps/' + kelurahanId,
            method: 'GET',
            success: function(data) {
                $('#tps_id').empty().append('<option value="">-- Pilih TPS --</option>');
                data.forEach(function(tps) {
                    $('#tps_id').append('<option value="' + tps.id + '">' + tps.number + '</option>');
                });
            }
        });
    });
</script>
@endsection

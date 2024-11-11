@extends('layouts.app')

@section('title', 'Tambah Jumlah Pengguna Hak Pilih')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Tambah Jumlah Pengguna Hak Pilih</h1>
    </div>

    <div class="section-body">
        <h2 class="section-title">Formulir Tambah Jumlah Pengguna Hak Pilih</h2>
        <p class="section-lead">
            Halaman ini memungkinkan Anda untuk menambahkan data jumlah pengguna hak pilih baru.
        </p>

        <div class="card">
            <div class="card-header">
                <h4>Formulir Jumlah Pengguna Hak Pilih</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('jumlah_pengguna_hak_pilih.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="tipe_pemilihan">Tipe Pemilihan</label>
                        <input type="text" class="form-control" id="tipe_pemilihan" name="tipe_pemilihan" required>
                    </div>
                    <div class="form-group">
                        <label for="laki_laki">Laki-laki</label>
                        <input type="number" class="form-control" id="laki_laki" name="laki_laki" required>
                    </div>
                    <div class="form-group">
                        <label for="perempuan">Perempuan</label>
                        <input type="number" class="form-control" id="perempuan" name="perempuan" required>
                    </div>
                    <div class="form-group">
                        <label for="jumlah">Jumlah</label>
                        <input type="number" class="form-control" id="jumlah" name="jumlah" required>
                    </div>
                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('jumlah_pengguna_hak_pilih.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
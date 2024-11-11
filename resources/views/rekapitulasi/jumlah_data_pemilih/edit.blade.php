@extends('layouts.app')

@section('title', 'Edit Data Jumlah Pemilih')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Edit Data Jumlah Pemilih</h1>
    </div>

    <div class="section-body">
        <h2 class="section-title">Form Edit Data Jumlah Pemilih</h2>
        <p class="section-lead">
            Halaman ini memungkinkan Anda mengedit data jumlah pemilih yang sudah ada.
        </p>

        <div class="card">
        @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ $errors->first() }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <div class="card-body">
                <form action="{{ route('jumlah_data_pemilih.update', $data->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="tipe_pemilihan">Tipe Pemilihan</label>
                        <select name="tipe_pemilihan" id="tipe_pemilihan" class="form-control">
                            <option value="gubernur" {{ $data->tipe_pemilihan == 'gubernur' ? 'selected' : '' }}>Gubernur</option>
                            <option value="walikota" {{ $data->tipe_pemilihan == 'walikota' ? 'selected' : '' }}>Walikota</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="laki_laki">Jumlah Pemilih Laki-laki</label>
                        <input type="number" name="laki_laki" id="laki_laki" class="form-control" value="{{ old('laki_laki', $data->laki_laki) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="perempuan">Jumlah Pemilih Perempuan</label>
                        <input type="number" name="perempuan" id="perempuan" class="form-control" value="{{ old('perempuan', $data->perempuan) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="jumlah">Total Jumlah Pemilih</label>
                        <input type="number" name="jumlah" id="jumlah" class="form-control" value="{{ old('jumlah', $data->jumlah) }}" required>
                    </div>

                    <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                    <a href="{{ route('jumlah_data_pemilih.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</section>
@   

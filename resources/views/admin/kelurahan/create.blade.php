@extends('layouts.app')

@section('title', 'Tambah Kelurahan')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Tambah Kelurahan</h1>
        </div>

        <div class="section-body">
            <h2 class="section-title">Formulir Tambah Kelurahan</h2>
            <p class="section-lead">
                Halaman ini memungkinkan Anda untuk menambahkan data kelurahan baru.
            </p>

            <div class="card">
                <div class="card-header">
                    <h4>Formulir Kelurahan</h4>
                </div>
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if (session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        {{ session('warning') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                <div class="card-body">
                    <form action="{{ route('kelurahan.store') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="kecamatan_id">Kecamatan</label>
                            <select class="form-control" id="kecamatan_id" name="kecamatan_id" required>
                                <option value="">Pilih Kecamatan</option>
                                @foreach ($kecamatans as $kecamatan)
                                    <option value="{{ $kecamatan->id }}"
                                        {{ old('kecamatan_id') == $kecamatan->id ? 'selected' : '' }}>
                                        {{ $kecamatan->nama_kecamatan }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kecamatan_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="nama_kelurahan">Nama Kelurahan</label>
                            <input type="text" class="form-control" id="nama_kelurahan" name="nama_kelurahan"
                                value="{{ old('nama_kelurahan') }}" required>
                            @error('nama_kelurahan')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="kode_kelurahan">Kode Kelurahan</label>
                            <input type="text" class="form-control" id="kode_kelurahan" name="kode_kelurahan"
                                value="{{ old('kode_kelurahan', $kelurahan->kode_kelurahan ?? '') }}" required
                                inputmode="numeric">
                            @error('kode_kelurahan')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group text-left">
                            <button type="submit" class="btn btn-warning">Simpan</button>
                            <a href="{{ route('kelurahan.index') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </section>
@endsection

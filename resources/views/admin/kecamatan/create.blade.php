@extends('layouts.app')

@section('title', 'Tambah Kecamatan')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Tambah Kecamatan</h1>
        </div>

        <div class="section-body">
            <h2 class="section-title">Formulir Tambah Kecamatan</h2>
            <p class="section-lead">
                Halaman ini memungkinkan Anda untuk menambahkan data kecamatan baru.
            </p>

            <div class="card">
                <div class="card-header">
                    <h4>Formulir Kecamatan</h4>
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
                    <form action="{{ route('kecamatan.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="nama_kecamatan">Nama Kecamatan</label>
                            <input type="text" class="form-control" id="nama_kecamatan" name="nama_kecamatan"
                                value="{{ old('nama_kecamatan') }}" required>
                            @error('nama_kecamatan')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="kode_kecamatan">Kode Kecamatan</label>
                            <input type="text" class="form-control" id="kode_kecamatan" name="kode_kecamatan"
                                value="{{ old('kode_kecamatan') }}" required inputmode="numeric">
                            @error('kode_kecamatan')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group text-left">
                            <button type="submit" class="btn btn-warning">Simpan</button>
                            <a href="{{ route('kecamatan.index') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </section>
@endsection

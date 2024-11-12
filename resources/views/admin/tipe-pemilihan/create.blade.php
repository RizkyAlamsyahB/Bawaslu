@extends('layouts.app')

@section('title', 'Tambah Tipe Pemilihan')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Tambah Tipe Pemilihan</h1>
        </div>

        <div class="section-body">
            <h2 class="section-title">Formulir Tambah Tipe Pemilihan</h2>
            <p class="section-lead">
                Halaman ini memungkinkan Anda untuk menambahkan data tipe pemilihan baru.
            </p>

            <div class="card">
                <div class="card-header">
                    <h4>Formulir Tipe Pemilihan</h4>
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
                    <form action="{{ route('tipe_pemilihan.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="nama">Nama Tipe Pemilihan</label>
                            <input type="text" class="form-control" id="nama" name="nama"
                                value="{{ old('nama') }}" required>
                            @error('nama')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group text-left">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{ route('tipe_pemilihan.index') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </section>
@endsection

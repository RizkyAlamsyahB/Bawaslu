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
                <div class="card-body">
                    <form action="{{ route('kecamatan.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">Nama Kecamatan</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="code">Kode Kecamatan</label>
                            <input type="text" class="form-control" id="code" name="code" value="{{ old('code') }}" required>
                            @error('code')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group text-left">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{ route('kecamatan.index') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

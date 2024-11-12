<!-- resources/views/admin/pasangan-calon/edit.blade.php -->
@extends('layouts.app')

@section('title', 'Edit Pasangan Calon')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Edit Pasangan Calon</h1>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('pasangan_calon.update', $pasanganCalon->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label>Tipe Pemilihan</label>
                        <select name="tipe_pemilihan_id" class="form-control @error('tipe_pemilihan_id') is-invalid @enderror" required>
                            <option value="">Pilih Tipe Pemilihan</option>
                            @foreach($tipePemilihans as $tipe)
                                <option value="{{ $tipe->id }}" {{ $pasanganCalon->tipe_pemilihan_id == $tipe->id ? 'selected' : '' }}>
                                    {{ $tipe->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('tipe_pemilihan_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Nomor Urut</label>
                        <input type="number" name="nomor_urut" class="form-control @error('nomor_urut') is-invalid @enderror" value="{{ old('nomor_urut', $pasanganCalon->nomor_urut) }}" required>
                        @error('nomor_urut')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Nama Pasangan</label>
                        <input type="text" name="nama_pasangan" class="form-control @error('nama_pasangan') is-invalid @enderror" value="{{ old('nama_pasangan', $pasanganCalon->nama_pasangan) }}" required>
                        @error('nama_pasangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('pasangan_calon.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

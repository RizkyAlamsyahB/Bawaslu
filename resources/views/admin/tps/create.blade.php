@extends('layouts.app')

@section('title', 'Tambah Data TPS')

@push('style')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@section('content')

    <section class="section">
        <div class="section-header">
            <h1>Tambah Data TPS</h1>
        </div>

        <div class="section-body">
            <h2 class="section-title">Formulir Data TPS</h2>

            <div class="card">
                <div class="card-header">
                    <h4>Tambah Data TPS</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('tps.store') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="name">Nama TPS</label>
                            <input type="text" id="name" name="name" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="code">Kode TPS</label>
                            <input type="text" id="code" name="code" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="kelurahan_id">Kelurahan</label>
                            <select id="kelurahan_id" name="kelurahan_id" class="form-control" required>
                                @foreach($kelurahans as $kelurahan)
                                    <option value="{{ $kelurahan->id }}">{{ $kelurahan->nama_kelurahan }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-success">Simpan</button>
                        <a href="{{ route('tps.index') }}" class="btn btn-secondary">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </section>

@endsection

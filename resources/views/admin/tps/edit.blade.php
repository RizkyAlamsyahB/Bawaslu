@extends('layouts.app')

@section('title', 'Edit Data TPS')

@push('style')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@section('content')

    <section class="section">
        <div class="section-header">
            <h1>Edit Data TPS</h1>
        </div>

        <div class="section-body">
            <h2 class="section-title">Formulir Edit Data TPS</h2>

            <div class="card">
                <div class="card-header">
                    <h4>Edit Data TPS</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('tps.update', $tps->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group mb-3">
                            <label for="name">Nama TPS</label>
                            <input type="text" id="name" name="name" class="form-control" value="{{ $tps->name }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="code">Kode TPS</label>
                            <input type="text" id="code" name="code" class="form-control" value="{{ $tps->code }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="kelurahan_id">Kelurahan</label>
                            <select id="kelurahan_id" name="kelurahan_id" class="form-control" required>
                                @foreach($kelurahans as $kelurahan)
                                    <option value="{{ $kelurahan->id }}" {{ $kelurahan->id == $tps->kelurahan_id ? 'selected' : '' }}>
                                        {{ $kelurahan->nama_kelurahan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-success">Update</button>
                        <a href="{{ route('tps.index') }}" class="btn btn-secondary">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </section>

@endsection

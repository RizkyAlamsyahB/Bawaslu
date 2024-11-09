@extends('layouts.app')

@section('title', 'Edit Kelurahan')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Edit Kelurahan</h1>
        </div>

        <div class="section-body">
            <h2 class="section-title">Formulir Edit Kelurahan</h2>
            <p class="section-lead">
                Halaman ini memungkinkan Anda untuk mengedit data kelurahan yang ada.
            </p>

            <div class="card">
                <div class="card-header">
                    <h4>Formulir Edit Kelurahan</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('kelurahan.update', $kelurahan->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="kecamatan_id">Kecamatan</label>
                            <select class="form-control" id="kecamatan_id" name="kecamatan_id" required>
                                <option value="">Pilih Kecamatan</option>
                                @foreach ($kecamatans as $kecamatan)
                                    <option value="{{ $kecamatan->id }}"
                                        {{ old('kecamatan_id', $kelurahan->kecamatan_id) == $kecamatan->id ? 'selected' : '' }}>
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
                                value="{{ old('nama_kelurahan', $kelurahan->nama_kelurahan) }}" required>
                            @error('nama_kelurahan')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="kode_kelurahan">Kode Kelurahan</label>
                            <input type="text" class="form-control" id="kode_kelurahan" name="kode_kelurahan"
                                value="{{ old('kode_kelurahan', $kelurahan->kode_kelurahan) }}" required>
                            @error('kode_kelurahan')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-warning">Update Kelurahan</button>
                            <a href="{{ route('kelurahan.index') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Optional: Add Select2 for better dropdown experience
            if (typeof $.fn.select2 === 'function') {
                $('#kecamatan_id').select2({
                    placeholder: "Pilih Kecamatan",
                    allowClear: true
                });
            }
        });
    </script>
@endpush

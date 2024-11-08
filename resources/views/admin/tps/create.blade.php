@extends('layouts.app')

@section('title', 'Tambah TPS')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Tambah TPS</h1>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Formulir TPS</h4>
                </div>
                <div class="card-body">
                    @if ($errors->has('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ $errors->first() }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                    <form action="{{ route('tps.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="kecamatan_id">Kecamatan</label>
                            <select class="form-control" id="kecamatan_id" name="kecamatan_id">
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
                            <label for="kelurahan_id">Kelurahan</label>
                            <select class="form-control" id="kelurahan_id" name="kelurahan_id">
                                <option value="">Pilih Kelurahan</option>
                            </select>
                            @error('kelurahan_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="no_tps">No TPS</label>
                            <input type="text" class="form-control" id="no_tps" name="no_tps"
                                value="{{ old('no_tps') }}" required>
                            @error('no_tps')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group text-left">
                            <button type="submit" class="btn btn-warning">Simpan</button>
                            <a href="{{ route('tps.index') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#kecamatan_id').on('change', function() {
                var kecamatanId = $(this).val();
                if (kecamatanId) {
                    $.ajax({
                        url: '{{ url('get-kelurahans-by-kecamatan') }}/' + kecamatanId,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $('#kelurahan_id').empty();
                            $('#kelurahan_id').append(
                                '<option value="">Pilih Kelurahan</option>');
                            $.each(data, function(key, value) {
                                $('#kelurahan_id').append('<option value="' + key +
                                    '">' + value + '</option>');
                            });
                        }
                    });
                } else {
                    $('#kelurahan_id').empty();
                    $('#kelurahan_id').append('<option value="">Pilih Kelurahan</option>');
                }
            });
        });
    </script>
@endsection

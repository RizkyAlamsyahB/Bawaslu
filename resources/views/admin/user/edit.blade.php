@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Edit User</h1>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('user.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="phone">Telepon</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" required inputmode="numeric">
                        </div>

                        <div class="form-group">
                            <label for="password">Password (Opsional)</label>
                            <input type="password" class="form-control" id="password" name="password">
                            <small class="text-muted">Kosongkan jika tidak ingin mengubah password.</small>
                        </div>

                        <div class="form-group">
                            <label for="kecamatan_id">Kecamatan</label>
                            <select class="form-control" id="kecamatan_id" name="kecamatan_id">
                                <option value="">Pilih Kecamatan</option>
                                @foreach ($kecamatans as $kecamatan)
                                    <option value="{{ $kecamatan->id }}" {{ $user->kecamatan_id == $kecamatan->id ? 'selected' : '' }}>
                                        {{ $kecamatan->nama_kecamatan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="kelurahan_id">Kelurahan</label>
                            <select class="form-control" id="kelurahan_id" name="kelurahan_id">
                                <option value="">Pilih Kelurahan</option>
                                @foreach ($kelurahans as $kelurahan)
                                    <option value="{{ $kelurahan->id }}" {{ $user->kelurahan_id == $kelurahan->id ? 'selected' : '' }}>
                                        {{ $kelurahan->nama_kelurahan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="tps_id">TPS</label>
                            <select class="form-control" id="tps_id" name="tps_id">
                                <option value="">Pilih TPS</option>
                                @foreach ($tps as $tpsItem)
                                    <option value="{{ $tpsItem->id }}" {{ $user->tps_id == $tpsItem->id ? 'selected' : '' }}>
                                        {{ $tpsItem->no_tps }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-warning">Simpan</button>
                        <a href="{{ route('user.index') }}" class="btn btn-secondary">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
    <script>
        $(document).ready(function() {
            // Load Kelurahan when Kecamatan is selected
            $('#kecamatan_id').change(function() {
                let kecamatanId = $(this).val();
                $('#kelurahan_id').html('<option value="">Pilih Kelurahan</option>');
                $('#tps_id').html('<option value="">Pilih TPS</option>');

                if (kecamatanId) {
                    $.get('/kelurahan/by-kecamatan/' + kecamatanId, function(data) {
                        $.each(data, function(id, nama_kelurahan) {
                            $('#kelurahan_id').append(new Option(nama_kelurahan, id));
                        });
                    });
                }
            });

            // Load TPS when Kelurahan is selected
            $('#kelurahan_id').change(function() {
                let kelurahanId = $(this).val();
                $('#tps_id').html('<option value="">Pilih TPS</option>');

                if (kelurahanId) {
                    $.get('/get-tps-by-kelurahan/' + kelurahanId, function(data) {
                        $.each(data, function(index, tps) {
                            $('#tps_id').append(new Option(tps.no_tps, tps.id));
                        });
                    });
                }
            });
        });
    </script>
    @endpush
@endsection

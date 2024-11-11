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
            <p class="section-lead">
                Halaman ini memungkinkan Anda untuk mengedit data TPS yang ada.
            </p>

            <div class="card">
                <div class="card-header">
                    <h4>Formulir Edit TPS</h4>
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

                    <form action="{{ route('tps.update', $tps->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="kecamatan_id">Kecamatan</label>
                            <select class="form-control" id="kecamatan_id" name="kecamatan_id" required>
                                <option value="">Pilih Kecamatan</option>
                                @foreach ($kecamatans as $kecamatan)
                                    <option value="{{ $kecamatan->id }}"
                                        {{ old('kecamatan_id', $tps->kecamatan_id) == $kecamatan->id ? 'selected' : '' }}>
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
                            <select class="form-control" id="kelurahan_id" name="kelurahan_id" required>
                                <option value="">Pilih Kelurahan</option>
                            </select>
                            @error('kelurahan_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="no_tps">Nomor TPS</label>
                            <input type="text" class="form-control" id="no_tps" name="no_tps"
                                value="{{ old('no_tps', $tps->no_tps) }}" required inputmode="numeric">
                            @error('no_tps')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-warning">Update TPS</button>
                            <a href="{{ route('tps.index') }}" class="btn btn-secondary">Kembali</a>
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
            // Function untuk load kelurahan
            function loadKelurahan(kecamatanId, selectedKelurahanId = '') {
                $('#kelurahan_id').empty().append('<option value="">Pilih Kelurahan</option>');

                if (kecamatanId) {
                    $.ajax({
                        url: `/kelurahan/by-kecamatan/${kecamatanId}`,
                        type: 'GET',
                        success: function(response) {
                            response.forEach(function(kelurahan) {
                                let selected = (kelurahan.id == selectedKelurahanId) ?
                                    'selected' : '';
                                $('#kelurahan_id').append(
                                    `<option value="${kelurahan.id}" ${selected}>${kelurahan.nama_kelurahan}</option>`
                                );
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error('Error loading kelurahan:', error);
                        }
                    });
                }
            }

            // Event listener untuk perubahan kecamatan
            $('#kecamatan_id').change(function() {
                let kecamatanId = $(this).val();
                loadKelurahan(kecamatanId);
            });

            // Load kelurahan saat halaman pertama kali dimuat dengan data yang sudah ada
            let initialKecamatanId = $('#kecamatan_id').val();
            let initialKelurahanId = '{{ old('kelurahan_id', $tps->kelurahan_id) }}';
            if (initialKecamatanId) {
                loadKelurahan(initialKecamatanId, initialKelurahanId);
            }
        });
    </script>
@endpush

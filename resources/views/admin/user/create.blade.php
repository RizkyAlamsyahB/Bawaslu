@extends('layouts.app')

@section('title', 'Tambah User')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Tambah User</h1>
        </div>

        <div class="section-body">
            <h2 class="section-title">Formulir Tambah User</h2>
            <p class="section-lead">
                Halaman ini memungkinkan Anda untuk menambahkan user baru ke dalam sistem.
            </p>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('user.store') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input type="text" class="form-control" id="name" name="name"
                                value="{{ old('name') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="phone">Telepon</label>
                            <input type="text" class="form-control" id="phone" name="phone"
                                   value="{{ old('phone') }}" required inputmode="numeric">
                        </div>

                        <div class="form-group">
                            <label for="kecamatan_id">Kecamatan</label>
                            <select class="form-control" id="kecamatan_id" name="kecamatan_id">
                                <option value="">Pilih Kecamatan</option>
                                @foreach ($kecamatans as $kecamatan)
                                    <option value="{{ $kecamatan->id }}" data-kode="{{ $kecamatan->kode_kecamatan }}"
                                        data-nama="{{ $kecamatan->nama_kecamatan }}">
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
                                    <option value="{{ $kelurahan->id }}" data-kode="{{ $kelurahan->kode_kelurahan }}">
                                        {{ $kelurahan->nama_kelurahan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="tps_id">TPS</label>
                            <select class="form-control" id="tps_id" name="tps_id">
                                <option value="">Pilih TPS</option>
                                @foreach ($tps as $tp)
                                    <option value="{{ $tp->id }}">{{ $tp->no_tps }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Username fields -->
                        <div class="username-field-group">
                            <div class="form-group" id="username_auto_group">
                                <label for="username_preview">Username (Auto Generate)</label>
                                <input type="text" class="form-control" id="username_preview" readonly>
                                <small class="form-text text-muted">Username akan dibuat otomatis berdasarkan pilihan
                                    wilayah</small>
                            </div>

                            <div class="form-group" id="username_manual_group" style="display: none;">
                                <label for="username_manual">Username</label>
                                <input type="text" class="form-control" id="username_manual" name="username_manual">
                                <small class="form-text text-muted">Masukkan username yang diinginkan</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-warning">Simpan</button>
                            <a href="{{ route('user.index') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('js/user.js') }}"></script>
@endpush

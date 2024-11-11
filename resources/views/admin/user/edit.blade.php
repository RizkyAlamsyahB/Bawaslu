@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Edit User</h1>
        </div>

        <div class="section-body">
            <h2 class="section-title">Formulir Edit User</h2>
            <p class="section-lead">
                Halaman ini memungkinkan Anda untuk mengubah data user yang ada dalam sistem.
            </p>

            <div class="card">
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
                    <form action="{{ route('user.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input type="text" class="form-control" id="name" name="name"
                                value="{{ old('name', $user->name) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="phone">Telepon</label>
                            <input type="text" class="form-control" id="phone" name="phone"
                                value="{{ old('phone', $user->phone) }}" required inputmode="numeric">
                        </div>

                        <div class="form-group">
                            <label for="kecamatan_id">Kecamatan</label>
                            <select class="form-control" id="kecamatan_id" name="kecamatan_id">
                                <option value="">Pilih Kecamatan</option>
                                @foreach ($kecamatans as $kecamatan)
                                    <option value="{{ $kecamatan->id }}" data-kode="{{ $kecamatan->kode_kecamatan }}"
                                        data-nama="{{ $kecamatan->nama_kecamatan }}"
                                        {{ $user->kecamatan_id == $kecamatan->id ? 'selected' : '' }}>
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
                                    <option value="{{ $kelurahan->id }}" data-kode="{{ $kelurahan->kode_kelurahan }}"
                                        {{ $user->kelurahan_id == $kelurahan->id ? 'selected' : '' }}>
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
                                    <option value="{{ $tp->id }}" {{ $user->tps_id == $tp->id ? 'selected' : '' }}>
                                        {{ $tp->no_tps }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Username fields -->
                        <div class="username-field-group">
                            <div class="form-group" id="username_auto_group">
                                <label for="username_preview">Username (Auto Generate)</label>
                                <input type="text" class="form-control" id="username_preview"
                                    value="{{ $user->username }}" readonly>
                                <small class="form-text text-muted">Username akan dibuat otomatis berdasarkan pilihan
                                    wilayah</small>
                            </div>

                            <div class="form-group" id="username_manual_group" style="display: none;">
                                <label for="username_manual">Username</label>
                                <input type="text" class="form-control" id="username_manual" name="username_manual"
                                    value="{{ $user->username }}">
                                <small class="form-text text-muted">Masukkan username yang diinginkan</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password">Password Baru (kosongkan jika tidak ingin mengubah)</label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-warning">Update</button>
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

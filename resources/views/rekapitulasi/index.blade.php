@extends('layouts.app')

@section('title', 'Rekapitulasi Suara')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Rekapitulasi Suara</h1>
        </div>

        <div class="section-body">
            <h2 class="section-title">Pilih Tipe Pemilihan</h2>
            <p class="section-lead">
                Silakan pilih tipe pemilihan yang ingin Anda rekapitulasi.
            </p>

            <div class="card">
                <div class="card-header">
                    <h4>Pilih Tipe Pemilihan</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('rekapitulasi.store') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="tipe_pemilihan">Tipe Pemilihan</label>
                            <select id="tipe_pemilihan" name="tipe_pemilihan" class="form-control" required>
                                <option value="" disabled selected>Pilih tipe pemilihan</option>
                                <option value="gubernur">Gubernur</option>
                                <option value="walikota">Wali Kota</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Lanjutkan</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
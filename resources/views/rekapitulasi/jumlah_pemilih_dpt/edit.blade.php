@extends('layouts.app')

@section('title', 'Edit Jumlah Pemilih DPT')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Edit Jumlah Pemilih DPT</h1>
    </div>

    <div class="section-body">
        <h2 class="section-title">Formulir Edit Jumlah Pemilih DPT</h2>

        <div class="card">
            <div class="card-header">
                <h4>Edit Jumlah Pemilih DPT</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('jumlah_pemilih_dpt.update', $jumlahPemilihDpt->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="tipe_pemilihan">Tipe Pemilihan</label>
                        <input type="text" class="form-control" id="tipe_pemilihan" name="tipe_pemilihan" value="{{ $jumlahPemilihDpt->tipe_pemilihan }}" required>
                    </div>
                    <div class="form-group">
                        <label for="laki_laki">Laki-laki</label>
                        <input type="number" class="form-control" id="laki_laki" name="laki_laki" value="{{ $jumlahPemilihDpt->laki_laki }}" required>
                    </div>
                    <div class="form-group">
                        <label for="perempuan">Perempuan</label>
                        <input type="number" class="form-control" id="perempuan" name="perempuan" value="{{ $jumlahPemilihDpt->perempuan }}" required>
                    </div>
                    <div class="form-group">
                        <label for="jumlah">Jumlah</label>
                        <input type="number" class="form-control" id="jumlah" name="jumlah" value="{{ $jumlahPemilihDpt->jumlah }}" required>
                    </div>
                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-success">Update</button>
                        <a href="{{ route('jumlah_pemilih_dpt.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
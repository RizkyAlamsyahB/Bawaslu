@extends('layouts.app')

@section('title', 'Edit Jumlah Pemilih DPK')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Edit Jumlah Pemilih DPK</h1>
    </div>

    <div class="section-body">
        <h2 class="section-title">Formulir Edit Jumlah Pemilih DPK</h2>

        <div class="card">
            <div class="card-header">
                <h4>Edit Jumlah Pemilih DPK</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('jumlah_pemilih_dpk.update', $jumlahPemilihDpk->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="tipe_pemilihan">Tipe Pemilihan</label>
                        <input type="text" class="form-control" id="tipe_pemilihan" name="tipe_pemilihan" value="{{ $jumlahPemilihDpk->tipe_pemilihan }}" required>
                    </div>
                    <div class="form-group">
                        <label for="laki_laki">Laki-laki</label>
                        <input type="number" class="form-control" id="laki_laki" name="laki_laki" value="{{ $jumlahPemilihDpk->laki_laki }}" required>
                    </div>
                    <div class="form-group">
                        <label for="perempuan">Perempuan</label>
                        <input type="number" class="form-control" id="perempuan" name="perempuan" value="{{ $jumlahPemilihDpk->perempuan }}" required>
                    </div>
                    <div class="form-group">
                        <label for="jumlah">Jumlah</label>
                        <input type="number" class="form-control" id="jumlah" name="jumlah" value="{{ $jumlahPemilihDpk->jumlah }}" required>
                    </div>
                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-success">Update</button>
                        <a href="{{ route('jumlah_pemilih_dpk.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
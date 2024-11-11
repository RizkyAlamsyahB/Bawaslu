@extends('layouts.app')

@section('title', 'Edit Pengguna Hak Pilih DPK')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Edit Pengguna Hak Pilih DPK</h1>
    </div>

    <div class="section-body">
        <h2 class="section-title">Formulir Edit Pengguna Hak Pilih DPK</h2>

        <div class="card">
            <div class="card-header">
                <h4>Edit Pengguna Hak Pilih DPK</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('pengguna_hak_pilih_dpk.update', $penggunaHakPilihDpk->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="tipe_pemilihan">Tipe Pemilihan</label>
                        <input type="text" class="form-control" id="tipe_pemilihan" name="tipe_pemilihan" value="{{ $penggunaHakPilihDpk->tipe_pemilihan }}" required>
                    </div>
                    <div class="form-group">
                        <label for="laki_laki">Laki-laki</label>
                        <input type="number" class="form-control" id="laki_laki" name="laki_laki" value="{{ $penggunaHakPilihDpk->laki_laki }}" required>
                    </div>
                    <div class="form-group">
                        <label for="perempuan">Perempuan</label>
                        <input type="number" class="form-control" id="perempuan" name="perempuan" value="{{ $penggunaHakPilihDpk->perempuan }}" required>
                    </div>
                    <div class="form-group">
                        <label for="jumlah">Jumlah</label>
                        <input type="number" class="form-control" id="jumlah" name="jumlah" value="{{ $penggunaHakPilihDpk->jumlah }}" required>
                    </div>
                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-success">Update</button>
                        <a href="{{ route('pengguna_hak_pilih_dpk.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
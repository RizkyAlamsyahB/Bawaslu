<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\DataSuaraSah;
use Illuminate\Http\Request;
use App\Models\PasanganCalon;
use App\Models\TipePemilihan;
use App\Models\DataSuaraTotal;
use App\Models\DataSuaraPaslon;
use App\Models\JumlahPemilihDpt;
use App\Models\PenggunaHakPilihDpk;
use App\Models\PenggunaHakPilihDpt;
use App\Models\PenggunaanSuratSuara;
use App\Models\PenggunaHakPilihDptb;
use App\Models\UraianHasilPengawasan;
use App\Models\JumlahPenggunaHakPilih;
use App\Models\PenggunaHakPilihDisabilitas;

class RekapitulasiController extends Controller
{
    private function checkIfAllDataExists($tipePemilihanId)
    {
        $userId = auth()->id(); // Ambil ID user yang sedang login


        return JumlahPemilihDpt::where('tipe_pemilihan_id', $tipePemilihanId)->where('user_id', $userId)->exists() &&
            PenggunaHakPilihDpt::where('tipe_pemilihan_id', $tipePemilihanId)->where('user_id', $userId)->exists() &&
            PenggunaHakPilihDptb::where('tipe_pemilihan_id', $tipePemilihanId)->where('user_id', $userId)->exists() &&
            PenggunaHakPilihDpk::where('tipe_pemilihan_id', $tipePemilihanId)->where('user_id', $userId)->exists() &&
            JumlahPenggunaHakPilih::where('tipe_pemilihan_id', $tipePemilihanId)->where('user_id', $userId)->exists() &&
            PenggunaanSuratSuara::where('tipe_pemilihan_id', $tipePemilihanId)->where('user_id', $userId)->exists() &&
            PenggunaHakPilihDisabilitas::where('tipe_pemilihan_id', $tipePemilihanId)->where('user_id', $userId)->exists() &&
            DataSuaraPaslon::where('tipe_pemilihan_id', $tipePemilihanId)->where('user_id', $userId)->exists() &&
            DataSuaraTotal::where('tipe_pemilihan_id', $tipePemilihanId)->where('user_id', $userId)->exists() &&
            UraianHasilPengawasan::where('tipe_pemilihan_id', $tipePemilihanId)->where('user_id', $userId)->exists();
    }

    public function create($tipe_pemilihan_id)
{
    $tipePemilihan = TipePemilihan::findOrFail($tipe_pemilihan_id);

    if ($this->checkIfAllDataExists($tipe_pemilihan_id)) {
        return redirect()
            ->route('dashboard')
            ->with('error', 'Data untuk pemilihan ' . $tipePemilihan->nama . ' sudah lengkap diisi!');
    }

    $currentStep = session('current_step', 1);

    // Load data paslon untuk step 8
    $pasanganCalon = ($currentStep == 8)
        ? PasanganCalon::where('tipe_pemilihan_id', $tipePemilihan->id)
            ->orderBy('nomor_urut')
            ->get()
        : [];

    return view('rekapitulasi.wizard', compact('tipePemilihan', 'currentStep', 'pasanganCalon'));
}

}

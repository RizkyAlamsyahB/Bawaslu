<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\DataSuaraSah;
use Illuminate\Http\Request;
use App\Models\PasanganCalon;
use App\Models\TipePemilihan;
use App\Models\JumlahPemilihDpk;
use App\Models\JumlahPemilihDpt;
use App\Models\JumlahDataPemilih;
use App\Models\JumlahPemilihDptb;
use App\Models\PenggunaHakPilihDpk;
use App\Models\PenggunaHakPilihDpt;
use App\Models\PenggunaanSuratSuara;
use App\Models\PenggunaHakPilihDptb;
use App\Models\JumlahPenggunaHakPilih;
use App\Models\JumlahPemilihDisabilitas;
use App\Models\PenggunaHakPilihDisabilitas;

class RekapitulasiController extends Controller
{

    private function checkIfAllDataExists($tipePemilihanId)
    {
        return JumlahPemilihDpt::where('tipe_pemilihan_id', $tipePemilihanId)->exists() &&
               JumlahPemilihDptb::where('tipe_pemilihan_id', $tipePemilihanId)->exists() &&
               JumlahPemilihDpk::where('tipe_pemilihan_id', $tipePemilihanId)->exists() &&
               JumlahDataPemilih::where('tipe_pemilihan_id', $tipePemilihanId)->exists() &&
               PenggunaHakPilihDpt::where('tipe_pemilihan_id', $tipePemilihanId)->exists() &&
               PenggunaHakPilihDptb::where('tipe_pemilihan_id', $tipePemilihanId)->exists() &&
               PenggunaHakPilihDpk::where('tipe_pemilihan_id', $tipePemilihanId)->exists() &&
               JumlahPenggunaHakPilih::where('tipe_pemilihan_id', $tipePemilihanId)->exists() &&
               JumlahPemilihDisabilitas::where('tipe_pemilihan_id', $tipePemilihanId)->exists() &&
               PenggunaHakPilihDisabilitas::where('tipe_pemilihan_id', $tipePemilihanId)->exists() &&
               PenggunaanSuratSuara::where('tipe_pemilihan_id', $tipePemilihanId)->exists() &&
               DataSuaraSah::whereHas('pasanganCalon', function($query) use ($tipePemilihanId) {
                   $query->where('tipe_pemilihan_id', $tipePemilihanId);
               })->exists();
    }

    public function create($tipe_pemilihan_id)
    {
        $tipePemilihan = TipePemilihan::findOrFail($tipe_pemilihan_id);

        // Cek apakah semua data sudah diisi
        if ($this->checkIfAllDataExists($tipe_pemilihan_id)) {
            return redirect()->route('dashboard')
                           ->with('error', 'Data untuk pemilihan ' . $tipePemilihan->nama . ' sudah lengkap diisi!');
        }

        $currentStep = session('current_step', 1);
        $pasanganCalon = [];
        if ($currentStep == 12) {
            $pasanganCalon = PasanganCalon::where('tipe_pemilihan_id', $tipePemilihan->id)
                ->orderBy('nomor_urut')
                ->get();
        }

        return view('rekapitulasi.wizard', compact('tipePemilihan', 'currentStep', 'pasanganCalon'));
    }




    public function gubernurIndex()
    {
        $tipePemilihan = TipePemilihan::where('nama', 'Gubernur')->first();
        $data = JumlahPemilihDpt::with('tipePemilihan')
            ->where('tipe_pemilihan_id', $tipePemilihan->id)
            ->latest()
            ->get();

        return view('rekapitulasi.gubernur.index', compact('data'));
    }

    public function walikotaIndex()
    {
        $tipePemilihan = TipePemilihan::where('nama', 'Walikota')->first();
        $data = JumlahPemilihDpt::with('tipePemilihan')
            ->where('tipe_pemilihan_id', $tipePemilihan->id)
            ->latest()
            ->get();

        return view('rekapitulasi.walikota.index', compact('data'));
    }
}

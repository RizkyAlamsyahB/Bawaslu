<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PasanganCalon;
use App\Models\TipePemilihan;
use App\Models\JumlahPemilihDpt;
use App\Models\JumlahPemilihDptb;

class RekapitulasiController extends Controller
{
    public function create($tipe_pemilihan_id)
    {
        $tipePemilihan = TipePemilihan::findOrFail($tipe_pemilihan_id);
        $currentStep = session('current_step', 1);

        // Ambil data pasangan calon untuk step 12
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

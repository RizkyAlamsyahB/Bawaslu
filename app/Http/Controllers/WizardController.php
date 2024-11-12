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

class WizardController extends Controller
{
    // Tambahkan method untuk cek apakah sudah pernah diisi
    private function checkIfDataExists($tipePemilihanId)
    {
        // Cek data sesuai dengan current step
        $currentStep = session('current_step', 1);

        switch ($currentStep) {
            case 1:
                return JumlahPemilihDpt::where('tipe_pemilihan_id', $tipePemilihanId)->exists();
            case 2:
                return JumlahPemilihDptb::where('tipe_pemilihan_id', $tipePemilihanId)->exists();
            case 3:
                return JumlahPemilihDpk::where('tipe_pemilihan_id', $tipePemilihanId)->exists();
            case 4:
                return JumlahDataPemilih::where('tipe_pemilihan_id', $tipePemilihanId)->exists();
            case 5:
                return PenggunaHakPilihDpt::where('tipe_pemilihan_id', $tipePemilihanId)->exists();
            case 6:
                return PenggunaHakPilihDptb::where('tipe_pemilihan_id', $tipePemilihanId)->exists();
            case 7:
                return PenggunaHakPilihDpk::where('tipe_pemilihan_id', $tipePemilihanId)->exists();
            case 8:
                return JumlahPenggunaHakPilih::where('tipe_pemilihan_id', $tipePemilihanId)->exists();
            case 9:
                return JumlahPemilihDisabilitas::where('tipe_pemilihan_id', $tipePemilihanId)->exists();
            case 10:
                return PenggunaHakPilihDisabilitas::where('tipe_pemilihan_id', $tipePemilihanId)->exists();
            case 11:
                return PenggunaanSuratSuara::where('tipe_pemilihan_id', $tipePemilihanId)->exists();
            case 12:
                return DataSuaraSah::whereHas('pasanganCalon', function ($query) use ($tipePemilihanId) {
                    $query->where('tipe_pemilihan_id', $tipePemilihanId);
                })->exists();
            default:
                return false;
        }
    }

    public function gubernur()
    {
        $tipePemilihan = TipePemilihan::where('nama', 'gubernur')->first();

        // Cek apakah data sudah ada
        if ($this->checkIfDataExists($tipePemilihan->id)) {
            return redirect()->route('dashboard')->with('error', 'Data untuk pemilihan Gubernur sudah pernah diisi!');
        }

        $currentStep = session('current_step', 1);
        $pasanganCalon = [];
        if ($currentStep == 12) {
            $pasanganCalon = PasanganCalon::where('tipe_pemilihan_id', $tipePemilihan->id)
                ->orderBy('nomor_urut')
                ->get();
        }

        return view('wizard.form', compact('tipePemilihan', 'currentStep', 'pasanganCalon'));
    }

    public function walikota()
    {
        $tipePemilihan = TipePemilihan::where('nama', 'walikota')->first();

        // Cek apakah data sudah ada
        if ($this->checkIfDataExists($tipePemilihan->id)) {
            return redirect()->route('dashboard')->with('error', 'Data untuk pemilihan Walikota sudah pernah diisi!');
        }

        $currentStep = session('current_step', 1);
        $pasanganCalon = [];
        if ($currentStep == 12) {
            $pasanganCalon = PasanganCalon::where('tipe_pemilihan_id', $tipePemilihan->id)
                ->orderBy('nomor_urut')
                ->get();
        }

        return view('wizard.form', compact('tipePemilihan', 'currentStep', 'pasanganCalon'));
    }

    public function create()
    {
        $tipePemilihan = TipePemilihan::where('nama', request()->segment(1))->first();

        // Cek apakah data sudah ada
        if ($this->checkIfDataExists($tipePemilihan->id)) {
            return redirect()->route('dashboard')->with('error', 'Data untuk pemilihan ini sudah pernah diisi!');
        }

        $currentStep = session('current_step', 1);
        $pasanganCalon = [];
        if ($currentStep == 12) {
            $pasanganCalon = PasanganCalon::where('tipe_pemilihan_id', $tipePemilihan->id)
                ->orderBy('nomor_urut')
                ->get();
        }

        return view('wizard.form', compact('tipePemilihan', 'currentStep', 'pasanganCalon'));
    }

    public function store(Request $request)
    {
        $currentStep = session('current_step', 1);
        if ($this->checkIfDataExists($request->tipe_pemilihan_id)) {
            return redirect()->route('dashboard')->with('error', 'Data untuk pemilihan ini sudah pernah diisi!');
        }

        if ($currentStep >= 1 && $currentStep <= 10) {
            $request->validate(
                [
                    'laki_laki' => 'required|integer|min:0',
                    'perempuan' => 'required|integer|min:0',
                    'jumlah' => 'required|integer|min:0',
                ],
                [
                    'laki_laki.required' => 'Kolom laki-laki harus diisi',
                    'perempuan.required' => 'Kolom perempuan harus diisi',
                    'jumlah.required' => 'Kolom jumlah harus diisi',
                    'laki_laki.min' => 'Jumlah laki-laki tidak boleh negatif',
                    'perempuan.min' => 'Jumlah perempuan tidak boleh negatif',
                    'jumlah.min' => 'Jumlah tidak boleh negatif',
                ],
            );

            // Verifikasi jumlah
            $calculatedJumlah = $request->laki_laki + $request->perempuan;
            if ($calculatedJumlah != $request->jumlah) {
                return back()
                    ->withInput()
                    ->withErrors(['jumlah' => 'Jumlah yang diinputkan harus sama dengan total laki-laki dan perempuan']);
            }

            switch ($currentStep) {
                case 1:
                    JumlahPemilihDpt::create([
                        'id' => Str::uuid(),
                        'tipe_pemilihan_id' => $request->tipe_pemilihan_id,
                        'laki_laki' => $request->laki_laki,
                        'perempuan' => $request->perempuan,
                        'jumlah' => $request->jumlah,
                    ]);
                    break;

                case 2:
                    JumlahPemilihDptb::create([
                        'id' => Str::uuid(),
                        'tipe_pemilihan_id' => $request->tipe_pemilihan_id,
                        'laki_laki' => $request->laki_laki,
                        'perempuan' => $request->perempuan,
                        'jumlah' => $request->jumlah,
                    ]);
                    break;

                case 3:
                    JumlahPemilihDpk::create([
                        'id' => Str::uuid(),
                        'tipe_pemilihan_id' => $request->tipe_pemilihan_id,
                        'laki_laki' => $request->laki_laki,
                        'perempuan' => $request->perempuan,
                        'jumlah' => $request->jumlah,
                    ]);
                    break;

                case 4:
                    JumlahDataPemilih::create([
                        'id' => Str::uuid(),
                        'tipe_pemilihan_id' => $request->tipe_pemilihan_id,
                        'laki_laki' => $request->laki_laki,
                        'perempuan' => $request->perempuan,
                        'jumlah' => $request->jumlah,
                    ]);
                    break;

                case 5:
                    PenggunaHakPilihDpt::create([
                        'id' => Str::uuid(),
                        'tipe_pemilihan_id' => $request->tipe_pemilihan_id,
                        'laki_laki' => $request->laki_laki,
                        'perempuan' => $request->perempuan,
                        'jumlah' => $request->jumlah,
                    ]);
                    break;

                case 6:
                    PenggunaHakPilihDptb::create([
                        'id' => Str::uuid(),
                        'tipe_pemilihan_id' => $request->tipe_pemilihan_id,
                        'laki_laki' => $request->laki_laki,
                        'perempuan' => $request->perempuan,
                        'jumlah' => $request->jumlah,
                    ]);
                    break;

                case 7:
                    PenggunaHakPilihDpk::create([
                        'id' => Str::uuid(),
                        'tipe_pemilihan_id' => $request->tipe_pemilihan_id,
                        'laki_laki' => $request->laki_laki,
                        'perempuan' => $request->perempuan,
                        'jumlah' => $request->jumlah,
                    ]);
                    break;

                case 8:
                    JumlahPenggunaHakPilih::create([
                        'id' => Str::uuid(),
                        'tipe_pemilihan_id' => $request->tipe_pemilihan_id,
                        'laki_laki' => $request->laki_laki,
                        'perempuan' => $request->perempuan,
                        'jumlah' => $request->jumlah,
                    ]);
                    break;

                case 9:
                    JumlahPemilihDisabilitas::create([
                        'id' => Str::uuid(),
                        'tipe_pemilihan_id' => $request->tipe_pemilihan_id,
                        'laki_laki' => $request->laki_laki,
                        'perempuan' => $request->perempuan,
                        'jumlah' => $request->jumlah,
                    ]);
                    break;

                case 10:
                    PenggunaHakPilihDisabilitas::create([
                        'id' => Str::uuid(),
                        'tipe_pemilihan_id' => $request->tipe_pemilihan_id,
                        'laki_laki' => $request->laki_laki,
                        'perempuan' => $request->perempuan,
                        'jumlah' => $request->jumlah,
                    ]);
                    break;
            }
        } elseif ($currentStep == 11) {
            $request->validate(
                [
                    'tipe_pemilihan' => 'required',
                    'surat_suara_diterima' => 'required|integer|min:0',
                    'surat_suara_dikembalikan' => 'required|integer|min:0',
                    'surat_suara_tidak_digunakan' => 'required|integer|min:0',
                    'surat_suara_digunakan' => 'required|integer|min:0',
                ],
                [
                    'surat_suara_diterima.required' => 'Jumlah surat suara yang diterima harus diisi',
                    'surat_suara_dikembalikan.required' => 'Jumlah surat suara dikembalikan harus diisi',
                    'surat_suara_tidak_digunakan.required' => 'Jumlah surat suara tidak digunakan harus diisi',
                    'surat_suara_digunakan.required' => 'Jumlah surat suara digunakan harus diisi',
                ],
            );

            // Verifikasi perhitungan
            $totalPenggunaan = $request->surat_suara_dikembalikan + $request->surat_suara_tidak_digunakan + $request->surat_suara_digunakan;

            if ($totalPenggunaan != $request->surat_suara_diterima) {
                return back()
                    ->withInput()
                    ->withErrors(['Total surat suara yang digunakan, dikembalikan, dan tidak digunakan harus sama dengan jumlah yang diterima']);
            }

            PenggunaanSuratSuara::create([
                'id' => Str::uuid(),
                'tipe_pemilihan_id' => $request->tipe_pemilihan_id,
                'surat_suara_diterima' => $request->surat_suara_diterima,
                'surat_suara_dikembalikan' => $request->surat_suara_dikembalikan,
                'surat_suara_tidak_digunakan' => $request->surat_suara_tidak_digunakan,
                'surat_suara_digunakan' => $request->surat_suara_digunakan,
            ]);
        } elseif ($currentStep == 12) {
            $request->validate([
                'pasangan_calon_id' => 'required|exists:pasangan_calons,id',
                'jumlah_suara_sah' => 'required|integer|min:0',
                'jumlah_suara_tidak_sah' => 'required|integer|min:0',
                'total_suara_sah_dan_tidak_sah' => 'required|integer|min:0',
            ]);

            DataSuaraSah::create([
                'id' => Str::uuid(),
                'pasangan_calon_id' => $request->pasangan_calon_id,
                'jumlah_suara_sah' => $request->jumlah_suara_sah,
                'jumlah_suara_tidak_sah' => $request->jumlah_suara_tidak_sah,
                'total_suara_sah_dan_tidak_sah' => $request->total_suara_sah_dan_tidak_sah,
            ]);

            // Reset wizard karena sudah selesai
            session()->forget('current_step');
            return redirect()->route('dashboard')->with('success', 'Semua data berhasil disimpan!');
        }

        // Set next step dan redirect
        session(['current_step' => $currentStep + 1]);
        return redirect()->back()->with('success', 'Data berhasil disimpan, silahkan lanjutkan pengisian.');
    }
}

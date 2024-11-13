<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\DataSuaraSah;
use Illuminate\Http\Request;
use App\Models\PasanganCalon;
use App\Models\TipePemilihan;
use App\Models\DataSuaraTotal;
use App\Models\DataSuaraPaslon;
use App\Models\JumlahPemilihDpk;
use App\Models\JumlahPemilihDpt;
use App\Models\JumlahDataPemilih;
use App\Models\JumlahPemilihDptb;
use App\Models\PenggunaHakPilihDpk;
use App\Models\PenggunaHakPilihDpt;
use App\Models\PenggunaanSuratSuara;
use App\Models\PenggunaHakPilihDptb;
use App\Models\UraianHasilPengawasan;
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
                return PenggunaHakPilihDpt::where('tipe_pemilihan_id', $tipePemilihanId)->exists();
            case 3:
                return PenggunaHakPilihDptb::where('tipe_pemilihan_id', $tipePemilihanId)->exists();
            case 4:
                return PenggunaHakPilihDpk::where('tipe_pemilihan_id', $tipePemilihanId)->exists();
            case 5:
                return JumlahPenggunaHakPilih::where('tipe_pemilihan_id', $tipePemilihanId)->exists();
            case 6:
                return PenggunaanSuratSuara::where('tipe_pemilihan_id', $tipePemilihanId)->exists();
            case 7:
                return PenggunaHakPilihDisabilitas::where('tipe_pemilihan_id', $tipePemilihanId)->exists();
            case 8:
                return DataSuaraPaslon::where('tipe_pemilihan_id', $tipePemilihanId)->where('user_id', auth()->id())->exists();
            case 9:
                return DataSuaraTotal::where('tipe_pemilihan_id', $tipePemilihanId)->where('user_id', auth()->id())->exists();
            case 10:
                return UraianHasilPengawasan::where('tipe_pemilihan_id', $tipePemilihanId)->where('user_id', auth()->id())->exists();
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
        if ($currentStep == 8) {
            // Ubah dari 12 ke 8
            $pasanganCalon = PasanganCalon::where('tipe_pemilihan_id', $tipePemilihan->id)
                ->orderBy('nomor_urut')
                ->get();
        }

        return view('rekapitulasi.wizard', compact('tipePemilihan', 'currentStep', 'pasanganCalon'));
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
        if ($currentStep == 8) {
            // Ubah dari 12 ke 8
            $pasanganCalon = PasanganCalon::where('tipe_pemilihan_id', $tipePemilihan->id)
                ->orderBy('nomor_urut')
                ->get();
        }

        return view('rekapitulasi.wizard', compact('tipePemilihan', 'currentStep', 'pasanganCalon'));
    }

   

    public function store(Request $request)
    {
        $currentStep = session('current_step', 1);

        if ($this->checkIfDataExists($request->tipe_pemilihan_id)) {
            return redirect()->route('dashboard')->with('error', 'Data untuk pemilihan ini sudah pernah diisi!');
        }

        // Step 1-5 tetap sama (untuk data pemilih)
        // Step 1-5 untuk data pemilih
        if ($currentStep >= 1 && $currentStep <= 5) {
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
                ],
            );

            // Verifikasi jumlah
            $calculatedJumlah = $request->laki_laki + $request->perempuan;
            if ($calculatedJumlah != $request->jumlah) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'jumlah' => 'Jumlah harus sama dengan total laki-laki dan perempuan',
                    ]);
            }

            // Simpan data sesuai step
            switch ($currentStep) {
                case 1:
                    JumlahPemilihDpt::create([
                        'id' => Str::uuid(),
                        'user_id' => auth()->id(),
                        'tipe_pemilihan_id' => $request->tipe_pemilihan_id,
                        'laki_laki' => $request->laki_laki,
                        'perempuan' => $request->perempuan,
                        'jumlah' => $request->jumlah,
                    ]);
                    break;
                case 2:
                    PenggunaHakPilihDpt::create([
                        'id' => Str::uuid(),
                        'user_id' => auth()->id(),
                        'tipe_pemilihan_id' => $request->tipe_pemilihan_id,
                        'laki_laki' => $request->laki_laki,
                        'perempuan' => $request->perempuan,
                        'jumlah' => $request->jumlah,
                    ]);
                    break;
                case 3:
                    PenggunaHakPilihDptb::create([
                        'id' => Str::uuid(),
                        'user_id' => auth()->id(),
                        'tipe_pemilihan_id' => $request->tipe_pemilihan_id,
                        'laki_laki' => $request->laki_laki,
                        'perempuan' => $request->perempuan,
                        'jumlah' => $request->jumlah,
                    ]);
                    break;
                case 4:
                    PenggunaHakPilihDpk::create([
                        'id' => Str::uuid(),
                        'user_id' => auth()->id(),
                        'tipe_pemilihan_id' => $request->tipe_pemilihan_id,
                        'laki_laki' => $request->laki_laki,
                        'perempuan' => $request->perempuan,
                        'jumlah' => $request->jumlah,
                    ]);
                    break;
                case 5:
                    JumlahPenggunaHakPilih::create([
                        'id' => Str::uuid(),
                        'user_id' => auth()->id(),
                        'tipe_pemilihan_id' => $request->tipe_pemilihan_id,
                        'laki_laki' => $request->laki_laki,
                        'perempuan' => $request->perempuan,
                        'jumlah' => $request->jumlah,
                    ]);
                    break;
            }
        }
        // Step 6 untuk data penggunaan surat suara
        elseif ($currentStep == 6) {
            $request->validate(
                [
                    'surat_suara_diterima' => 'required|integer|min:0',
                    'surat_suara_digunakan' => 'required|integer|min:0',
                    'surat_suara_dikembalikan' => 'required|integer|min:0',
                    'surat_suara_tidak_digunakan' => 'required|integer|min:0',
                ],
                [
                    'surat_suara_diterima.required' => 'Jumlah surat suara yang diterima harus diisi',
                    'surat_suara_digunakan.required' => 'Jumlah surat suara yang digunakan harus diisi',
                    'surat_suara_dikembalikan.required' => 'Jumlah surat suara dikembalikan harus diisi',
                    'surat_suara_tidak_digunakan.required' => 'Jumlah surat suara tidak digunakan harus diisi',
                ],
            );

            // Verifikasi perhitungan
            $totalPenggunaan = $request->surat_suara_digunakan + $request->surat_suara_dikembalikan + $request->surat_suara_tidak_digunakan;

            if ($totalPenggunaan != $request->surat_suara_diterima) {
                return back()
                    ->withInput()
                    ->withErrors(['Total surat suara yang digunakan, dikembalikan, dan tidak digunakan harus sama dengan jumlah yang diterima']);
            }

            PenggunaanSuratSuara::create([
                'id' => Str::uuid(),
                'user_id' => auth()->id(),
                'tipe_pemilihan_id' => $request->tipe_pemilihan_id,
                'surat_suara_diterima' => $request->surat_suara_diterima,
                'surat_suara_digunakan' => $request->surat_suara_digunakan,
                'surat_suara_dikembalikan' => $request->surat_suara_dikembalikan,
                'surat_suara_tidak_digunakan' => $request->surat_suara_tidak_digunakan,
            ]);
        }
        // Step 7 untuk data pemilih disabilitas
        elseif ($currentStep == 7) {
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
                ],
            );

            // Verifikasi jumlah
            $calculatedJumlah = $request->laki_laki + $request->perempuan;
            if ($calculatedJumlah != $request->jumlah) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'jumlah' => 'Jumlah harus sama dengan total laki-laki dan perempuan',
                    ]);
            }

            PenggunaHakPilihDisabilitas::create([
                'id' => Str::uuid(),
                'user_id' => auth()->id(),
                'tipe_pemilihan_id' => $request->tipe_pemilihan_id,
                'laki_laki' => $request->laki_laki,
                'perempuan' => $request->perempuan,
                'jumlah' => $request->jumlah,
            ]);
        }
        // Step 8 untuk data perolehan suara paslon
        elseif ($currentStep == 8) {
            foreach ($request->paslon_suara as $paslon_id => $jumlah_suara) {
                $request->validate([
                    "paslon_suara.$paslon_id" => 'required|integer|min:0',
                ]);

                DataSuaraPaslon::create([
                    'id' => Str::uuid(),
                    'user_id' => auth()->id(),
                    'tipe_pemilihan_id' => $request->tipe_pemilihan_id,
                    'pasangan_calon_id' => $paslon_id,
                    'jumlah_suara' => $jumlah_suara,
                ]);
            }
        }
        // Step 9 (terakhir) untuk data suara sah dan tidak sah
        elseif ($currentStep == 9) {
            $request->validate([
                'jumlah_suara_sah' => 'required|integer|min:0',
                'jumlah_suara_tidak_sah' => 'required|integer|min:0',
                'total_suara_sah_dan_tidak_sah' => 'required|integer|min:0',
            ]);

            // Verifikasi total suara sah sama dengan jumlah suara semua paslon
            $totalSuaraPaslon = DataSuaraPaslon::where('tipe_pemilihan_id', $request->tipe_pemilihan_id)
                ->where('user_id', auth()->id())
                ->sum('jumlah_suara');

            if ($totalSuaraPaslon != $request->jumlah_suara_sah) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'jumlah_suara_sah' => 'Total suara sah harus sama dengan jumlah suara seluruh paslon',
                    ]);
            }

            // Verifikasi total
            if ($request->jumlah_suara_sah + $request->jumlah_suara_tidak_sah != $request->total_suara_sah_dan_tidak_sah) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'total_suara_sah_dan_tidak_sah' => 'Total harus sama dengan penjumlahan suara sah dan tidak sah',
                    ]);
            }

            DataSuaraTotal::create([
                'id' => Str::uuid(),
                'user_id' => auth()->id(),
                'tipe_pemilihan_id' => $request->tipe_pemilihan_id,
                'jumlah_suara_sah' => $request->jumlah_suara_sah,
                'jumlah_suara_tidak_sah' => $request->jumlah_suara_tidak_sah,
                'total_suara_sah_dan_tidak_sah' => $request->total_suara_sah_dan_tidak_sah,
            ]);
        }

        // Step 10 untuk uraian hasil pengawasan
        elseif ($currentStep == 10) {
            $request->validate(
                [
                    'uraian' => 'required|string',
                ],
                [
                    'uraian.required' => 'Uraian hasil pengawasan harus diisi',
                ],
            );

            UraianHasilPengawasan::create([
                'id' => Str::uuid(),
                'user_id' => auth()->id(),
                'tipe_pemilihan_id' => $request->tipe_pemilihan_id,
                'uraian' => $request->uraian,
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

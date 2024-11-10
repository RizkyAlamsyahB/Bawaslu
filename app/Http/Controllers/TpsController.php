<?php

namespace App\Http\Controllers;

use App\Models\Tps;
use League\Csv\Reader;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class TpsController extends Controller
{
    // Hanya admin yang dapat mengakses halaman ini
    public function __construct()
    {
        $this->middleware(['auth', 'role:super_admin']);
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $file = $request->file('csv_file');
            $csv = Reader::createFromPath($file->getPathname(), 'r');
            $csv->setHeaderOffset(0);

            $records = $csv->getRecords();
            $successCount = 0;
            $skipCount = 0;
            $errors = [];

            foreach ($records as $record) {
                $kodeKecamatan = $record['KODE KECAMATAN'];
                $kodeKelurahan = $record['KODE KELURAHAN'];
                $noTps = $record['NO TPS'];

                // Skip if data is incomplete
                if (empty($kodeKecamatan) || empty($kodeKelurahan) || empty($noTps)) {
                    $skipCount++;
                    continue;
                }

                // Format Kecamatan code to 2 digits
                $kodeKecamatan = str_pad($kodeKecamatan, 2, '0', STR_PAD_LEFT);

                // Find the Kecamatan and Kelurahan
                $kecamatan = Kecamatan::where('kode_kecamatan', $kodeKecamatan)->first();
                if (!$kecamatan) {
                    $errors[] = "Kecamatan code '$kodeKecamatan' not found for TPS '$noTps'";
                    $skipCount++;
                    continue;
                }

                $kelurahan = Kelurahan::where('kode_kelurahan', $kodeKelurahan)
                    ->where('kecamatan_id', $kecamatan->id)
                    ->first();

                if (!$kelurahan) {
                    $errors[] = "Kelurahan code '$kodeKelurahan' not found under Kecamatan '$kodeKecamatan' for TPS '$noTps'";
                    $skipCount++;
                    continue;
                }

                // Check if the TPS already exists
                $tpsExists = Tps::where('no_tps', $noTps)
                    ->where('kelurahan_id', $kelurahan->id)
                    ->where('kecamatan_id', $kecamatan->id)
                    ->exists();

                if (!$tpsExists) {
                    $tps = new Tps();
                    $tps->id = Str::uuid();
                    $tps->no_tps = $noTps;
                    $tps->kelurahan_id = $kelurahan->id;
                    $tps->kecamatan_id = $kecamatan->id;
                    $tps->save();
                    $successCount++;
                } else {
                    $skipCount++;
                }
            }

            DB::commit();

            if (!empty($errors)) {
                $message = sprintf('TPS import completed with warnings. %d new records added, %d records skipped. Warnings: %s', $successCount, $skipCount, implode(', ', $errors));
                return redirect()->route('tps.index')->with('warning', $message);
            }

            $message = sprintf('TPS import successful! %d new records added, %d records skipped.', $successCount, $skipCount);

            return redirect()->route('tps.index')->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withErrors(['error' => 'Failed to import data: ' . $e->getMessage()]);
        }
    }

    // Menampilkan data TPS dengan server-side processing
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $tps = Tps::with(['kelurahan.kecamatan']);

            return DataTables::of($tps)
                ->addIndexColumn()
                ->addColumn('kode_kecamatan', function ($row) {
                    return $row->kelurahan->kecamatan->kode_kecamatan;
                })
                ->addColumn('nama_kelurahan', function ($row) {
                    return $row->kelurahan->nama_kelurahan;
                })
                ->addColumn('kode_kelurahan', function ($row) {
                    return $row->kelurahan->kode_kelurahan;
                })
                ->addColumn('action', function ($row) {
                    $actionBtn =
                        '<div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Aksi
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="' .
                        route('tps.edit', $row->id) .
                        '">Edit</a>
                        <a class="dropdown-item" href="#" onclick="deleteTps(\'' .
                        $row->id .
                        '\')">Hapus</a>
                    </div>
                </div>';
                    return $actionBtn;
                })
                ->filterColumn('kode_kecamatan', function ($query, $keyword) {
                    $query->whereHas('kelurahan.kecamatan', function ($q) use ($keyword) {
                        $q->where('kode_kecamatan', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('kode_kelurahan', function ($query, $keyword) {
                    $query->whereHas('kelurahan', function ($q) use ($keyword) {
                        $q->where('kode_kelurahan', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('nama_kelurahan', function ($query, $keyword) {
                    $query->whereHas('kelurahan', function ($q) use ($keyword) {
                        $q->where('nama_kelurahan', 'like', "%{$keyword}%");
                    });
                })
                ->orderColumn('kode_kecamatan', function ($query, $order) {
                    $query->join('kelurahans', 'tps.kelurahan_id', '=', 'kelurahans.id')->join('kecamatans', 'kelurahans.kecamatan_id', '=', 'kecamatans.id')->orderBy('kecamatans.kode_kecamatan', $order);
                })
                ->orderColumn('kode_kelurahan', function ($query, $order) {
                    $query->join('kelurahans', 'tps.kelurahan_id', '=', 'kelurahans.id')->orderBy('kelurahans.kode_kelurahan', $order);
                })
                ->orderColumn('nama_kelurahan', function ($query, $order) {
                    $query->join('kelurahans', 'tps.kelurahan_id', '=', 'kelurahans.id')->orderBy('kelurahans.nama_kelurahan', $order);
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.tps.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kelurahans = Kelurahan::all();
        $kecamatans = Kecamatan::all();
        return view('admin.tps.create', compact('kelurahans', 'kecamatans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Cek dulu apakah kombinasi tersebut sudah ada
            $exists = Tps::where('no_tps', $request->no_tps)
                ->where('kelurahan_id', $request->kelurahan_id)
                ->where('kecamatan_id', $request->kecamatan_id)
                ->exists();

            if ($exists) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('warning', 'TPS dengan nomor tersebut sudah ada di kelurahan dan kecamatan yang dipilih!');
            }

            // Validasi normal
            $validated = $request->validate([
                'no_tps' => 'required|numeric|digits:3',
                'kelurahan_id' => 'required|exists:kelurahans,id',
                'kecamatan_id' => 'required|exists:kecamatans,id',
            ]);

            $tps = new Tps();
            $tps->id = Str::uuid();
            $tps->no_tps = $validated['no_tps'];
            $tps->kelurahan_id = $validated['kelurahan_id'];
            $tps->kecamatan_id = $validated['kecamatan_id'];
            $tps->save();

            return redirect()->route('tps.index')->with('success', 'Data TPS berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data TPS.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $tps = Tps::findOrFail($id);
        $kelurahans = Kelurahan::all();
        $kecamatans = Kecamatan::all();
        return view('admin.tps.edit', compact('tps', 'kelurahans', 'kecamatans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $tps = Tps::findOrFail($id);

            // Cek apakah kombinasi sudah ada (kecuali untuk record yang sedang diedit)
            $exists = Tps::where('no_tps', $request->no_tps)
                ->where('kelurahan_id', $request->kelurahan_id)
                ->where('kecamatan_id', $request->kecamatan_id)
                ->where('id', '!=', $id)
                ->exists();

            if ($exists) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('warning', 'TPS dengan nomor tersebut sudah ada di kelurahan dan kecamatan yang dipilih!');
            }

            // Validasi normal
            $validated = $request->validate([
                'no_tps' => 'required|numeric|digits:3',
                'kelurahan_id' => 'required|exists:kelurahans,id',
                'kecamatan_id' => 'required|exists:kecamatans,id',
            ]);

            $tps->update($validated);

            return redirect()->route('tps.index')->with('success', 'Data TPS berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui data TPS.');
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $tps = Tps::findOrFail($id);
            $tps->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data TPS berhasil dihapus!',
            ]);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menghapus data TPS.',
                ],
                500,
            );
        }
    }

    /**
     * Get TPS by Kecamatan
     */
    // Metode untuk mengambil TPS berdasarkan kelurahan_id
    public function getTpsByKelurahan($kelurahan_id)
    {
        $tps = Tps::where('kelurahan_id', $kelurahan_id)->get(['id', 'no_tps']);

        // Mengembalikan data TPS dalam format JSON
        return response()->json($tps);
    }

    // Metode untuk mengambil TPS berdasarkan kecamatan_id
    public function getTpsByKecamatan($kecamatan_id)
    {
        $tps = Tps::where('kecamatan_id', $kecamatan_id)->get(['id', 'no_tps']);

        // Mengembalikan data TPS dalam format JSON
        return response()->json($tps);
    }
}

<?php

namespace App\Http\Controllers;

use League\Csv\Reader;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class KelurahanController extends Controller
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
                $namaKelurahan = $record['KELURAHAN'];

                // Skip if data is incomplete
                if (empty($kodeKecamatan) || empty($kodeKelurahan) || empty($namaKelurahan)) {
                    $skipCount++;
                    continue;
                }

                // Format Kecamatan code to 2 digits
                $kodeKecamatan = str_pad($kodeKecamatan, 2, '0', STR_PAD_LEFT);

                // Find the Kecamatan
                $kecamatan = Kecamatan::where('kode_kecamatan', $kodeKecamatan)->first();
                if (!$kecamatan) {
                    $errors[] = "Kecamatan code '$kodeKecamatan' not found for Kelurahan '$namaKelurahan'";
                    $skipCount++;
                    continue;
                }

                // Check for duplicate kelurahan name in different kecamatan
                $existingKelurahan = Kelurahan::where('nama_kelurahan', $namaKelurahan)
                    ->whereHas('kecamatan', function ($query) use ($kecamatan) {
                        $query->where('id', '!=', $kecamatan->id);
                    })
                    ->first();

                if ($existingKelurahan) {
                    // Append kecamatan name to kelurahan name for clarity
                    $namaKelurahan = $namaKelurahan . ' (' . $kecamatan->nama_kecamatan . ')';
                }

                // Check if the Kelurahan already exists in this kecamatan
                $kelurahan = Kelurahan::where('kode_kelurahan', $kodeKelurahan)
                    ->where('kecamatan_id', $kecamatan->id)
                    ->first();

                if (!$kelurahan) {
                    $kelurahan = new Kelurahan();
                    $kelurahan->id = Str::uuid();
                    $kelurahan->kode_kelurahan = $kodeKelurahan;
                    $kelurahan->nama_kelurahan = $namaKelurahan;
                    $kelurahan->kecamatan_id = $kecamatan->id;
                    $kelurahan->save();
                    $successCount++;
                } else {
                    $skipCount++;
                }
            }

            DB::commit();

            if (!empty($errors)) {
                $message = sprintf('Kelurahan import completed with warnings. %d new records added, %d records skipped. Warnings: %s', $successCount, $skipCount, implode(', ', $errors));
                return redirect()->route('kelurahan.index')->with('warning', $message);
            }

            $message = sprintf('Kelurahan import successful! %d new records added, %d records skipped.', $successCount, $skipCount);

            return redirect()->route('kelurahan.index')->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withErrors(['error' => 'Failed to import data: ' . $e->getMessage()]);
        }
    }

    // Menampilkan data kelurahan dengan server-side processing

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $kelurahans = Kelurahan::with('kecamatan');

            return DataTables::of($kelurahans)
                ->addIndexColumn()
                ->addColumn('kode_kecamatan', function ($row) {
                    return $row->kecamatan->kode_kecamatan;
                })
                ->addColumn('nama_kecamatan', function ($row) {
                    return $row->kecamatan->nama_kecamatan;
                })
                ->addColumn('action', function ($row) {
                    $actionBtn =
                        '<div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Aksi
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="' .
                        route('kelurahan.edit', $row->id) .
                        '">Edit</a>
                            <a class="dropdown-item" href="#" onclick="deleteKelurahan(\'' .
                        $row->id .
                        '\')">Hapus</a>
                        </div>
                    </div>';
                    return $actionBtn;
                })
                ->filterColumn('kode_kecamatan', function ($query, $keyword) {
                    $query->whereHas('kecamatan', function ($q) use ($keyword) {
                        $q->where('kode_kecamatan', 'like', "%{$keyword}%");
                    });
                })
                ->orderColumn('kode_kecamatan', function ($query, $order) {
                    // Use 'kecamatans' table instead of 'kecamatan'
                    $query->join('kecamatans', 'kelurahans.kecamatan_id', '=', 'kecamatans.id')->orderBy('kecamatans.kode_kecamatan', $order);
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.kelurahan.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kecamatans = Kecamatan::orderBy('nama_kecamatan', 'asc')->get();
        return view('admin.kelurahan.create', compact('kecamatans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Custom validation rule untuk mengecek kombinasi nama_kelurahan dan kecamatan_id
        $validated = $request->validate(
            [
                'kode_kelurahan' => [
                    'required',
                    'numeric',
                    'max:9999999', // Ensures a maximum of 7 numeric digits
                    Rule::unique('kelurahans', 'kode_kelurahan')->where('kecamatan_id', $request->kecamatan_id), // Unique within the specified kecamatan_id
                ],

                'nama_kelurahan' => [
                    'required',
                    'string',
                    'max:100',
                    Rule::unique('kelurahans')
                        ->where(function ($query) use ($request) {
                            return $query->where('nama_kelurahan', $request->nama_kelurahan)->where('kecamatan_id', $request->kecamatan_id);
                        })
                        ->ignore(null, 'id'),
                ],
                'kecamatan_id' => 'required|exists:kecamatans,id',
            ],
            [
                'kode_kelurahan.unique' => 'Kode kelurahan sudah digunakan di kecamatan ini.',
                'nama_kelurahan.unique' => 'Nama kelurahan sudah ada di kecamatan ini.',
            ],
        );

        try {
            // Check for duplicate kelurahan name in different kecamatan
            $existingKelurahan = Kelurahan::where('nama_kelurahan', $validated['nama_kelurahan'])
                ->whereHas('kecamatan', function ($query) use ($validated) {
                    $query->where('id', '!=', $validated['kecamatan_id']);
                })
                ->first();

            // If duplicate exists, append kecamatan name
            if ($existingKelurahan) {
                $kecamatan = Kecamatan::find($validated['kecamatan_id']);
                $validated['nama_kelurahan'] = $validated['nama_kelurahan'] . ' (' . $kecamatan->nama_kecamatan . ')';
            }

            $kelurahan = new Kelurahan();
            $kelurahan->id = Str::uuid();
            $kelurahan->kode_kelurahan = $validated['kode_kelurahan'];
            $kelurahan->nama_kelurahan = $validated['nama_kelurahan'];
            $kelurahan->kecamatan_id = $validated['kecamatan_id'];
            $kelurahan->save();

            return redirect()->route('kelurahan.index')->with('success', 'Data kelurahan berhasil ditambahkan!');
        } catch (\Exception $e) {
            Log::error('Error storing kelurahan: ' . $e->getMessage());
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data kelurahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $kelurahan = Kelurahan::findOrFail($id);
        $kecamatans = Kecamatan::orderBy('nama_kecamatan', 'asc')->get();
        return view('admin.kelurahan.edit', compact('kelurahan', 'kecamatans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $kelurahan = Kelurahan::findOrFail($id);

        $validated = $request->validate([
            'kode_kelurahan' => [
                'required',
                'numeric',
                'max:9999999', // Ensures a maximum of 7 numeric digits
                Rule::unique('kelurahans', 'kode_kelurahan')
                    ->ignore($kelurahan->id) // Ignore current record during update
                    ->where('kecamatan_id', $request->kecamatan_id), // Unique within the specified kecamatan_id
            ],

            'nama_kelurahan' => 'required|string|max:255',
            'kecamatan_id' => 'required|exists:kecamatans,id',
        ]);
        try {
            $kelurahan->update($validated);

            return redirect()->route('kelurahan.index')->with('success', 'Data kelurahan berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan saat memperbarui data kelurahan.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $kelurahan = Kelurahan::findOrFail($id);
            $kelurahan->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data kelurahan berhasil dihapus!',
            ]);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menghapus data kelurahan.',
                ],
                500,
            );
        }
    }

    // di KelurahankController
    public function getKelurahanByKecamatan($kecamatan_id)
    {
        $kelurahans = Kelurahan::where('kecamatan_id', $kecamatan_id)->pluck('nama_kelurahan', 'id');
        return response()->json($kelurahans);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Tps;
use App\Models\Kelurahan;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Cache;

class TpsController extends Controller
{
    // Hanya admin yang dapat mengakses halaman ini
    public function __construct()
    {
        $this->middleware(['auth', 'role:super_admin']);
    }

    // Menampilkan data TPS dengan server-side processing
    public function index()
    {
        if (request()->ajax()) {
            $data = Tps::with(['kelurahan', 'kecamatan'])->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('kelurahan', function ($row) {
                    return $row->kelurahan ? $row->kelurahan->nama_kelurahan : '-';
                })
                ->addColumn('kecamatan', function ($row) {
                    return $row->kecamatan ? $row->kecamatan->nama_kecamatan : '-';
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('tps.edit', $row->id);
                    $deleteUrl = route('tps.destroy', $row->id);

                    return '
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton-' .
                        $row->id .
                        '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Actions
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton-' .
                        $row->id .
                        '">
                                <a href="' .
                        $editUrl .
                        '" class="dropdown-item">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <button class="dropdown-item text-danger btn-delete" data-url="' .
                        $deleteUrl .
                        '">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </div>
                        </div>';
                })
                ->rawColumns(['action'])

                ->make(true);
        }

        return view('admin.tps.index');
    }

    // Menampilkan form untuk membuat TPS
    public function create()
    {
        $kelurahans = Kelurahan::all();
        $kecamatans = Kecamatan::all();

        return view('admin.tps.create', compact('kelurahans', 'kecamatans'));
    }

 


    public function getTpsByKelurahan($kelurahan_id)
    {
        $tps = Tps::where('kelurahan_id', $kelurahan_id)->pluck('no_tps', 'id');
        return response()->json($tps);
    }

    // Menyimpan TPS baru
    public function store(Request $request)
    {
        $request->validate([
            'no_tps' => 'required|string|max:255',
            'kelurahan_id' => 'nullable|exists:kelurahans,id',
            'kecamatan_id' => 'nullable|exists:kecamatans,id',
        ]);

        try {
            Tps::create([
                'no_tps' => $request->no_tps,
                'kelurahan_id' => $request->kelurahan_id,
                'kecamatan_id' => $request->kecamatan_id,
            ]);

            return redirect()->route('tps.index')->with('success', 'TPS berhasil ditambahkan!');
        } catch (\Illuminate\Database\QueryException $e) {
            // Cek apakah error disebabkan oleh duplikasi entri
            if ($e->errorInfo[1] == 1062) {
                return back()->withErrors(['error' => 'TPS dengan kombinasi tersebut sudah ada.']);
            }
            // Jika error bukan karena duplikasi
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()]);
        }
    }

    // Menampilkan form untuk mengedit TPS
    public function edit($no_tps)
    {
        $tps = Tps::findOrFail($no_tps);
        $kelurahans = Kelurahan::all();
        $kecamatans = Kecamatan::all();

        return view('admin.tps.edit', compact('tps', 'kelurahans', 'kecamatans'));
    }

    // Memperbarui data TPS
    public function update(Request $request, $no_tps)
    {
        $tps = Tps::findOrFail($no_tps);

        $request->validate([
            'no_tps' => 'required|string|max:255', // Menghapus aturan unique
            'kelurahan_id' => 'nullable|exists:kelurahans,id',
            'kecamatan_id' => 'nullable|exists:kecamatans,id',
        ]);

        $tps->update([
            'no_tps' => $request->no_tps,
            'kelurahan_id' => $request->kelurahan_id,
            'kecamatan_id' => $request->kecamatan_id,
        ]);

        return redirect()->route('tps.index')->with('success', 'TPS berhasil diperbarui!');
    }

    // Menghapus TPS
    public function destroy($id)
    {
        $tps = Tps::findOrFail($id);
        $tps->delete();

        return redirect()->route('tps.index')->with('success', 'TPS berhasil dihapus');
    }
}

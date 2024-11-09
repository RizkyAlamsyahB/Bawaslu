<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Cache;

class KelurahanController extends Controller
{
    // Hanya admin yang dapat mengakses halaman ini
    public function __construct()
    {
        $this->middleware(['auth', 'role:super_admin']);
    }

    // Menampilkan data kelurahan dengan server-side processing
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $kelurahans = Kelurahan::with('kecamatan');

            return DataTables::of($kelurahans)
                ->addIndexColumn()
                ->addColumn('kode_kecamatan', function($row) {
                    return $row->kecamatan->kode_kecamatan;
                })
                ->addColumn('nama_kecamatan', function($row) {
                    return $row->kecamatan->nama_kecamatan;
                })
                ->addColumn('action', function($row){
                    $actionBtn = '<div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Aksi
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="'.route('kelurahan.edit', $row->id).'">Edit</a>
                            <a class="dropdown-item" href="#" onclick="deleteKelurahan(\''.$row->id.'\')">Hapus</a>
                        </div>
                    </div>';
                    return $actionBtn;
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
        $kecamatans = Kecamatan::all();
        return view('admin.kelurahan.create', compact('kecamatans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_kelurahan' => 'required|string|unique:kelurahans,kode_kelurahan,NULL,id,kecamatan_id,' . $request->kecamatan_id,
            'nama_kelurahan' => 'required|string|max:255',
            'kecamatan_id' => 'required|exists:kecamatans,id',
        ]);

        try {
            $kelurahan = new Kelurahan();
            $kelurahan->id = Str::uuid();
            $kelurahan->kode_kelurahan = $validated['kode_kelurahan'];
            $kelurahan->nama_kelurahan = $validated['nama_kelurahan'];
            $kelurahan->kecamatan_id = $validated['kecamatan_id'];
            $kelurahan->save();

            return redirect()
                ->route('kelurahan.index')
                ->with('success', 'Data kelurahan berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data kelurahan.']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $kelurahan = Kelurahan::findOrFail($id);
        $kecamatans = Kecamatan::all();
        return view('admin.kelurahan.edit', compact('kelurahan', 'kecamatans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $kelurahan = Kelurahan::findOrFail($id);

        $validated = $request->validate([
            'kode_kelurahan' => 'required|string|unique:kelurahans,kode_kelurahan,' . $kelurahan->id . ',id,kecamatan_id,' . $request->kecamatan_id,
            'nama_kelurahan' => 'required|string|max:255',
            'kecamatan_id' => 'required|exists:kecamatans,id',
        ]);
        try {
            $kelurahan->update($validated);

            return redirect()
                ->route('kelurahan.index')
                ->with('success', 'Data kelurahan berhasil diperbarui!');
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
                'message' => 'Data kelurahan berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data kelurahan.'
            ], 500);
        }
    }

    // di KelurahankController
public function getKelurahanByKecamatan($kecamatan_id)
{
    $kelurahans = Kelurahan::where('kecamatan_id', $kecamatan_id)->pluck('nama_kelurahan', 'id');
    return response()->json($kelurahans);
}
}

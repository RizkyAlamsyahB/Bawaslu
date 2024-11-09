<?php

namespace App\Http\Controllers;

use App\Models\Tps;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;

class TpsController extends Controller
{
    // Hanya admin yang dapat mengakses halaman ini
    public function __construct()
    {
        $this->middleware(['auth', 'role:super_admin']);
    }

    // Menampilkan data TPS dengan server-side processing
    public function index(Request $request)
{
    if ($request->ajax()) {
        $tps = Tps::with('kelurahan', 'kecamatan');

        return DataTables::of($tps)
            ->addIndexColumn()
            ->addColumn('kode_kecamatan', function($row) {
                return $row->kecamatan->kode_kecamatan; // Tambahkan kolom kode kecamatan
            })
            ->addColumn('nama_kecamatan', function($row) {
                return $row->kecamatan->nama_kecamatan;
            })
            ->addColumn('kode_kelurahan', function($row) {
                return $row->kelurahan->kode_kelurahan; // Tambahkan kolom kode kelurahan
            })
            ->addColumn('nama_kelurahan', function($row) {
                return $row->kelurahan->nama_kelurahan;
            })
            ->addColumn('action', function($row){
                $actionBtn = '<div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Aksi
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="'.route('tps.edit', $row->id).'">Edit</a>
                        <a class="dropdown-item" href="#" onclick="deleteTps(\''.$row->id.'\')">Hapus</a>
                    </div>
                </div>';
                return $actionBtn;
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
        $validated = $request->validate([
            'no_tps' => 'required|string|unique:tps,no_tps,NULL,id,kelurahan_id,{$request->kelurahan_id},kecamatan_id,{$request->kecamatan_id}',
            'kelurahan_id' => 'required|exists:kelurahans,id',
            'kecamatan_id' => 'required|exists:kecamatans,id',
        ]);

        try {
            $tps = new Tps();
            $tps->id = Str::uuid();
            $tps->no_tps = $validated['no_tps'];
            $tps->kelurahan_id = $validated['kelurahan_id'];
            $tps->kecamatan_id = $validated['kecamatan_id'];
            $tps->save();

            return redirect()
                ->route('tps.index')
                ->with('success', 'Data TPS berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data TPS.']);
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
        $tps = Tps::findOrFail($id);

        $validated = $request->validate([
            'no_tps' => [
                'required',
                'string',
                Rule::unique('tps')->ignore($tps->id)->where(function ($query) use ($request) {
                    return $query->where('kelurahan_id', $request->kelurahan_id)
                                ->where('kecamatan_id', $request->kecamatan_id);
                }),
            ],
            'kelurahan_id' => 'required|exists:kelurahans,id',
            'kecamatan_id' => 'required|exists:kecamatans,id',
        ]);

        try {
            $tps->update($validated);

            return redirect()
                ->route('tps.index')
                ->with('success', 'Data TPS berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan saat memperbarui data TPS.']);
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
                'message' => 'Data TPS berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data TPS.'
            ], 500);
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

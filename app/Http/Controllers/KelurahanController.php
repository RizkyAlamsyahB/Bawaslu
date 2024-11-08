<?php

namespace App\Http\Controllers;

use App\Models\Kelurahan;
use App\Models\Kecamatan;
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
    public function index()
    {
        if (request()->ajax()) {
            $data = Kelurahan::with('kecamatan')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('kecamatan', function ($row) {
                    return $row->kecamatan ? $row->kecamatan->nama_kecamatan : '-';
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('kelurahan.edit', $row->id);
                    $deleteUrl = route('kelurahan.destroy', $row->id);

                    return '
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton-' . $row->id . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Actions
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton-' . $row->id . '">
                                    <a href="' . $editUrl . '" class="dropdown-item">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <a href="javascript:void(0);" class="dropdown-item text-danger deleteButton" data-url="' . $deleteUrl . '">
                                        <i class="bi bi-trash"></i> Delete
                                    </a>
                                </div>
                        </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.kelurahan.index');
    }

    // Menampilkan form untuk membuat kelurahan
    public function create()
    {
        $kecamatans = Kecamatan::all();  // Ambil semua kecamatan
        return view('admin.kelurahan.create', compact('kecamatans'));
    }

  

    // Menyimpan kelurahan baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_kelurahan' => 'required|string|max:255',
            'kode_kelurahan' => 'required|max:10',  // Menghapus aturan unique
            'kecamatan_id' => 'required|exists:kecamatans,id',
        ]);

        try {
            Kelurahan::create([
                'nama_kelurahan' => $request->nama_kelurahan,
                'kode_kelurahan' => $request->kode_kelurahan,
                'kecamatan_id' => $request->kecamatan_id,
            ]);

            return redirect()->route('kelurahan.index')->with('success', 'Kelurahan berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->withErrors('Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    // Menampilkan form untuk mengedit kelurahan
    public function edit(Kelurahan $kelurahan)
    {
        $kecamatans = Kecamatan::all();  // Ambil semua kecamatan
        return view('admin.kelurahan.edit', compact('kelurahan', 'kecamatans'));
    }

    // Memperbarui data kelurahan
    public function update(Request $request, Kelurahan $kelurahan)
    {
        $request->validate([
            'nama_kelurahan' => 'required|string|max:255',
            'kode_kelurahan' => 'required|max:10',  // Menghapus aturan unique
            'kecamatan_id' => 'required|exists:kecamatans,id',
        ]);

        $kelurahan->update([
            'nama_kelurahan' => $request->nama_kelurahan,
            'kode_kelurahan' => $request->kode_kelurahan,
            'kecamatan_id' => $request->kecamatan_id,
        ]);

        return redirect()->route('kelurahan.index')->with('success', 'Kelurahan berhasil diperbarui!');
    }

    // Menghapus kelurahan
    public function destroy(Kelurahan $kelurahan)
    {
        $kelurahan->delete();

        // Clear cache after deletion
        Cache::forget('kelurahan_data');

        return redirect()->route('kelurahan.index')->with('success', 'Kelurahan berhasil dihapus!');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Cache;

class KecamatanController extends Controller
{
    // Hanya admin yang dapat mengakses halaman ini
    public function __construct()
    {
        $this->middleware(['auth', 'role:super_admin']);
    }

    // Menampilkan data kecamatan dengan server-side processing

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $kecamatans = Kecamatan::query();

            return DataTables::of($kecamatans)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn = '<div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Aksi
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="'.route('kecamatan.edit', $row->id).'">Edit</a>
                            <a class="dropdown-item" href="#" onclick="deleteKecamatan(\''.$row->id.'\')">Hapus</a>
                        </div>
                    </div>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.kecamatan.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.kecamatan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_kecamatan' => 'required|string|unique:kecamatans,kode_kecamatan',
            'nama_kecamatan' => 'required|string|max:255',
        ]);

        try {
            $kecamatan = new Kecamatan();
            $kecamatan->id = Str::uuid();
            $kecamatan->kode_kecamatan = $validated['kode_kecamatan'];
            $kecamatan->nama_kecamatan = $validated['nama_kecamatan'];
            $kecamatan->save();

            return redirect()
                ->route('kecamatan.index')
                ->with('success', 'Data kecamatan berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data kecamatan.']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $kecamatan = Kecamatan::findOrFail($id);
        return view('admin.kecamatan.edit', compact('kecamatan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $kecamatan = Kecamatan::findOrFail($id);

        $validated = $request->validate([
            'kode_kecamatan' => [
                'required',
                'string',
                Rule::unique('kecamatans')->ignore($kecamatan->id),
            ],
            'nama_kecamatan' => 'required|string|max:255',
        ]);

        try {
            $kecamatan->update($validated);

            return redirect()
                ->route('kecamatan.index')
                ->with('success', 'Data kecamatan berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan saat memperbarui data kecamatan.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $kecamatan = Kecamatan::findOrFail($id);
            $kecamatan->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data kecamatan berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data kecamatan.'
            ], 500);
        }
    }

}

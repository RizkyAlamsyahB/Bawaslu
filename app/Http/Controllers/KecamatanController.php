<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use Illuminate\Http\Request;
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

    public function index()
    {
        if (request()->ajax()) {
            $data = Kecamatan::query();

            return DataTables::of($data)
                ->addIndexColumn() // Tambahkan baris ini
                ->addColumn('action', function ($row) {
                    $editUrl = route('kecamatan.edit', $row->id);
                    $deleteUrl = route('kecamatan.destroy', $row->id);

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

        return view('admin.kecamatan.index');
    }



    // Menampilkan form untuk membuat kecamatan
    public function create()
    {
        return view('admin.kecamatan.create');
    }

    // Menyimpan kecamatan baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_kecamatan' => 'required|string|max:255',
            'kode_kecamatan' => 'required|unique:kecamatans,kode_kecamatan|max:10',
        ]);

        try {
            Kecamatan::create([
                'nama_kecamatan' => $request->nama_kecamatan,
                'kode_kecamatan' => $request->kode_kecamatan,
            ]);

            return redirect()->route('kecamatan.index')->with('success', 'Kecamatan berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->withErrors('Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    // Menampilkan form untuk mengedit kecamatan
    public function edit(Kecamatan $kecamatan)
    {
        return view('admin.kecamatan.edit', compact('kecamatan'));
    }

    // Memperbarui data kecamatan
    public function update(Request $request, Kecamatan $kecamatan)
    {
        $request->validate([
            'nama_kecamatan' => 'required|string|max:255',
            'kode_kecamatan' => 'required|unique:kecamatans,kode_kecamatan,' . $kecamatan->id . '|max:10',
        ]);

        $kecamatan->update([
            'nama_kecamatan' => $request->nama_kecamatan,
            'kode_kecamatan' => $request->kode_kecamatan,
        ]);

        return redirect()->route('kecamatan.index')->with('success', 'Kecamatan berhasil diperbarui!');
    }

    // Menghapus kecamatan
    public function destroy(Kecamatan $kecamatan)
    {
        $kecamatan->delete();

        // Clear cache after deletion
        Cache::forget('kecamatan_data');

        return redirect()->route('kecamatan.index')->with('success', 'Kecamatan berhasil dihapus!');
    }
}

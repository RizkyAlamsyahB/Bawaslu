<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\JumlahDataPemilih;

class JumlahDataPemilihController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:tps,super_admin']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = JumlahDataPemilih::all();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    if (auth()->user()->role === 'super_admin') {
                        $actionBtn = '<div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Aksi
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="' . route('jumlah_data_pemilih.edit', $row->id) . '">Edit</a>
                                <a class="dropdown-item" href="#" onclick="openDeleteModal(\'' . $row->id . '\')">Hapus</a>
                            </div>
                        </div>';
                        return $actionBtn;
                    }
                    return ''; // Jika bukan super_admin, kolom kosong
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    
        return view('rekapitulasi.jumlah_data_pemilih.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('rekapitulasi.jumlah_data_pemilih.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi data
        $request->validate([
            'tipe_pemilihan' => 'required|in:gubernur,walikota',
            'laki_laki' => 'required|integer',
            'perempuan' => 'required|integer',
            'jumlah' => 'required|integer',
        ]);
    
        // Menghitung total jumlah dari input laki-laki dan perempuan
        $calculatedJumlah = $request->laki_laki + $request->perempuan;
        if ((int)$request->jumlah !== $calculatedJumlah) {
            return redirect()->back()
                ->withErrors(['jumlah' => 'Jumlah tidak sesuai dengan total laki-laki dan perempuan.'])
                ->withInput();
        }
    
        try {
            // Mencoba menyimpan data jika tidak ada error
            JumlahDataPemilih::create($request->all());
        } catch (\Exception $e) {
            // Menangkap error dan mengembalikan ke halaman sebelumnya dengan pesan error
            return redirect()->back()
                ->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()])
                ->withInput();
        }
    
        // Mengarahkan ke halaman index dengan pesan sukses
        return redirect()->route('jumlah_data_pemilih.index')->with('success', 'Data berhasil ditambahkan.');
    }
    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Mendapatkan data yang akan diedit
        $jumlahPemilih = JumlahDataPemilih::findOrFail($id);
        return view('rekapitulasi.jumlah_data_pemilih.edit', compact('jumlahPemilih'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validasi data
        $request->validate([
            'tipe_pemilihan' => 'required|in:gubernur,walikota',
            'laki_laki' => 'required|integer',
            'perempuan' => 'required|integer',
            'jumlah' => 'required|integer',
        ]);

        // Menghitung jumlah
        $calculatedJumlah = $request->laki_laki + $request->perempuan;
        if ((int)$request->jumlah !== $calculatedJumlah) {
            return redirect()->back()->withErrors(['jumlah' => 'Jumlah tidak sesuai dengan total laki-laki dan perempuan.'])->withInput();
        }

        try {
            $dataPemilih = JumlahDataPemilih::findOrFail($id);
            $dataPemilih->update($request->all());
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage()])->withInput();
        }

        return redirect()->route('jumlah_data_pemilih.index')->with('success', 'Data berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $dataPemilih = JumlahDataPemilih::findOrFail($id);
            $dataPemilih->delete();
        } catch (\Exception $e) {
            return redirect()->route('jumlah_data_pemilih.index')->withErrors(['error' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage()]);
        }

        return redirect()->route('jumlah_data_pemilih.index')->with('success', 'Data berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\PasanganCalon;
use App\Models\TipePemilihan;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PasanganCalonController extends Controller
{
   public function __construct()
   {
       $this->middleware(['auth', 'role:super_admin']);
   }

   public function index(Request $request)
   {
       if ($request->ajax()) {
           $pasanganCalon = PasanganCalon::with('tipePemilihan');

           return DataTables::of($pasanganCalon)
               ->addIndexColumn()
               ->addColumn('tipe_pemilihan', function ($row) {
                   return $row->tipePemilihan->nama;
               })
               ->addColumn('action', function ($row) {
                   $actionBtn =
                       '<div class="dropdown">
                           <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                               Aksi
                           </button>
                           <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                               <a class="dropdown-item" href="' . route('pasangan_calon.edit', ['pasangan_calon' => $row->id]) . '">Edit</a>
                               <a class="dropdown-item" href="#" onclick="deleteConfirmation(\'' . $row->id . '\')">Hapus</a>
                           </div>
                       </div>';
                   return $actionBtn;
               })
               ->rawColumns(['action'])
               ->make(true);
       }

       return view('admin.pasangan-calon.index');
   }

   public function create()
   {
       $tipePemilihans = TipePemilihan::all();
       return view('admin.pasangan-calon.create', compact('tipePemilihans'));
   }

   public function store(Request $request)
   {
       $request->validate([
           'nama_pasangan' => 'required|string|max:255',
           'nomor_urut' => 'required|numeric|min:1',
           'tipe_pemilihan_id' => 'required|exists:tipe_pemilihans,id',
       ]);

       PasanganCalon::create($request->all());

       return redirect()->route('pasangan_calon.index')
           ->with('success', 'Data pasangan calon berhasil dibuat.');
   }

   public function edit(PasanganCalon $pasanganCalon)
   {
       $tipePemilihans = TipePemilihan::all();
       return view('admin.pasangan-calon.edit', compact('pasanganCalon', 'tipePemilihans'));
   }

   public function update(Request $request, PasanganCalon $pasanganCalon)
   {
       $request->validate([
           'nama_pasangan' => 'required|string|max:255',
           'nomor_urut' => 'required|numeric|min:1',
           'tipe_pemilihan_id' => 'required|exists:tipe_pemilihans,id',
       ]);

       $pasanganCalon->update($request->all());

       return redirect()->route('pasangan_calon.index')
           ->with('success', 'Data pasangan calon berhasil diperbarui.');
   }

   public function destroy(PasanganCalon $pasanganCalon)
   {
       try {
           $pasanganCalon->delete();
           return response()->json([
               'success' => true,
               'message' => 'Data pasangan calon berhasil dihapus.'
           ]);
       } catch (\Exception $e) {
           return response()->json([
               'success' => false,
               'message' => 'Gagal menghapus data pasangan calon.'
           ], 500);
       }
   }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipePemilihan;
use Yajra\DataTables\DataTables;


class TipePemilihanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:super_admin']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $tipePemilihans = TipePemilihan::query();

            return DataTables::of($tipePemilihans)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn =
                        '<div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Aksi
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="' . route('tipe_pemilihan.edit', ['tipe_pemilihan' => $row->id]) . '">Edit</a>
                                <a class="dropdown-item" href="#" onclick="deleteConfirmation(\'' . $row->id . '\')">Hapus</a>
                            </div>
                        </div>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.tipe-pemilihan.index');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.tipe-pemilihan.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        TipePemilihan::create($request->all());

        return redirect()->route('tipe_pemilihan.index')->with('success', 'Tipe pemilihan berhasil dibuat.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TipePemilihan  $tipePemilihan
     * @return \Illuminate\Http\Response
     */
    public function edit(TipePemilihan $tipePemilihan)
    {
        return view('admin.tipe-pemilihan.edit', compact('tipePemilihan'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TipePemilihan  $tipePemilihan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TipePemilihan $tipePemilihan)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $tipePemilihan->update($request->all());

        return redirect()->route('tipe_pemilihan.index')->with('success', 'Tipe pemilihan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TipePemilihan  $tipePemilihan
     * @return \Illuminate\Http\Response
     */
    public function destroy(TipePemilihan $tipePemilihan)
    {
        $tipePemilihan->delete();

        return redirect()->route('tipe_pemilihan.index')->with('success', 'Tipe pemilihan berhasil dihapus.');
    }
}

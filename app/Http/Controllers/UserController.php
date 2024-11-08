<?php

namespace App\Http\Controllers;


use App\Models\Tps;
use App\Models\User;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:super_admin']);
    }
    public function index()
    {
        if (request()->ajax()) {
            $data = User::with(['kecamatan', 'kelurahan', 'tps']);

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('role', fn($row) => ucfirst($row->role))
                ->addColumn('nama_kecamatan', function ($row) {
                    return $row->kecamatan?->name ?? '-';
                })
                ->addColumn('nama_kelurahan', function ($row) {
                    return $row->kelurahan?->name ?? '-';
                })
                ->addColumn('tps_number', function ($row) {
                    return $row->tps?->number ?? '-';
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('user.edit', $row->id);
                    $deleteUrl = route('user.destroy', $row->id);
                    return '
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle btn-sm" type="button" data-toggle="dropdown">
                                Actions
                            </button>
                            <div class="dropdown-menu">
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

        return view('admin.user.index');
    }


    public function create()
    {
        $kecamatans = Kecamatan::all();
        $kelurahans = Kelurahan::all();
        $tps = Tps::all();

        return view('admin.user.create', compact('kecamatans', 'kelurahans', 'tps'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'username' => 'required|string|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:tps,kecamatan,kelurahan,kota,super_admin',
        ]);

        User::create([
            'id' => Str::uuid(),
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'kecamatan_id' => $request->kecamatan_id,
            'kelurahan_id' => $request->kelurahan_id,
            'tps_id' => $request->tps_id,
        ]);

        return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $kecamatans = Kecamatan::all();
        $kelurahans = Kelurahan::all();
        $tps = Tps::all();

        return view('admin.user.edit', compact('user', 'kecamatans', 'kelurahans', 'tps'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'username' => 'required|string|unique:users,username,' . $id,
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:tps,kecamatan,kelurahan,kota,super_admin',
        ]);

        $user->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'username' => $validated['username'],
            'password' => $request->password ? Hash::make($validated['password']) : $user->password,
            'role' => $validated['role'],
            'kecamatan_id' => $request->kecamatan_id,
            'kelurahan_id' => $request->kelurahan_id,
            'tps_id' => $request->tps_id,
        ]);

        return redirect()->route('user.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['success' => 'User berhasil dihapus.']);
    }

    public function getKelurahans($kecamatanId)
{
    return Kelurahan::where('kecamatan_id', $kecamatanId)->get();
}

public function getTps($kelurahanId)
{
    return Tps::where('kelurahan_id', $kelurahanId)->get();
}

}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Tps;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function __construct()
    {
        // Middleware untuk memastikan hanya super_admin yang bisa mengakses
        $this->middleware(['auth', 'role:super_admin']);
    }

    // Tampilkan daftar user dengan server-side processing
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $users = User::with(['kecamatan:id,nama_kecamatan', 'kelurahan:id,nama_kelurahan', 'tps:id,no_tps'])
                ->select(['id', 'name', 'username', 'role', 'kecamatan_id', 'kelurahan_id', 'tps_id'])
                ->get();

            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('kecamatan', function($row) {
                    return $row->kecamatan ? $row->kecamatan->nama_kecamatan : '-';
                })
                ->addColumn('kelurahan', function($row) {
                    return $row->kelurahan ? $row->kelurahan->nama_kelurahan : '-';
                })
                ->addColumn('tps', function($row) {
                    return $row->tps ? $row->tps->no_tps : '-';
                })
                ->addColumn('action', function($row) {
                    return '<div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">
                                    Aksi
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="'.route('user.edit', $row->id).'">Edit</a>
                                    <a class="dropdown-item" href="#" onclick="deleteUser(\''.$row->id.'\')">Hapus</a>
                                </div>
                            </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }


        return view('admin.user.index');
    }


    // Menampilkan form untuk membuat user baru
    public function create()
    {
        $kecamatans = Kecamatan::all();
        return view('admin.user.create', compact('kecamatans'));
    }

    // Menyimpan user baru ke database
    public function store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'phone' => 'required',
        'password' => 'required',
        'kecamatan_id' => 'nullable',
        'kelurahan_id' => 'nullable',
        'tps_id' => 'nullable',
    ]);

    // Tentukan role dan buat username
    $role = 'kota';
    $username = '';

    // Logic untuk pembuatan username
    if ($request->kecamatan_id && !$request->kelurahan_id && !$request->tps_id) {
        // Jika hanya memilih kecamatan, gunakan nama kecamatan
        $kecamatan = Kecamatan::find($request->kecamatan_id);
        $username = strtolower(str_replace(' ', '_', $kecamatan->nama_kecamatan));
        $role = 'kecamatan';
    }
    elseif ($request->kecamatan_id && $request->kelurahan_id) {
        // Jika memilih sampai kelurahan, gunakan kode kecamatan dan kelurahan
        $kecamatan = Kecamatan::find($request->kecamatan_id);
        $kelurahan = Kelurahan::find($request->kelurahan_id);
        $username = $kecamatan->kode_kecamatan . '.' . $kelurahan->kode_kelurahan;

        if ($request->tps_id) {
            // Jika memilih sampai TPS
            $tps = Tps::find($request->tps_id);
            $username .= '.' . $tps->no_tps;
            $role = 'tps';
        } else {
            $role = 'kelurahan';
        }
    }
    else {
        // Jika tidak ada yang dipilih (user kota)
        $username = 'kota_surabaya';
        $lastUser = User::where('username', 'LIKE', 'kota_surabaya%')
                        ->orderBy('username', 'desc')
                        ->first();

        if ($lastUser) {
            preg_match('/(\d+)$/', $lastUser->username, $matches);
            $nextNumber = isset($matches[1]) ? intval($matches[1]) + 1 : 1;
            $username .= '_' . $nextNumber;
        }
        $role = 'kota';
    }

    // Cek apakah username sudah ada
    if (User::where('username', $username)->exists()) {
        // Jika username sudah ada, tambahkan angka di belakangnya
        $counter = 1;
        $originalUsername = $username;
        while (User::where('username', $username)->exists()) {
            $username = $originalUsername . '_' . $counter;
            $counter++;
        }
    }

    // Simpan user baru
    User::create([
        'name' => $request->name,
        'phone' => $request->phone,
        'username' => $username,
        'password' => bcrypt($request->password),
        'role' => $role,
        'kecamatan_id' => $request->kecamatan_id,
        'kelurahan_id' => $request->kelurahan_id,
        'tps_id' => $request->tps_id,
    ]);

    return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan');
}


    // Menampilkan form untuk mengedit user yang ada
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $kecamatans = Kecamatan::all();
        $kelurahans = Kelurahan::where('kecamatan_id', $user->kecamatan_id)->get();
        $tps = Tps::where('kelurahan_id', $user->kelurahan_id)->get();

        return view('admin.user.edit', compact('user', 'kecamatans', 'kelurahans', 'tps'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'password' => 'nullable|string',
            'kecamatan_id' => 'nullable|exists:kecamatans,id',
            'kelurahan_id' => 'nullable|exists:kelurahans,id',
            'tps_id' => 'nullable|exists:tps,id',
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'password' => $validated['password'] ? Hash::make($validated['password']) : $user->password,
            'kecamatan_id' => $request->kecamatan_id,
            'kelurahan_id' => $request->kelurahan_id,
            'tps_id' => $request->tps_id,
        ]);

        return redirect()->route('user.index')->with('success', 'User berhasil diperbarui!');
    }


    // Menghapus user dari database
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dihapus!'
        ]);
    }
}

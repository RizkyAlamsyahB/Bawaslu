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
                ->addColumn('kecamatan', function ($row) {
                    return $row->kecamatan ? $row->kecamatan->nama_kecamatan : '-';
                })
                ->addColumn('kelurahan', function ($row) {
                    return $row->kelurahan ? $row->kelurahan->nama_kelurahan : '-';
                })
                ->addColumn('tps', function ($row) {
                    return $row->tps ? $row->tps->no_tps : '-';
                })
                ->addColumn('action', function ($row) {
                    return '<div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">
                                    Aksi
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="' .
                        route('user.edit', $row->id) .
                        '">Edit</a>
                                    <a class="dropdown-item" href="#" onclick="deleteUser(\'' .
                        $row->id .
                        '\')">Hapus</a>
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
        $kelurahans = Kelurahan::all();
        $tps = Tps::all();
        return view('admin.user.create', compact('kecamatans', 'kelurahans', 'tps'));
    }

    public function getKelurahanByKecamatan($kecamatan_id)
    {
        $kelurahans = Kelurahan::where('kecamatan_id', $kecamatan_id)
            ->orderBy('nama_kelurahan', 'asc')
            ->get(['id', 'nama_kelurahan', 'kode_kelurahan']);
        return response()->json($kelurahans);
    }

    public function getTpsByKelurahan($kelurahan_id)
    {
        $tps = Tps::where('kelurahan_id', $kelurahan_id)
            ->orderBy('no_tps', 'asc')
            ->get(['id', 'no_tps']);
        return response()->json($tps);
    }

    // Menyimpan user baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|numeric|digits:14',  // Ensures the phone field contains only numbers
            'password' => 'required|min:8',  // Ensures the password is at least 8 characters
            'kecamatan_id' => 'nullable',
            'kelurahan_id' => 'nullable',
            'tps_id' => 'nullable',
            'username_manual' => 'nullable|unique:users,username', // Tambahkan validasi untuk username manual
        ]);

        // Tentukan role dan buat username
        $role = 'kota';
        $username = '';

        // Logic untuk pembuatan username
        if (!$request->kecamatan_id && !$request->kelurahan_id && !$request->tps_id) {
            // Jika role kota, gunakan username manual
            $username = $request->username_manual;
            $role = 'kota';
        } elseif ($request->kecamatan_id && !$request->kelurahan_id && !$request->tps_id) {
            // Jika hanya memilih kecamatan
            $kecamatan = Kecamatan::find($request->kecamatan_id);
            $username = strtolower(str_replace(' ', '_', $kecamatan->nama_kecamatan));
            $role = 'kecamatan';
        } elseif ($request->kecamatan_id && $request->kelurahan_id) {
            // Jika memilih sampai kelurahan
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

        // Cek apakah username sudah ada (untuk non-manual username)
        if ($role !== 'kota' && User::where('username', $username)->exists()) {
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
            'name' => 'required|string|max:100',        // Name is required and must be a string
            'phone' => 'required|numeric|digits:14',     // Phone is required and must contain only numbers
            'password' => 'nullable|string|min:8',  // Password is nullable, but if provided, it must be at least 8 characters long
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
            'message' => 'User berhasil dihapus!',
        ]);
    }
}

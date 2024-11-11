<?php

namespace App\Http\Controllers;

use App\Models\Tps;
use App\Models\User;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

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
        try {
            $request->validate([
                'name' => 'required|string|max:100',
                'phone' => 'required|numeric|digits_between:10,13', // Ubah ini
                'password' => 'required|min:8',
                'kecamatan_id' => 'nullable',
                'kelurahan_id' => 'nullable',
                'tps_id' => 'nullable',
                'username_manual' => 'nullable|unique:users,username',
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
                if (!$kecamatan) {
                    throw new \Exception('Kecamatan tidak ditemukan');
                }
                $username = strtolower(str_replace(' ', '_', $kecamatan->nama_kecamatan));
                $role = 'kecamatan';
            } elseif ($request->kecamatan_id && $request->kelurahan_id) {
                // Jika memilih sampai kelurahan
                $kecamatan = Kecamatan::find($request->kecamatan_id);
                $kelurahan = Kelurahan::find($request->kelurahan_id);

                if (!$kecamatan || !$kelurahan) {
                    throw new \Exception('Kecamatan atau Kelurahan tidak ditemukan');
                }

                if ($request->tps_id) {
                    // Jika memilih sampai TPS
                    $tps = Tps::find($request->tps_id);
                    if (!$tps) {
                        throw new \Exception('TPS tidak ditemukan');
                    }
                    $username = $kecamatan->kode_kecamatan . '.' . $kelurahan->kode_kelurahan . '.' . $tps->no_tps;
                    $role = 'tps';
                } else {
                    // Jika hanya sampai kelurahan, tambahkan angka 1
                    $username = $kecamatan->kode_kecamatan . '1.' . $kelurahan->kode_kelurahan;
                    $role = 'kelurahan';
                }
            }

            // Debug information
            Log::info('Username yang akan dibuat:', [
                'username' => $username,
                'role' => $role,
                'request_data' => $request->all(),
            ]);

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

            // Pastikan username tidak kosong
            if (empty($username)) {
                throw new \Exception('Username tidak boleh kosong');
            }

            // Buat array data user
            $userData = [
                'name' => $request->name,
                'phone' => $request->phone,
                'username' => $username,
                'password' => bcrypt($request->password),
                'role' => $role,
                'kecamatan_id' => $request->kecamatan_id,
                'kelurahan_id' => $request->kelurahan_id,
                'tps_id' => $request->tps_id,
            ];

            // Debug information sebelum menyimpan
            Log::info('Data user yang akan disimpan:', $userData);

            // Simpan user baru
            $user = User::create($userData);

            // Debug information setelah menyimpan
            Log::info('User berhasil dibuat:', ['user_id' => $user->id]);

            return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan');
        } catch (\Exception $e) {
            // Log error
            Log::error('Error saat membuat user:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Menampilkan form untuk mengedit user yang ada
    public function edit($id)
    {
        $user = User::findOrFail($id);

        // Urutkan kecamatan berdasarkan nama
        $kecamatans = Kecamatan::orderBy('nama_kecamatan', 'asc')->get();

        // Ambil kelurahan berdasarkan kecamatan yang dipilih dan urutkan
        $kelurahans = $user->kecamatan_id
            ? Kelurahan::where('kecamatan_id', $user->kecamatan_id)
                ->orderBy('nama_kelurahan', 'asc')
                ->get()
            : collect();

        // Ambil TPS berdasarkan kelurahan yang dipilih dan urutkan
        $tps = $user->kelurahan_id
            ? Tps::where('kelurahan_id', $user->kelurahan_id)
                ->orderByRaw('CAST(no_tps AS UNSIGNED) ASC')
                ->get()
            : collect();

        return view('admin.user.edit', compact('user', 'kecamatans', 'kelurahans', 'tps'));
    }
    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $request->validate([
                'name' => 'required|string|max:100',
                'phone' => 'required|numeric|digits_between:10,13',
                'kecamatan_id' => 'nullable',
                'kelurahan_id' => 'nullable',
                'tps_id' => 'nullable',
                'username_manual' => ['nullable', Rule::unique('users', 'username')->ignore($user->id)],
                'password' => 'nullable|min:8',
            ]);

            // Tentukan role dan username
            $role = 'kota';
            $username = $user->username; // Default ke username yang ada

            if (!$request->kecamatan_id && !$request->kelurahan_id && !$request->tps_id) {
                // Role kota
                $username = $request->username_manual ?? $user->username;
                $role = 'kota';
            } elseif ($request->kecamatan_id && !$request->kelurahan_id && !$request->tps_id) {
                // Role kecamatan
                $kecamatan = Kecamatan::find($request->kecamatan_id);
                $username = strtolower(str_replace(' ', '_', $kecamatan->nama_kecamatan));
                $role = 'kecamatan';
            } elseif ($request->kecamatan_id && $request->kelurahan_id) {
                $kecamatan = Kecamatan::find($request->kecamatan_id);
                $kelurahan = Kelurahan::find($request->kelurahan_id);

                if ($request->tps_id) {
                    // Role TPS
                    $tps = Tps::find($request->tps_id);
                    $username = $kecamatan->kode_kecamatan . '.' . $kelurahan->kode_kelurahan . '.' . $tps->no_tps;
                    $role = 'tps';
                } else {
                    // Role kelurahan
                    $username = $kecamatan->kode_kecamatan . '1.' . $kelurahan->kode_kelurahan;
                    $role = 'kelurahan';
                }
            }

            // Update data user
            $userData = [
                'name' => $request->name,
                'phone' => $request->phone,
                'username' => $username,
                'role' => $role,
                'kecamatan_id' => $request->kecamatan_id,
                'kelurahan_id' => $request->kelurahan_id,
                'tps_id' => $request->tps_id,
            ];

            // Update password jika diisi
            if ($request->filled('password')) {
                $userData['password'] = bcrypt($request->password);
            }

            $user->update($userData);

            return redirect()->route('user.index')->with('success', 'User berhasil diupdate');
        } catch (\Exception $e) {
            Log::error('Error saat update user:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
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

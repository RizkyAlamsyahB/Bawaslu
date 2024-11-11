<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Cek apakah pengguna telah login
        if (!Auth::check()) {
            return redirect('login'); // Jika belum login, arahkan ke halaman login
        }

        // Dapatkan pengguna yang sedang login
        $user = Auth::user();

        // Cek apakah pengguna memiliki salah satu role yang diizinkan
        if (!in_array($user->role, $roles)) {
            abort(403, 'Unauthorized access.'); // Jika tidak memiliki salah satu role, tampilkan error 403
        }

        return $next($request); // Lanjutkan jika role sesuai
    }
}

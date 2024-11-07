<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role)
    {
        // Cek apakah pengguna telah login
        if (!Auth::check()) {
            return redirect('login'); // Jika belum login, arahkan ke halaman login
        }

        // Dapatkan pengguna yang sedang login
        $user = Auth::user();

        // Cek apakah pengguna memiliki role yang diizinkan
        if ($user->role !== $role) {
            abort(403, 'Unauthorized access.'); // Jika tidak memiliki role yang sesuai, tampilkan error 403
        }

        return $next($request); // Lanjutkan jika role sesuai
    }
}

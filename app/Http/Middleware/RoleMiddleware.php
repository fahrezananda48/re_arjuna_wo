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
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Jika belum login
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Anda harus login terlebih dahulu.');
        }

        $user = Auth::user();

        // Ambil role user (pastikan field 'role' ada di tabel users)
        $userRole = $user->role ?? null;

        // Jika user tidak punya role yang diizinkan
        if (!in_array($userRole, $roles)) {
            return abort(403, 'Anda tidak memiliki akses.');
        }

        return $next($request);
    }
}

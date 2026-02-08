<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Enums\RolesEnum;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // // Cek apakah user diblokir
        // if ($user->status_blokir) {
        //     Auth::logout();
        //     return redirect()->route('login')->with('error', 'Akun Anda telah diblokir. Silakan hubungi administrator.');
        // }

        // Cek role user
        $userRole = $user->role->value;

        // Jika role tidak sesuai, redirect ke dashboard sesuai role mereka
        if ($userRole !== $role) {
            return redirect()->route($this->getDashboardRoute($userRole));
        }

        return $next($request);
    }

    /**
     * Get dashboard route based on role
     */
    private function getDashboardRoute(string $role): string
    {
        return match ($role) {
            RolesEnum::ADMIN->value => 'admin.dashboard',
            RolesEnum::PETUGAS->value => 'petugas.dashboard',
            RolesEnum::PEMINJAM->value => 'peminjam.dashboard',
            default => 'login',
        };
    }
}

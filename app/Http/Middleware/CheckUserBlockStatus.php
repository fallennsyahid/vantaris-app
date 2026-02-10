<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CheckUserBlockStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (Auth::check()) {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            // Check if user is blocked and has durasi_blokir set
            if ($user->status_blokir && $user->durasi_blokir) {
                $durasiBlokir = Carbon::parse($user->durasi_blokir);
                $now = Carbon::now();

                // If current time is past the block duration, auto-unblock
                if ($now->greaterThan($durasiBlokir)) {
                    $user->status_blokir = false;
                    $user->durasi_blokir = null;
                    $user->save();
                }
            }
        }

        return $next($request);
    }
}

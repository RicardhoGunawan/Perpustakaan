<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckMembership
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // If the user is not a member or membership has expired
            if (!$user->member || !$user->member->is_active || $user->member->valid_until < now()) {
                return redirect()->route('profile.index')
                    ->with('error', 'Anda perlu menjadi anggota aktif untuk meminjam buku. Silakan hubungi petugas perpustakaan.');
            }
        }
        
        return $next($request);
    }
}
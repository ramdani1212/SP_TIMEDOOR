<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CustomAuthMiddleware
{
    public function handle(Request $request, Closure $next, ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Pengguna sudah login, arahkan ke dashboard yang benar
                if ($guard === 'web') {
                    return redirect()->route('admin.dashboard');
                } elseif ($guard === 'teacher') {
                    return redirect()->route('teacher.dashboard');
                }
            } else {
                // Pengguna belum login, arahkan ke halaman login yang benar
                if ($request->routeIs('admin.*')) {
                    return redirect()->route('admin.login');
                }
                if ($request->routeIs('teacher.*')) {
                    return redirect()->route('teacher.login');
                }
                // Jika tidak ada yang cocok, arahkan ke rute login umum
                return redirect()->route('login');
            }
        }
        return $next($request);
    }
}
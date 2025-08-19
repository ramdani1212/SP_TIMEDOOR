<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Menampilkan halaman login custom dari file HTML
    public function showLoginForm()
    {
        $html = file_get_contents(public_path('html/login.html')); // ambil HTML dari folder public/html
        $html = str_replace('{{ csrf_token() }}', csrf_token(), $html); // inject token CSRF
        return response($html);
    }

    // Menangani proses login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirect sesuai role user
            if (Auth::user()->role === 'admin') {
                return redirect('/admin/schedules');
            } elseif (Auth::user()->role === 'teacher') {
                return redirect('/teacher/schedule/create');
            }

            return redirect('/dashboard');
        }

        // Jika login gagal
        return redirect('/login')->with('error', 'Email atau password salah.');
    }

    // Halaman dashboard (jika diperlukan)
    public function dashboard()
    {
        return "Selamat datang di dashboard!";
    }
}

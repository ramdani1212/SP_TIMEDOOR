<?php

namespace App\Http\Controllers\Teacher\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Menampilkan form login untuk guru.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('teacher.auth.login');
    }

    /**
     * Menangani permintaan login untuk guru.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // Mendapatkan kredensial dari form
        $credentials = $request->only('email', 'password');

        // Mencoba untuk melakukan login dengan guard 'teacher'
        if (Auth::guard('teacher')->attempt($credentials)) {
            // Jika berhasil, buat ulang sesi dan redirect ke dashboard
            $request->session()->regenerate();

            return redirect()->intended('/teacher/dashboard');
        }

        // Jika gagal, kembali ke halaman login dengan pesan error
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Menangani permintaan logout untuk guru.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::guard('teacher')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/teacher/login');
    }
}
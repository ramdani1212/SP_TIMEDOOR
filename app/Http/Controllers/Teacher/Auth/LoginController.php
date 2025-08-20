<?php

namespace App\Http\Controllers\Teacher\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('teacher.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('teacher')->attempt($credentials)) {
            // PERBAIKAN: Periksa apakah role pengguna adalah 'teacher'
            $user = Auth::guard('teacher')->user();
            if ($user->role === 'teacher') {
                $request->session()->regenerate();
                return redirect()->intended('/teacher/dashboard');
            }

            // Jika role bukan 'teacher', logout dan kembalikan dengan pesan error
            Auth::guard('teacher')->logout();
        }

        return back()->withErrors([
            'email' => 'Email atau Password salah, atau Anda tidak memiliki akses sebagai guru.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('teacher')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/teacher/login');
    }
}
<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('web')->attempt($credentials)) {
            // PERBAIKAN: Periksa apakah role pengguna adalah 'admin'
            $user = Auth::guard('web')->user();
            if ($user->role === 'admin') {
                $request->session()->regenerate();
                return redirect()->intended('/admin/dashboard');
            }
            
            // Jika role bukan 'admin', logout dan kembalikan dengan pesan error
            Auth::guard('web')->logout();
        }

        return back()->withErrors([
            'email' => 'Email atau Password salah, atau Anda tidak memiliki akses sebagai admin.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/admin/login');
    }
}
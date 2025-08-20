<?php

namespace App\Http\Controllers\Teacher\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('teacher.auth.login'); // sesuaikan view-mu
    }

    protected function credentials(Request $request): array
    {
        return [
            'email'    => $request->input('email'),
            'password' => $request->input('password'),
            'role'     => 'guru', // <— hanya user role 'guru' yang boleh
        ];
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('teacher')->attempt($this->credentials($request), $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('teacher.dashboard'));
        }

        return back()
            ->withErrors(['email' => 'Email/password salah atau akun bukan role guru.'])
            ->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('teacher')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('teacher.login');
    }
}

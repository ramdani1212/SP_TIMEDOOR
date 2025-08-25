<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Notifications\DatabaseNotification; // Tambahkan ini

class AdminController extends Controller
{
    /**
     * Menampilkan halaman profil admin.
     */
    public function showProfile()
    {
        // Pastikan ambil user dari guard admin
        $user = Auth::guard('web')->user();
        return view('admin.profile.show', compact('user'));
    }

    /**
     * Menampilkan form untuk mengubah password.
     */
    public function showChangePasswordForm()
    {
        return view('admin.change-password');
    }

    /**
     * Memperbarui password admin.
     */
    public function updatePassword(Request $request)
    {
        // Validasi input
        $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Auth::guard('web')->user();

        // Periksa password lama
        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Password saat ini salah.'],
            ]);
        }

        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('admin.profile.show')->with('success', 'Password berhasil diubah!');
    }

    /**
     * Menandai notifikasi sebagai sudah dibaca.
     */
    public function markAsRead(DatabaseNotification $notification)
    {
        $notification->markAsRead();
        return back()->with('success', 'Notifikasi berhasil ditandai sudah dibaca.');
    }

        public function destroyNotification(string $notificationId)
    {
        $user = Auth::user();
        $n = $user->notifications()->where('id', $notificationId)->firstOrFail();
        $n->delete();

        return back()->with('success', 'Notifikasi berhasil dihapus.');
    }
}
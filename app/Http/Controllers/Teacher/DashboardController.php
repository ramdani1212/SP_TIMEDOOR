<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\Schedule;
use App\Models\User;
use App\Notifications\TeacherNoteNotification;           // untuk REVISI jadwal
use App\Notifications\TeacherGeneralNoteNotification;   // untuk PESAN UMUM (ikon lonceng)

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard guru dengan daftar jadwal yang ditugaskan kepadanya.
     */
    public function index()
    {
        $teacher = Auth::guard('teacher')->user();

        $schedules = $teacher->schedules()
            ->with(['students'])
            ->latest('schedule_date')
            ->get();

        return view('teacher.dashboard', compact('schedules'));
    }

    /**
     * Menampilkan halaman profil guru.
     */
    public function showProfile()
    {
        $teacher = Auth::guard('teacher')->user();
        return view('teacher.profile.show', compact('teacher'));
    }

    /**
     * Menampilkan form untuk mengubah password.
     */
    public function showChangePasswordForm()
    {
        return view('teacher.change-password');
    }

    /**
     * Memperbarui password guru.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'new_password'     => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Auth::guard('teacher')->user();

        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Password saat ini salah.'],
            ]);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password berhasil diubah!');
    }

    /**
     * Menyetujui jadwal yang dipilih.
     */
    public function approve(Schedule $schedule)
    {
        if ($schedule->teacher_id !== Auth::guard('teacher')->id()) {
            abort(403);
        }

        $schedule->update([
            'status'        => 'approved',
            'revision_note' => null,
        ]);

        return back()->with('success', 'Jadwal berhasil disetujui!');
    }

    /**
     * Mengubah status jadwal menjadi revisi dan menambahkan catatan,
     * lalu mengirim notifikasi ke admin (INI KHUSUS REVISI).
     */
    public function revision(Request $request, Schedule $schedule)
    {
        if ($schedule->teacher_id !== Auth::guard('teacher')->id()) {
            abort(403);
        }

        $request->validate([
            'revision_note' => 'required|string|max:500',
        ]);

        $schedule->update([
            'status'        => 'revision',
            'revision_note' => $request->revision_note,
        ]);

        $admin = User::where('role', 'admin')->first();
        if ($admin) {
            $teacher = Auth::guard('teacher')->user();
            // Notifikasi revisi (konten “guru meminta revisi”)
            $admin->notify(new TeacherNoteNotification(
                $request->revision_note,
                $teacher,
                $schedule
            ));
        }

        return back()->with('success', 'Jadwal berhasil direvisi dengan catatan!');
    }

    /**
     * Mengirim NOTIFIKASI PESAN UMUM dari guru ke admin (tombol lonceng).
     * BUKAN revisi jadwal.
     */
    public function sendNoteToAdmin(Request $request)
    {
        $request->validate([
            'note_to_admin' => 'required|string|max:1000',
            'schedule_id'   => 'nullable|integer',
        ]);

        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            return back()->with('error', 'Admin tidak ditemukan.');
        }

        $teacher  = Auth::guard('teacher')->user();
        $schedule = null;

        if ($request->filled('schedule_id')) {
            $schedule = Schedule::with('students', 'teacher')->find($request->schedule_id);
        }

        // Notifikasi PESAN UMUM (supaya isi notif = pesan guru, bukan “revisi”)
        $admin->notify(new TeacherGeneralNoteNotification(
            note: $request->note_to_admin,
            teacher: $teacher,
            schedule: $schedule
        ));

        return back()->with('success', 'Pesan berhasil dikirim ke admin.');
    }

    /**
     * Menampilkan riwayat notifikasi untuk guru.
     */
    public function notificationsIndex()
    {
        $teacher = Auth::guard('teacher')->user();

        return view('teacher.notifications.index', [
            'unread' => $teacher->unreadNotifications,
            'all'    => $teacher->notifications()->latest()->paginate(10),
        ]);
    }

    /**
     * Tandai notifikasi sebagai sudah dibaca.
     */
    public function markAsRead($id)
    {
        $teacher = Auth::guard('teacher')->user();
        $notification = $teacher->notifications()->findOrFail($id);
        $notification->markAsRead();

        return back()->with('success', 'Notifikasi ditandai sudah dibaca.');
    }

    public function destroyNotification(string $id)
    {
        $teacher = Auth::guard('teacher')->user();

        // pastikan yang dihapus milik si teacher ini
        $notification = $teacher->notifications()->where('id', $id)->firstOrFail();
        $notification->delete();

        return back()->with('success', 'Notifikasi berhasil dihapus.');
    }
}

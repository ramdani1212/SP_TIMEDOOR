<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Notification;
use App\Models\Schedule;
use App\Models\User;
use App\Notifications\TeacherNoteNotification;
use App\Notifications\TeacherGeneralNoteNotification;

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
        $schedule->update(['status' => 'approved', 'revision_note' => null]);
        return back()->with('success', 'Jadwal berhasil disetujui!');
    }

    /**
     * Mengubah status jadwal menjadi revisi dan menambahkan catatan,
     * lalu mengirim notifikasi ke admin.
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
            $admin->notify(new TeacherNoteNotification($request->revision_note, $teacher, $schedule));
        }

        return back()->with('success', 'Jadwal berhasil direvisi dengan catatan!');
    }

    /**
     * Mengirim notifikasi catatan umum dari guru ke admin.
     */
    public function sendNoteToAdmin(Request $request)
    {
        $request->validate([
            'note_to_admin' => 'required|string|max:1000',
        ]);

        $admin = User::where('role', 'admin')->first();

        if ($admin) {
            $teacher = Auth::guard('teacher')->user();
            if ($request->filled('schedule_id')) {
                $schedule = Schedule::with('students')->find($request->schedule_id);
                if ($schedule) {
                    $admin->notify(new TeacherNoteNotification($request->note_to_admin, $teacher, $schedule));
                }
            } else {
                $admin->notify(new TeacherGeneralNoteNotification($request->note_to_admin, $teacher));
            }
        }

        return back()->with('success', 'Catatan berhasil dikirim ke admin.');
    }

    /**
     * Menampilkan riwayat notifikasi untuk guru.
     * - $unread untuk bagian "Belum Dibaca"
     * - $all untuk semua notifikasi (pagination)
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

        if ($notification) {
            $notification->markAsRead();
        }

        return back()->with('success', 'Notifikasi ditandai sudah dibaca.');
    }
}

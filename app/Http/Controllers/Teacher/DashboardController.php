<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\User;
use App\Models\Student;
use App\Notifications\TeacherNoteNotification;
use App\Notifications\TeacherGeneralNoteNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard guru dengan daftar jadwal yang ditugaskan kepadanya.
     */
    public function index()
    {
        // Memuat (eager load) relasi 'student'
        $schedules = Auth::guard('teacher')->user()->schedules()->with('student')->latest()->get();
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
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Auth::guard('teacher')->user();

        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Password saat ini salah.'],
            ]);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->back()->with('success', 'Password berhasil diubah!');
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
     * Mengubah status jadwal menjadi revisi dan menambahkan catatan.
     */
    public function revision(Request $request, Schedule $schedule)
    {
        if ($schedule->teacher_id !== Auth::guard('teacher')->id()) {
            abort(403);
        }
        $request->validate([
            'revision_note' => 'required|string|max:500',
        ]);
        
        $schedule->update(['status' => 'revision', 'revision_note' => $request->revision_note]);
        
        $admin = User::where('role_id', 1)->first();
        if ($admin) {
            $teacher = Auth::user();
            $admin->notify(new TeacherNoteNotification($request->revision_note, $teacher, $schedule));
        }

        return back()->with('success', 'Jadwal berhasil direvisi dengan catatan!');
    }
    
    /**
     * Mengirim notifikasi catatan dari guru ke admin.
     */
    public function sendNoteToAdmin(Request $request)
    {
        $request->validate([
            'note_to_admin' => 'required|string|max:1000',
        ]);
        
        $admin = User::where('role_id', 1)->first();

        if ($admin) {
            $teacher = Auth::user();
            if ($request->has('schedule_id') && $request->schedule_id != null) {
                $schedule = Schedule::find($request->schedule_id);
                if ($schedule) {
                    $admin->notify(new TeacherNoteNotification($request->note_to_admin, $teacher, $schedule));
                }
            } else {
                $admin->notify(new TeacherGeneralNoteNotification($request->note_to_admin, $teacher));
            }
        }

        return redirect()->back()->with('success', 'Catatan berhasil dikirim ke admin.');
    }

    /**
     * Menampilkan riwayat notifikasi untuk guru.
     */
    public function notificationsIndex()
    {
        $teacher = Auth::guard('teacher')->user();
        $notifications = $teacher->notifications()->latest()->paginate(10);
        
        $teacher->unreadNotifications->markAsRead();

        return view('teacher.notifications.index', compact('notifications'));
    }
}
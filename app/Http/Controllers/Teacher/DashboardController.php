<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\User;
use App\Notifications\TeacherNoteNotification;
use App\Notifications\TeacherGeneralNoteNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class DashboardController extends Controller
{
    public function index()
    {
        // Eager load 'students' (jamak), bukan 'student'
        $teacher = Auth::guard('teacher')->user();

        $schedules = $teacher->schedules()
            ->with(['students'])   // <— penting
            ->latest('schedule_date')
            ->get();

        return view('teacher.dashboard', compact('schedules'));
    }

    public function showProfile()
    {
        $teacher = Auth::guard('teacher')->user();
        return view('teacher.profile.show', compact('teacher'));
    }

    public function showChangePasswordForm()
    {
        return view('teacher.change-password');
    }

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

    public function approve(Schedule $schedule)
    {
        if ($schedule->teacher_id !== Auth::guard('teacher')->id()) {
            abort(403);
        }
        $schedule->update(['status' => 'approved', 'revision_note' => null]);
        return back()->with('success', 'Jadwal berhasil disetujui!');
    }

    public function revision(Request $request, Schedule $schedule)
    {
        if ($schedule->teacher_id !== Auth::guard('teacher')->id()) {
            abort(403);
        }

        $request->validate([
            'revision_note' => 'required|string|max:500',
        ]);

        $schedule->update(['status' => 'revision', 'revision_note' => $request->revision_note]);

        // kirim notifikasi ke admin (contoh ambil user role_id = 1)
        $admin = \App\Models\User::where('role_id', 1)->first();
        if ($admin) {
            $teacher = Auth::guard('teacher')->user(); // <— gunakan guard teacher
            $admin->notify(new TeacherNoteNotification($request->revision_note, $teacher, $schedule));
        }

        return back()->with('success', 'Jadwal berhasil direvisi dengan catatan!');
    }

    public function sendNoteToAdmin(Request $request)
    {
        $request->validate([
            'note_to_admin' => 'required|string|max:1000',
        ]);

        $admin = \App\Models\User::where('role_id', 1)->first();

        if ($admin) {
            $teacher = Auth::guard('teacher')->user(); // <— guard teacher
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

    public function notificationsIndex()
    {
        $teacher = Auth::guard('teacher')->user();
        $notifications = $teacher->notifications()->latest()->paginate(10);
        $teacher->unreadNotifications->markAsRead();

        return view('teacher.notifications.index', compact('notifications'));
    }
}

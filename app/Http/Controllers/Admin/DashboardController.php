<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Models\Schedule;
use App\Models\User;
use App\Models\Student;

// (Opsional) notifikasi saat revisi
use App\Notifications\ScheduleRevisionNotification;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard admin dengan daftar semua jadwal.
     */
    public function index()
    {
        // muat relasi teacher & students
        $schedules = Schedule::with(['teacher', 'students'])->latest()->get();
        return view('admin.dashboard', compact('schedules'));
    }

    /**
     * Halaman riwayat notifikasi untuk admin.
     * - Mengirim $unread (koleksi) untuk bagian "Belum Dibaca"
     * - Mengirim $all (paginator) untuk bagian "Semua"
     * Catatan: tidak auto mark-as-read di sini (pakai tombol PATCH markAsRead).
     */
    public function notificationsIndex()
    {
        $admin = Auth::user(); // guard web
        return view('admin.notifications.index', [
            'unread' => $admin->unreadNotifications,                   // koleksi
            'all'    => $admin->notifications()->latest()->paginate(10), // paginator
        ]);
    }

    /**
     * Menampilkan form untuk membuat jadwal baru.
     */
    public function create()
    {
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();
        $students = Student::orderBy('nama')->get();

        return view('admin.schedules.create', compact('teachers', 'students'));
    }

    /**
     * Menyimpan jadwal baru dan siswa yang dipilih.
     * (Catatan: notifikasi ke teacher saat create sudah kamu pasang di Admin\ScheduleController@store)
     */
    public function store(Request $request)
    {
        $request->validate([
            'teacher_id'    => 'required|exists:users,id',
            'schedule_date' => 'required|date',
            'start_time'    => 'required|date_format:H:i',
            'end_time'      => 'required|date_format:H:i|after:start_time',
            'jenis_kelas'   => 'required|string|max:255',
            'students'      => 'required|array',
            'students.*'    => 'exists:students,id',
        ]);

        $schedule = Schedule::create([
            'teacher_id'    => $request->teacher_id,
            'schedule_date' => $request->schedule_date,
            'start_time'    => $request->start_time,
            'end_time'      => $request->end_time,
            'jenis_kelas'   => $request->jenis_kelas,
            'status'        => 'pending',
        ]);

        $schedule->students()->attach($request->students);

        return redirect()->route('admin.dashboard')->with('success', 'Jadwal berhasil dibuat!');
    }

    /**
     * Quick action: set status 'approved'.
     * (Opsional: kalau mau kirim notif approve ke teacher, bisa tambahkan Notification::send di sini)
     */
    public function approve(Schedule $schedule)
    {
        $schedule->update(['status' => 'approved']);
        return back()->with('success', 'Jadwal berhasil disetujui!');
    }

    /**
     * Quick action: set status 'revision' + simpan catatan.
     * Kirim notifikasi revisi ke teacher pemilik jadwal (opsional tapi disarankan).
     */
    public function revision(Schedule $schedule, Request $request)
    {
        $request->validate([
            'revision_note' => 'nullable|string|max:5000',
        ]);

        $schedule->update([
            'status'        => 'revision',
            'revision_note' => $request->revision_note,
        ]);

        // KIRIM NOTIF revisi ke teacher (kalau classnya tersedia)
        $teacher = User::find($schedule->teacher_id);
        if ($teacher && class_exists(ScheduleRevisionNotification::class)) {
            Notification::send($teacher, new ScheduleRevisionNotification($schedule));
        }

        return back()->with('success', 'Jadwal berhasil ditandai untuk revisi.');
    }
}

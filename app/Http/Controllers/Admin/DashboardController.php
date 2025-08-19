<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;// Penting: Gunakan model User untuk guru
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard admin dengan daftar semua jadwal.
     */
    public function index()
    {
        // Ambil semua schedule beserta teacher & students
        // Relasi `teacher` di model Schedule harus mengarah ke model User
        $schedules = Schedule::with(['teacher', 'students'])->latest()->get();

        return view('admin.dashboard', compact('schedules'));
    }

    /**
     * Menampilkan riwayat notifikasi untuk admin.
     */
    public function notificationsIndex()
    {
        $admin = Auth::user();
        $notifications = $admin->notifications()->latest()->paginate(10);

        // Tandai semua notifikasi yang belum dibaca jadi sudah dibaca
        $admin->unreadNotifications->markAsRead();

        return view('admin.notifications.index', compact('notifications'));
    }

    /**
     * Menampilkan form untuk membuat jadwal baru.
     */
   

    public function create()
    {
    // Pastikan Anda menggunakan kode ini:
    $teachers = \App\Models\User::where('role', 'teacher')->get();
    $students = \App\Models\Student::all();

    return view('admin.schedules.create', compact('teachers', 'students'));
    }

    /**
     * Menyimpan jadwal baru dan siswa yang dipilih.
     */
    public function store(Request $request)
    {
        $request->validate([
            // Perbaikan: Validasi teacher_id harus ada di tabel 'users', bukan 'teachers'
            'teacher_id'     => 'required|exists:users,id',
            'schedule_date'  => 'required|date',
            'start_time'     => 'required|date_format:H:i',
            'end_time'       => 'required|date_format:H:i|after:start_time',
            'jenis_kelas'    => 'required|string|max:255',
            'students'       => 'required|array',
            'students.*'     => 'exists:students,id',
        ]);

        // Simpan jadwal
        $schedule = Schedule::create([
            'teacher_id'    => $request->teacher_id,
            'schedule_date' => $request->schedule_date,
            'start_time'    => $request->start_time,
            'end_time'      => $request->end_time,
            'jenis_kelas'   => $request->jenis_kelas,
            'status'        => 'pending', // default pending
        ]);

        $schedule->students()->attach($request->students);

        return redirect()->route('admin.dashboard')->with('success', 'Jadwal berhasil dibuat!');
    }

    /**
     * Mengubah status jadwal menjadi 'approved'.
     */
    public function approve(Schedule $schedule)
    {
        $schedule->update(['status' => 'approved']);

        return back()->with('success', 'Jadwal berhasil disetujui!');
    }

    /**
     * Mengubah status jadwal menjadi 'revision'.
     */
    public function revision(Schedule $schedule, Request $request)
    {
        $request->validate([
            'revision_note' => 'nullable|string|max:255',
        ]);

        $schedule->update([
            'status'        => 'revision',
            'revision_note' => $request->revision_note,
        ]);

        return back()->with('success', 'Jadwal berhasil ditandai untuk revisi.');
    }
}
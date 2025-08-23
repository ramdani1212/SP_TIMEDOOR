<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\User;     // guru diambil dari users (role = teacher)
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Carbon\Carbon;

// === tambahkan use untuk Notifications ===
use App\Notifications\ScheduleCreatedNotification;
use App\Notifications\ScheduleRevisionNotification;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::with(['teacher', 'students'])->get();
        return view('admin.dashboard', compact('schedules'));
    }

    public function create()
    {
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();
        $students = Student::orderBy('nama')->get();
        return view('admin.schedules.create', compact('teachers', 'students'));
    }

    public function store(Request $request)
    {
        // Validasi: H:i (tanpa detik)
        $request->validate([
            'teacher_id'    => ['required','exists:users,id'],
            'students'      => ['required','array'],
            'students.*'    => ['exists:students,id'],
            'schedule_date' => ['required','date'],
            'start_time'    => ['required','date_format:H:i'],
            'end_time'      => ['required','date_format:H:i','after:start_time'],
            'jenis_kelas'   => ['required','string','max:255'],
            'status'        => ['required','in:pending,confirmed,cancelled,revision'],
            'revision_note' => ['nullable','string'],
        ]);

        // Normalisasi ke H:i:s
        $start = $request->start_time . ':00';
        $end   = $request->end_time   . ':00';

        // Guard tambahan end > start
        $s = Carbon::createFromFormat('H:i:s', $start);
        $e = Carbon::createFromFormat('H:i:s', $end);
        if (!$e->gt($s)) {
            return back()->withInput()->withErrors([
                'end_time' => 'Waktu selesai harus setelah waktu mulai.',
            ]);
        }

        DB::beginTransaction();
        try {
            $schedule = Schedule::create([
                'teacher_id'    => $request->teacher_id,
                'schedule_date' => $request->schedule_date,
                'start_time'    => $start,
                'end_time'      => $end,
                'jenis_kelas'   => $request->jenis_kelas,
                'status'        => $request->status,
                'revision_note' => $request->revision_note,
            ]);

            $schedule->students()->attach($request->input('students', []));

            // === KIRIM NOTIFIKASI: Admin menambah jadwal -> ke teacher yang dipilih ===
            $teacher = User::find($request->teacher_id);
            if ($teacher) {
                Notification::send($teacher, new ScheduleCreatedNotification($schedule));
            }

            DB::commit();
            return redirect()->route('admin.dashboard')->with('success', 'Jadwal berhasil dibuat & notifikasi dikirim ke teacher!');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan saat membuat jadwal.');
        }
    }

    public function edit(Schedule $schedule)
    {
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();
        $students = Student::orderBy('nama')->get();
        return view('admin.schedules.edit', compact('schedule', 'teachers', 'students'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        // Validasi: H:i (tanpa detik)
        $request->validate([
            'teacher_id'    => ['required','exists:users,id'],
            'students'      => ['required','array'],
            'students.*'    => ['exists:students,id'],
            'schedule_date' => ['required','date'],
            'start_time'    => ['required','date_format:H:i'],
            'end_time'      => ['required','date_format:H:i','after:start_time'],
            'jenis_kelas'   => ['required','string','max:255'],
            'status'        => ['required','in:pending,confirmed,cancelled,revision'],
            'revision_note' => ['nullable','string'],
        ]);

        // Normalisasi ke H:i:s
        $start = $request->start_time . ':00';
        $end   = $request->end_time   . ':00';

        // Guard tambahan
        $s = Carbon::createFromFormat('H:i:s', $start);
        $e = Carbon::createFromFormat('H:i:s', $end);
        if (!$e->gt($s)) {
            return back()->withInput()->withErrors([
                'end_time' => 'Waktu selesai harus setelah waktu mulai.',
            ]);
        }

        DB::beginTransaction();
        try {
            $schedule->update([
                'teacher_id'    => $request->teacher_id,
                'schedule_date' => $request->schedule_date,
                'start_time'    => $start,
                'end_time'      => $end,
                'jenis_kelas'   => $request->jenis_kelas,
                'status'        => $request->status,
                'revision_note' => $request->revision_note,
            ]);

            $schedule->students()->sync($request->input('students', []));

            // === KIRIM NOTIFIKASI: kalau status jadi 'revision' kirim catatan revisi ke teacher ===
            if ($request->status === 'revision' && filled($request->revision_note)) {
                $teacher = User::find($request->teacher_id);
                if ($teacher) {
                    Notification::send($teacher, new ScheduleRevisionNotification($schedule));
                }
            }

            DB::commit();
            return redirect()->route('admin.dashboard')->with('success', 'Jadwal berhasil diperbarui!');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui jadwal.');
        }
    }

    public function destroy(Schedule $schedule)
    {
        DB::beginTransaction();
        try {
            $schedule->students()->detach();
            $schedule->delete();

            DB::commit();
            return redirect()->route('admin.dashboard')->with('success', 'Jadwal berhasil dihapus!');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat menghapus jadwal.');
        }
    }
}

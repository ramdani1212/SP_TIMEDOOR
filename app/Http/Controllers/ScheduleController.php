<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class ScheduleController extends Controller
{
    /**
     * List jadwal (opsional sesuaikan view kamu).
     */
    public function index()
    {
        $schedules = Schedule::with(['teacher', 'students'])
            ->orderByDesc('schedule_date')
            ->orderBy('start_time')
            ->get();

        return view('admin.schedules.index', compact('schedules'));
    }

    /**
     * Form create.
     */
    public function create()
    {
        $teachers = Teacher::orderBy('name')->get();
        $students = Student::orderBy('nama')->get();

        return view('admin.schedules.create', compact('teachers', 'students'));
    }

    /**
     * Store jadwal baru.
     * - Validasi HH:MM
     * - Simpan sebagai HH:MM:SS
     */
    public function store(Request $request)
    {
        $request->validate([
            'teacher_id'    => ['required', 'exists:teachers,id'],
            'students'      => ['required', 'array'],
            'students.*'    => ['exists:students,id'],
            'schedule_date' => ['required', 'date'],
            'start_time'    => ['required', 'date_format:H:i'],
            'end_time'      => ['required', 'date_format:H:i', 'after:start_time'],
            'jenis_kelas'   => ['required', 'string', 'max:255'],
            'status'        => ['required', 'in:pending,approved,completed,revision,cancelled'],
            'revision_note' => ['nullable', 'string'],
        ]);

        DB::beginTransaction();
        try {
            $schedule = Schedule::create([
                'teacher_id'    => $request->teacher_id,
                'schedule_date' => $request->schedule_date,
                'start_time'    => $request->start_time . ':00', // normalize -> H:i:s
                'end_time'      => $request->end_time   . ':00', // normalize -> H:i:s
                'jenis_kelas'   => $request->jenis_kelas,
                'status'        => $request->status,
                'revision_note' => $request->revision_note,
            ]);

            $schedule->students()->attach($request->input('students', []));

            DB::commit();
            return redirect()->route('admin.dashboard')->with('success', 'Jadwal berhasil dibuat!');
        } catch (Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan saat membuat jadwal.');
        }
    }

    /**
     * Form edit.
     */
    public function edit(Schedule $schedule)
    {
        $teachers = Teacher::orderBy('name')->get();
        $students = Student::orderBy('nama')->get();

        return view('admin.schedules.edit', compact('schedule', 'teachers', 'students'));
    }

    /**
     * Update jadwal.
     * - Validasi HH:MM
     * - Simpan sebagai HH:MM:SS
     */
    public function update(Request $request, Schedule $schedule)
    {
        $request->validate([
            'teacher_id'    => ['required', 'exists:teachers,id'],
            'students'      => ['required', 'array'],
            'students.*'    => ['exists:students,id'],
            'schedule_date' => ['required', 'date'],
            'start_time'    => ['required', 'date_format:H:i'],
            'end_time'      => ['required', 'date_format:H:i', 'after:start_time'],
            'jenis_kelas'   => ['required', 'string', 'max:255'],
            'status'        => ['required', 'in:pending,approved,completed,revision,cancelled'],
            'revision_note' => ['nullable', 'string'],
        ]);

        DB::beginTransaction();
        try {
            $schedule->update([
                'teacher_id'    => $request->teacher_id,
                'schedule_date' => $request->schedule_date,
                'start_time'    => $request->start_time . ':00', // normalize -> H:i:s
                'end_time'      => $request->end_time   . ':00', // normalize -> H:i:s
                'jenis_kelas'   => $request->jenis_kelas,
                'status'        => $request->status,
                'revision_note' => $request->revision_note,
            ]);

            $schedule->students()->sync($request->input('students', []));

            DB::commit();
            return redirect()->route('admin.dashboard')->with('success', 'Jadwal berhasil diperbarui!');
        } catch (Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui jadwal.');
        }
    }

    /**
     * Hapus jadwal.
     */
    public function destroy(Schedule $schedule)
    {
        DB::beginTransaction();
        try {
            $schedule->students()->detach();
            $schedule->delete();

            DB::commit();
            return redirect()->route('admin.dashboard')->with('success', 'Jadwal berhasil dihapus!');
        } catch (Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat menghapus jadwal.');
        }
    }
}

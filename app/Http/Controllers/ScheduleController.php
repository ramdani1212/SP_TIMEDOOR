<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Throwable;

class ScheduleController extends Controller
{
    public function create()
    {
        $teachers = Teacher::orderBy('name')->get();
        $students = Student::orderBy('nama')->get();
        return view('admin.schedules.create', compact('teachers', 'students'));
    }

    public function store(Request $request)
    {
        // Validasi NON-waktu saja
        $request->validate([
            'teacher_id'    => ['required','exists:teachers,id'],
            'students'      => ['required','array'],
            'students.*'    => ['exists:students,id'],
            'schedule_date' => ['required','date'],
            'jenis_kelas'   => ['required','string','max:255'],
            'status'        => ['required','in:pending,approved,completed,revision,cancelled'],
            'revision_note' => ['nullable','string'],
        ]);

        // Parse jam:menit -> jam:menit:detik
        $start = $this->toHms($request->input('start_time')); // "H:i:s" atau null
        $end   = $this->toHms($request->input('end_time'));

        if (!$start || !$end) {
            return back()->withInput()->withErrors([
                'start_time' => 'Format waktu tidak valid. Gunakan HH:MM (mis. 09:30).',
                'end_time'   => 'Format waktu tidak valid. Gunakan HH:MM (mis. 11:00).',
            ]);
        }

        if (!$this->isEndAfterStart($start, $end)) {
            return back()->withInput()->withErrors([
                'end_time' => 'Waktu selesai harus setelah waktu mulai.',
            ]);
        }

        DB::beginTransaction();
        try {
            $schedule = Schedule::create([
                'teacher_id'    => $request->teacher_id,
                'schedule_date' => $request->schedule_date,
                'start_time'    => $start, // H:i:s
                'end_time'      => $end,   // H:i:s
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

    public function edit(Schedule $schedule)
    {
        $teachers = Teacher::orderBy('name')->get();
        $students = Student::orderBy('nama')->get();
        return view('admin.schedules.edit', compact('schedule','teachers','students'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $request->validate([
            'teacher_id'    => ['required','exists:teachers,id'],
            'students'      => ['required','array'],
            'students.*'    => ['exists:students,id'],
            'schedule_date' => ['required','date'],
            'jenis_kelas'   => ['required','string','max:255'],
            'status'        => ['required','in:pending,approved,completed,revision,cancelled'],
            'revision_note' => ['nullable','string'],
        ]);

        $start = $this->toHms($request->input('start_time'));
        $end   = $this->toHms($request->input('end_time'));

        if (!$start || !$end) {
            return back()->withInput()->withErrors([
                'start_time' => 'Format waktu tidak valid. Gunakan HH:MM (mis. 09:30).',
                'end_time'   => 'Format waktu tidak valid. Gunakan HH:MM (mis. 11:00).',
            ]);
        }

        if (!$this->isEndAfterStart($start, $end)) {
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

            DB::commit();
            return redirect()->route('admin.dashboard')->with('success', 'Jadwal berhasil diperbarui!');
        } catch (Throwable $e) {
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
        } catch (Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat menghapus jadwal.');
        }
    }

    /** Helpers */
    private function toHms(?string $time): ?string
    {
        if (!$time) return null;
        // jika "HH:MM" tambahkan ":00"
        if (preg_match('/^\d{2}:\d{2}$/', $time)) {
            $time .= ':00';
        }
        // validasi akhir "HH:MM:SS"
        if (!preg_match('/^\d{2}:\d{2}:\d{2}$/', $time)) {
            try {
                return Carbon::parse($time)->format('H:i:s');
            } catch (\Exception $e) {
                return null;
            }
        }
        return $time;
    }

    private function isEndAfterStart(string $start, string $end): bool
    {
        $s = Carbon::createFromFormat('H:i:s', $start);
        $e = Carbon::createFromFormat('H:i:s', $end);
        return $e->gt($s);
    }
}

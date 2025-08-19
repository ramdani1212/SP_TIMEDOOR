<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    /**
     * Menampilkan dashboard guru dengan daftar jadwal.
     */
    public function index()
    {
        $teacherId = Auth::guard('teacher')->id();
        $schedules = Schedule::where('teacher_id', $teacherId)
                             ->with(['teacher', 'students'])
                             ->get();

        return view('teacher.dashboard', compact('schedules'));
    }

    /**
     * Menampilkan form untuk membuat jadwal baru.
     */
    public function create()
    {
        $students = Student::all();
        return view('teacher.schedules.create', compact('students'));
    }

    /**
     * Menyimpan jadwal baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'schedule_date' => 'required|date',
            'start_time'    => 'required|date_format:H:i',
            'end_time'      => 'required|date_format:H:i|after:start_time',
            'jenis_kelas'   => 'required|string|max:255',
            'students'      => 'required|array',
            'students.*'    => 'exists:students,id',
        ]);

        DB::beginTransaction();

        try {
            $schedule = Auth::guard('teacher')->user()->schedules()->create([
                'schedule_date' => $request->schedule_date,
                'start_time'    => $request->start_time,
                'end_time'      => $request->end_time,
                'jenis_kelas'   => $request->jenis_kelas,
                'status'        => 'pending',
            ]);

            $schedule->students()->attach($request->input('students'));

            DB::commit();

            return redirect()->route('teacher.dashboard')->with('success', 'Jadwal berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Terjadi kesalahan saat membuat jadwal. Silakan coba lagi.');
        }
    }

    /**
     * Menampilkan form untuk mengedit jadwal.
     */
    public function edit(Schedule $schedule)
    {
        if ($schedule->teacher_id !== Auth::guard('teacher')->id()) {
            abort(403, 'Anda tidak diizinkan untuk mengedit jadwal ini.');
        }

        $students = Student::all();
        return view('teacher.schedules.edit', compact('schedule', 'students'));
    }

    /**
     * Mengupdate jadwal di database.
     */
    public function update(Request $request, Schedule $schedule)
    {
        if ($schedule->teacher_id !== Auth::guard('teacher')->id()) {
            abort(403, 'Anda tidak diizinkan untuk memperbarui jadwal ini.');
        }

        $request->validate([
            'schedule_date' => 'required|date',
            'start_time'    => 'required|date_format:H:i',
            'end_time'      => 'required|date_format:H:i|after:start_time',
            'jenis_kelas'   => 'required|string|max:255',
            'students'      => 'required|array',
            'students.*'    => 'exists:students,id',
        ]);

        DB::beginTransaction();

        try {
            $schedule->update([
                'schedule_date' => $request->schedule_date,
                'start_time'    => $request->start_time,
                'end_time'      => $request->end_time,
                'jenis_kelas'   => $request->jenis_kelas,
            ]);

            $schedule->students()->sync($request->input('students'));

            DB::commit();

            return redirect()->route('teacher.dashboard')->with('success', 'Jadwal berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Terjadi kesalahan saat memperbarui jadwal. Silakan coba lagi.');
        }
    }

    /**
     * Menghapus jadwal dari database.
     */
    public function destroy(Schedule $schedule)
    {
        if ($schedule->teacher_id !== Auth::guard('teacher')->id()) {
            abort(403, 'Anda tidak diizinkan untuk menghapus jadwal ini.');
        }
        
        DB::beginTransaction();

        try {
            $schedule->students()->detach();
            $schedule->delete();

            DB::commit();
            
            return redirect()->route('teacher.dashboard')->with('success', 'Jadwal berhasil dihapus!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                             ->with('error', 'Terjadi kesalahan saat menghapus jadwal. Silakan coba lagi.');
        }
    }
}
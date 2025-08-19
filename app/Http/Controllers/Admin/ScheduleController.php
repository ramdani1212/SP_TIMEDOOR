<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\User; // Perbaikan: Ganti Teacher menjadi User
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    /**
     * Menampilkan dashboard admin dengan daftar jadwal.
     */
    public function index()
    {
        $schedules = Schedule::with(['teacher', 'students'])->get();
        return view('admin.dashboard', compact('schedules'));
    }

    /**
     * Menampilkan form untuk membuat jadwal baru.
     */
    public function create()
    {
        // Perbaikan: Mengambil guru dari tabel users dengan role 'teacher'
        $teachers = User::where('role', 'teacher')->get();
        $students = Student::all();
        return view('admin.schedules.create', compact('teachers', 'students'));
    }

    /**
     * Menyimpan jadwal baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            // Perbaikan: Validasi ke tabel users
            'teacher_id'    => 'required|exists:users,id',
            'students'      => 'required|array',
            'students.*'    => 'exists:students,id',
            'schedule_date' => 'required|date',
            'start_time'    => 'required|date_format:H:i:s',
            'end_time'      => 'required|date_format:H:i:s|after:start_time',
            'jenis_kelas'   => 'required|string|max:255',
            'status'        => 'required|string|in:pending,confirmed,cancelled,revision',
            'revision_note' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $schedule = Schedule::create([
                'teacher_id'    => $request->teacher_id,
                'schedule_date' => $request->schedule_date,
                'start_time'    => $request->start_time,
                'end_time'      => $request->end_time,
                'jenis_kelas'   => $request->jenis_kelas,
                'status'        => $request->status,
                'revision_note' => $request->revision_note,
            ]);

            $schedule->students()->attach($request->input('students'));

            DB::commit();

            return redirect()->route('admin.dashboard')->with('success', 'Jadwal berhasil dibuat!');

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
        // Perbaikan: Mengambil guru dari tabel users dengan role 'teacher'
        $teachers = User::where('role', 'teacher')->get();
        $students = Student::all();
        return view('admin.schedules.edit', compact('schedule', 'teachers', 'students'));
    }

    /**
     * Mengupdate jadwal di database.
     */
    public function update(Request $request, Schedule $schedule)
    {
        $request->validate([
            // Perbaikan: Validasi ke tabel users
            'teacher_id'    => 'required|exists:users,id',
            'students'      => 'required|array',
            'students.*'    => 'exists:students,id',
            'schedule_date' => 'required|date',
            'start_time'    => 'required|date_format:H:i:s',
            'end_time'      => 'required|date_format:H:i:s|after:start_time',
            'jenis_kelas'   => 'required|string|max:255',
            'status'        => 'required|string|in:pending,confirmed,cancelled,revision',
            'revision_note' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $schedule->update([
                'teacher_id'    => $request->teacher_id,
                'schedule_date' => $request->schedule_date,
                'start_time'    => $request->start_time,
                'end_time'      => $request->end_time,
                'jenis_kelas'   => $request->jenis_kelas,
                'status'        => $request->status,
                'revision_note' => $request->revision_note,
            ]);

            $schedule->students()->sync($request->input('students'));

            DB::commit();

            return redirect()->route('admin.dashboard')
                             ->with('success', 'Jadwal berhasil diperbarui.');

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
        DB::beginTransaction();

        try {
            $schedule->students()->detach();
            $schedule->delete();

            DB::commit();
            
            return redirect()->route('admin.dashboard')
                             ->with('success', 'Jadwal berhasil dihapus!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                             ->with('error', 'Terjadi kesalahan saat menghapus jadwal. Silakan coba lagi.');
        }
    }
}
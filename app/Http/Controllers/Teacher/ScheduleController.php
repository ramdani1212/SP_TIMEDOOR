<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    /**
     * Menampilkan dashboard guru dengan daftar jadwal.
     */
    public function index()
    {
        $schedules = Auth::guard('teacher')->user()->schedules()->latest()->get();
        return view('teacher.dashboard', compact('schedules'));
    }

    /**
     * Menampilkan form untuk membuat jadwal baru.
     */
    public function create()
    {
        return view('teacher.schedules.create');
    }

    /**
     * Menyimpan jadwal baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'schedule_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        Auth::guard('teacher')->user()->schedules()->create([
            'schedule_date' => $request->schedule_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'status' => 'pending',
        ]);

        return redirect()->route('teacher.dashboard')->with('success', 'Jadwal berhasil dibuat!');
    }

    /**
     * Menampilkan form untuk mengedit jadwal.
     */
    public function edit(Schedule $schedule)
    {
        if ($schedule->teacher_id !== Auth::guard('teacher')->id()) {
            abort(403);
        }
        return view('teacher.schedules.edit', compact('schedule'));
    }

    /**
     * Mengupdate jadwal di database.
     */
    public function update(Request $request, Schedule $schedule)
    {
        if ($schedule->teacher_id !== Auth::guard('teacher')->id()) {
            abort(403);
        }
        $request->validate([
            'schedule_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);
        $schedule->update($request->all());
        return redirect()->route('teacher.dashboard')->with('success', 'Jadwal berhasil diupdate!');
    }

    /**
     * Menghapus jadwal dari database.
     */
    public function destroy(Schedule $schedule)
    {
        if ($schedule->teacher_id !== Auth::guard('teacher')->id()) {
            abort(403);
        }
        $schedule->delete();
        return redirect()->route('teacher.dashboard')->with('success', 'Jadwal berhasil dihapus!');
    }
}
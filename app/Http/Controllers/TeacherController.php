<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use App\Models\Schedule;
use App\Notifications\TeacherNoteNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teachers = Teacher::all();
        return view('teachers.index', compact('teachers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('teachers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            // Tambahkan validasi lain sesuai kebutuhan
        ]);

        Teacher::create($request->all());
        return redirect()->route('teachers.index')->with('success', 'Teacher added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Teacher $teacher)
    {
        return view('teachers.show', compact('teacher'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Teacher $teacher)
    {
        return view('teachers.edit', compact('teacher'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Teacher $teacher)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            // Tambahkan validasi lain sesuai kebutuhan
        ]);

        $teacher->update($request->all());
        return redirect()->route('teachers.index')->with('success', 'Teacher updated.');
    }


    public function createRevision(Schedule $schedule)
{
    // Mengembalikan view dengan data jadwal yang dipilih
    return view('teacher.schedules.revision', compact('schedule'));
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teacher $teacher)
    {
        $teacher->delete();
        return redirect()->route('teachers.index')->with('success', 'Teacher deleted.');
    }

    /**
     * Store a revision note and notify the admin.
     */
    public function submitRevision(Request $request, Schedule $schedule)
    {
        $request->validate([
            'note' => 'required|string',
        ]);
        
        // Mengambil guru yang sedang login
        $teacher = Auth::user(); 

        // Mengambil admin pertama
        $admin = User::where('role', 'admin')->first();

        // Mengirim notifikasi jika admin ditemukan
        if ($admin) {
            $admin->notify(new TeacherNoteNotification($request->note, $teacher, $schedule));
        }

        return redirect()->back()->with('success', 'Catatan revisi berhasil dikirim ke admin.');
    }
}
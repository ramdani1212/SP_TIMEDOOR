<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminTeacherController extends Controller
{
    /**
     * Menampilkan daftar guru.
     */
    public function index()
    {
        $teachers = User::where('role', 'teacher')->get();
        return view('admin.teachers.index', compact('teachers'));
    }

    /**
     * Menampilkan form untuk membuat guru baru.
     */
    public function create()
    {
        return view('admin.teachers.create');
    }

    /**
     * Menyimpan guru baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'teacher',
        ]);

        return redirect()->route('admin.teachers.index')->with('success', 'Guru berhasil dibuat.');
    }

    /**
     * Menampilkan form untuk mengedit guru.
     */
    public function edit(User $teacher)
    {
        // Pastikan pengguna yang diedit adalah guru
        if ($teacher->role !== 'teacher') {
            abort(404);
        }
        return view('admin.teachers.edit', compact('teacher'));
    }

    /**
     * Memperbarui guru di database.
     */
    public function update(Request $request, User $teacher)
    {
        // Pastikan pengguna yang diperbarui adalah guru
        if ($teacher->role !== 'teacher') {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $teacher->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $teacher->name = $request->name;
        $teacher->email = $request->email;
        if ($request->password) {
            $teacher->password = Hash::make($request->password);
        }
        $teacher->save();

        return redirect()->route('admin.teachers.index')->with('success', 'Guru berhasil diperbarui.');
    }

    /**
     * Menghapus guru dari database.
     */
    public function destroy(User $teacher)
    {
        // Pastikan pengguna yang dihapus adalah guru
        if ($teacher->role !== 'teacher') {
            abort(404);
        }
        $teacher->delete();

        return redirect()->route('admin.teachers.index')->with('success', 'Guru berhasil dihapus.');
    }
}
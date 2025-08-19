<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;

class AdminStudentController extends Controller
{
    /**
     * Menampilkan daftar semua siswa.
     */
    public function index()
    {
        $students = Student::latest()->get();
        return view('admin.students.index', compact('students'));
    }

    /**
     * Menampilkan form untuk membuat siswa baru.
     */
    public function create()
    {
        return view('admin.students.create');
    }

    /**
     * Menyimpan siswa baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'umur' => 'nullable|integer',
            'alamat' => 'nullable|string',
            'no_telp' => 'nullable|string',
            'nama_orang_tua' => 'nullable|string',
        ]);
        
        Student::create($request->all());

        return redirect()->route('admin.students.index')->with('success', 'Data siswa berhasil ditambahkan!');
    }

    /**
     * Menampilkan form untuk mengedit data siswa.
     */
    public function edit(Student $student)
    {
        return view('admin.students.edit', compact('student'));
    }

    /**
     * Memperbarui data siswa di database.
     */
    public function update(Request $request, Student $student)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'umur' => 'nullable|integer',
            'alamat' => 'nullable|string',
            'no_telp' => 'nullable|string',
            'nama_orang_tua' => 'nullable|string',
        ]);
        
        $student->update($request->all());

        return redirect()->route('admin.students.index')->with('success', 'Data siswa berhasil diperbarui!');
    }

    /**
     * Menghapus data siswa dari database.
     */
    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()->route('admin.students.index')->with('success', 'Data siswa berhasil dihapus!');
    }
}
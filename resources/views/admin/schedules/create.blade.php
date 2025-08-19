@extends('admin.layouts.app')

@section('title', 'Tambah Jadwal')

@section('content')
<style>
    .form-container {
        max-width: 800px;
        margin: 40px auto;
        padding: 30px;
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.1);
    }
    .form-header {
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 25px;
        color: #4CAF50;
        text-align: center;
    }
    .form-group {
        margin-bottom: 20px;
    }
    label {
        font-weight: 600;
        margin-bottom: 8px;
        color: #555;
        display: block;
    }
    input, textarea, select {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 1rem;
        transition: 0.2s;
    }
    input:focus, textarea:focus, select:focus {
        border-color: #4CAF50;
        box-shadow: 0 0 5px rgba(76,175,80,0.4);
        outline: none;
    }
    .btn {
        padding: 10px 18px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        transition: 0.3s;
    }
    .btn-primary {
        background-color: #4CAF50;
        color: white;
    }
    .btn-primary:hover {
        background-color: #45a049;
    }
    .btn-secondary {
        background-color: #ccc;
        color: #333;
    }
    .btn-secondary:hover {
        background-color: #bbb;
    }
</style>

<div class="form-container">
    <h3 class="form-header">Tambah Jadwal</h3>
    <form action="{{ route('admin.schedules.store') }}" method="POST">
        @csrf

      <div class="form-group">
            <label for="teacher_id">Pilih Guru</label>
                <select name="teacher_id" id="teacher_id" required>
                 <option value="">-- Pilih Guru --</option>
             @foreach($teachers as $teacher)
            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
        @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="students">Pilih Siswa</label>
            <select name="students[]" id="students" multiple required>
                @foreach($students as $student)
                    <option value="{{ $student->id }}">{{ $student->nama }}</option>
                @endforeach
            </select>
            <small>Gunakan Ctrl (Windows) atau Command (Mac) untuk pilih lebih dari satu siswa</small>
        </div>

        <div class="form-group">
            <label for="schedule_date">Tanggal</label>
            <input type="date" name="schedule_date" id="schedule_date" required>
        </div>

        <div class="form-group">
            <label for="start_time">Waktu Mulai</label>
            <input type="time" name="start_time" id="start_time" required>
        </div>

        <div class="form-group">
            <label for="end_time">Waktu Selesai</label>
            <input type="time" name="end_time" id="end_time" required>
        </div>

        <div class="form-group">
            <label for="jenis_kelas">Jenis Kelas</label>
            <select name="jenis_kelas" id="jenis_kelas" required>
                <option value="windows">Windows</option>
                <option value="linux">Linux</option>
                <option value="android">Android</option>
            </select>
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status" required>
                <option value="pending">Pending</option>
                <option value="confirmed">Confirmed</option>
                <option value="cancelled">Cancelled</option>
                <option value="revision">Revision</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
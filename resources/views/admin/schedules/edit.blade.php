@extends('admin.layouts.app')

@section('title', 'Edit Jadwal')

@section('content')
<style>
    /* CSS sudah ada + tambahan agar multi-select lebih rapi */
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
        padding: 10px 12px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 1rem;
        transition: 0.2s;
        width: 100%;
    }
    input:focus, textarea:focus, select:focus {
        border-color: #4CAF50;
        box-shadow: 0 0 5px rgba(76,175,80,0.4);
        outline: none;
    }
    textarea {
        resize: vertical;
        min-height: 80px;
    }
    .btn {
        padding: 10px 18px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        transition: 0.3s;
        text-decoration: none;
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
    /* Style untuk pesan error validasi */
    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
        padding: 10px;
        margin-bottom: 20px;
        border-radius: 5px;
    }
    .alert-danger ul {
        margin-bottom: 0;
        padding-left: 20px;
    }
</style>

<div class="form-container">
    <div class="card">
        <div class="card-header">
            <h3>Edit Jadwal</h3>
        </div>
        <div class="card-body">
            {{-- Bagian ini akan menampilkan pesan error validasi --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.schedules.update', $schedule) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Pilih Guru --}}
                <div class="form-group">
                    <label for="teacher_id">Pilih Guru</label>
                    <select name="teacher_id" id="teacher_id" class="form-control" required>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ old('teacher_id', $schedule->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Pilih Siswa (Multi Select) --}}
                <div class="form-group">
                    <label for="students">Pilih Siswa</label>
                    <select name="students[]" id="students" class="form-control" multiple required>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" 
                                {{ in_array($student->id, old('students', $schedule->students->pluck('id')->toArray())) ? 'selected' : '' }}>
                                {{ $student->nama }}
                            </option>
                        @endforeach
                    </select>
                    <small style="color: #777;">* Tekan CTRL (atau CMD di Mac) untuk memilih lebih dari satu siswa</small>
                </div>

                {{-- Tanggal --}}
                <div class="form-group">
                    <label for="schedule_date">Tanggal</label>
                    <input type="date" name="schedule_date" id="schedule_date" 
                            class="form-control" value="{{ old('schedule_date', $schedule->schedule_date) }}" required>
                </div>

                {{-- Waktu Mulai --}}
                <div class="form-group">
                    <label for="start_time">Waktu Mulai</label>
                    <input type="time" name="start_time" id="start_time" 
                            class="form-control" value="{{ old('start_time', $schedule->start_time) }}" required>
                </div>

                {{-- Waktu Selesai --}}
                <div class="form-group">
                    <label for="end_time">Waktu Selesai</label>
                    <input type="time" name="end_time" id="end_time" 
                            class="form-control" value="{{ old('end_time', $schedule->end_time) }}" required>
                </div>

                {{-- Jenis Kelas --}}
                <div class="form-group">
                    <label for="jenis_kelas">Jenis Kelas</label>
                    <select name="jenis_kelas" id="jenis_kelas" class="form-control" required>
                        <option value="windows" {{ old('jenis_kelas', $schedule->jenis_kelas) == 'windows' ? 'selected' : '' }}>Windows</option>
                        <option value="linux" {{ old('jenis_kelas', $schedule->jenis_kelas) == 'linux' ? 'selected' : '' }}>Linux</option>
                        <option value="android" {{ old('jenis_kelas', $schedule->jenis_kelas) == 'android' ? 'selected' : '' }}>Android</option>
                    </select>
                </div>

                {{-- Status --}}
                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="pending" {{ old('status', $schedule->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ old('status', $schedule->status) == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="cancelled" {{ old('status', $schedule->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        <option value="revision" {{ old('status', $schedule->status) == 'revision' ? 'selected' : '' }}>Revision</option>
                    </select>
                </div>
                
                {{-- KECUALI JIKA ANDA INGIN ADMIN BISA MENGEDIT CATATAN REVISI INI JUGA --}}
                {{-- Jika tidak ingin, hapus blok div form-group di bawah ini --}}
                <div class="form-group">
                    <label for="revision_note">Catatan Revisi</label>
                    <textarea name="revision_note" id="revision_note" class="form-control">{{ old('revision_note', $schedule->revision_note) }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Perbarui</button>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary mt-3">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection
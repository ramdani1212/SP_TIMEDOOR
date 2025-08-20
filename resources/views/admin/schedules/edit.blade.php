@extends('admin.layouts.app')

@section('title', 'Edit Jadwal')

@section('content')
<style>
    .form-container{max-width:800px;margin:40px auto;padding:30px;background:#fff;border-radius:12px;box-shadow:0 6px 20px rgba(0,0,0,.1)}
    .form-header{font-size:1.5rem;font-weight:700;margin-bottom:25px;color:#4CAF50;text-align:center}
    .form-group{margin-bottom:20px}
    label{font-weight:600;margin-bottom:8px;color:#555;display:block}
    input,textarea,select{width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:8px;font-size:1rem;transition:.2s}
    input:focus,textarea:focus,select:focus{border-color:#4CAF50;box-shadow:0 0 5px rgba(76,175,80,.4);outline:none}
    .btn{padding:10px 18px;border-radius:8px;border:none;cursor:pointer;font-weight:600;transition:.3s;text-decoration:none}
    .btn-primary{background:#4CAF50;color:#fff}.btn-primary:hover{background:#45a049}
    .btn-secondary{background:#ccc;color:#333}.btn-secondary:hover{background:#bbb}
    .alert-danger{background:#f8d7da;color:#721c24;border:1px solid #f5c6cb;padding:10px;border-radius:6px;margin-bottom:16px}
    .alert-danger ul{margin:8px 0 0 20px}
</style>

<div class="form-container">
    <h3 class="form-header">Edit Jadwal</h3>

    @if ($errors->any())
        <div class="alert-danger">
            <strong>Perbaiki input berikut:</strong>
            <ul>
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- FORM UTAMA (kosong, hanya token & method), field terhubung via atribut form= --}}
    <form id="scheduleEditForm" action="{{ route('admin.schedules.update', $schedule) }}" method="POST" novalidate>
        @csrf
        @method('PUT')
    </form>

    <div class="form-group">
        <label for="teacher_id">Pilih Guru</label>
        <select form="scheduleEditForm" name="teacher_id" id="teacher_id" required>
            @foreach($teachers as $teacher)
                <option value="{{ $teacher->id }}"
                    {{ old('teacher_id', $schedule->teacher_id) == $teacher->id ? 'selected' : '' }}>
                    {{ $teacher->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="students">Pilih Siswa</label>
        @php
            $selectedStudents = old('students', $schedule->students->pluck('id')->toArray());
        @endphp
        <select form="scheduleEditForm" name="students[]" id="students" multiple size="6" required>
            @foreach($students as $student)
                <option value="{{ $student->id }}" {{ in_array($student->id, $selectedStudents) ? 'selected' : '' }}>
                    {{ $student->nama }}
                </option>
            @endforeach
        </select>
        <small>Gunakan Ctrl (Windows) / Command (Mac) untuk pilih lebih dari satu.</small>
    </div>

    <div class="form-group">
        <label for="schedule_date">Tanggal</label>
        <input form="scheduleEditForm" type="date" name="schedule_date" id="schedule_date"
               value="{{ old('schedule_date', $schedule->schedule_date) }}" required>
    </div>

    <div class="form-group">
        <label for="start_time">Waktu Mulai</label>
        <input form="scheduleEditForm" type="time" name="start_time" id="start_time"
               value="{{ old('start_time', substr($schedule->start_time, 0, 5)) }}" required>
    </div>

    <div class="form-group">
        <label for="end_time">Waktu Selesai</label>
        <input form="scheduleEditForm" type="time" name="end_time" id="end_time"
               value="{{ old('end_time', substr($schedule->end_time, 0, 5)) }}" required>
    </div>

    <div class="form-group">
        <label for="jenis_kelas">Jenis Kelas</label>
        <select form="scheduleEditForm" name="jenis_kelas" id="jenis_kelas" required>
            <option value="windows" {{ old('jenis_kelas', $schedule->jenis_kelas) == 'windows' ? 'selected' : '' }}>Windows</option>
            <option value="linux"   {{ old('jenis_kelas', $schedule->jenis_kelas) == 'linux' ? 'selected' : '' }}>Linux</option>
            <option value="android" {{ old('jenis_kelas', $schedule->jenis_kelas) == 'android' ? 'selected' : '' }}>Android</option>
        </select>
    </div>

    <div class="form-group">
        <label for="status">Status</label>
        @php
            $statuses = ['pending'=>'Pending','confirmed'=>'Confirmed','cancelled'=>'Cancelled','revision'=>'Revision'];
        @endphp
        <select form="scheduleEditForm" name="status" id="status" required>
            @foreach($statuses as $val => $label)
                <option value="{{ $val }}" {{ old('status', $schedule->status) == $val ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>

    <button form="scheduleEditForm" type="submit" class="btn btn-primary">Perbarui</button>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Batal</a>
</div>

{{-- Debug optional --}}
<script>
document.addEventListener('DOMContentLoaded', function(){
    const f = document.getElementById('scheduleEditForm');
    if(f){ f.addEventListener('submit', () => console.log('[edit] submitting...')); }
});
</script>
@endsection

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
    textarea{resize:vertical;min-height:80px}
    .btn{padding:10px 18px;border-radius:8px;border:none;cursor:pointer;font-weight:600;transition:.3s;text-decoration:none}
    .btn-primary{background:#4CAF50;color:#fff}.btn-primary:hover{background:#45a049}
    .btn-secondary{background:#ccc;color:#333}.btn-secondary:hover{background:#bbb}
    .alert-danger{background:#f8d7da;color:#721c24;border:1px solid #f5c6cb;padding:10px;margin-bottom:20px;border-radius:5px}
    .alert-danger ul{margin-bottom:0;padding-left:20px}
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

    <form action="{{ route('admin.schedules.update', $schedule) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Guru --}}
        <div class="form-group">
            <label for="teacher_id">Pilih Guru</label>
            <select name="teacher_id" id="teacher_id" required>
                @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}" @selected(old('teacher_id', $schedule->teacher_id) == $teacher->id)>
                        {{ $teacher->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Siswa (multi select) --}}
        <div class="form-group">
            <label for="students">Pilih Siswa</label>
            @php $selected = old('students', $schedule->students->pluck('id')->toArray()); @endphp
            <select name="students[]" id="students" multiple size="6" required>
                @foreach($students as $student)
                    <option value="{{ $student->id }}" @selected(in_array($student->id, $selected))>
                        {{ $student->nama }}
                    </option>
                @endforeach
            </select>
            <small style="color:#777">* Tekan CTRL (Windows) / CMD (Mac) untuk memilih lebih dari satu.</small>
        </div>

        {{-- Tanggal & Waktu --}}
        <div class="form-group">
            <label for="schedule_date">Tanggal</label>
            <input type="date" name="schedule_date" id="schedule_date" value="{{ old('schedule_date', $schedule->schedule_date) }}" required>
        </div>
        <div class="form-group">
            <label for="start_time">Waktu Mulai</label>
            <input type="time" name="start_time" id="start_time" value="{{ old('start_time', $schedule->start_time) }}" required>
        </div>
        <div class="form-group">
            <label for="end_time">Waktu Selesai</label>
            <input type="time" name="end_time" id="end_time" value="{{ old('end_time', $schedule->end_time) }}" required>
        </div>

        {{-- Jenis Kelas --}}
        <div class="form-group">
            <label for="jenis_kelas">Jenis Kelas</label>
            <select name="jenis_kelas" id="jenis_kelas" required>
                <option value="windows" @selected(old('jenis_kelas', $schedule->jenis_kelas) == 'windows')>Windows</option>
                <option value="linux"   @selected(old('jenis_kelas', $schedule->jenis_kelas) == 'linux')>Linux</option>
                <option value="android" @selected(old('jenis_kelas', $schedule->jenis_kelas) == 'android')>Android</option>
            </select>
        </div>

        {{-- Status (disamakan dengan sistem lain) --}}
        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status" required>
                <option value="pending"   @selected(old('status', $schedule->status) == 'pending')>Pending</option>
                <option value="approved"  @selected(old('status', $schedule->status) == 'approved')>Approved</option>
                <option value="completed" @selected(old('status', $schedule->status) == 'completed')>Completed</option>
                <option value="revision"  @selected(old('status', $schedule->status) == 'revision')>Revision</option>
                <option value="cancelled" @selected(old('status', $schedule->status) == 'cancelled')>Cancelled</option>
            </select>
        </div>

        {{-- Opsional: Catatan Revisi --}}
        <div class="form-group">
            <label for="revision_note">Catatan Revisi</label>
            <textarea name="revision_note" id="revision_note">{{ old('revision_note', $schedule->revision_note) }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Perbarui</button>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection

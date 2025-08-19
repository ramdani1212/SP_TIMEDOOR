@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Form Absensi</h2>

    <form action="{{ route('attendances.store') }}" method="POST">
        @csrf

        <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">

        <div id="students">
            <div class="student-entry mb-3">
                <label>Nama Siswa</label>
                <input type="text" name="students[0][name]" class="form-control" required>

                <label>Hadir?</label>
                <select name="students[0][is_present]" class="form-control">
                    <option value="1">Hadir</option>
                    <option value="0">Tidak Hadir</option>
                </select>
            </div>
        </div>

        <button type="button" onclick="addStudent()">+ Tambah Siswa</button>
        <br><br>
        <button type="submit" class="btn btn-primary">Submit Absensi</button>
    </form>
</div>

<script>
    let count = 1;
    function addStudent() {
        const html = `
        <div class="student-entry mb-3">
            <label>Nama Siswa</label>
            <input type="text" name="students[${count}][name]" class="form-control" required>

            <label>Hadir?</label>
            <select name="students[${count}][is_present]" class="form-control">
                <option value="1">Hadir</option>
                <option value="0">Tidak Hadir</option>
            </select>
        </div>
        `;
        document.getElementById('students').insertAdjacentHTML('beforeend', html);
        count++;
    }
</script>
@endsection

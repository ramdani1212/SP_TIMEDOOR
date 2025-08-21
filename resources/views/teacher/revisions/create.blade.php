@extends('teacher.layouts.app')

@section('title', 'Kirim Catatan Revisi')

@section('content')
<div class="dashboard-container">
    <h2>Kirim Catatan Revisi</h2>
    <p>Jadwal: {{ $schedule->jenis_kelas }}</p>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="form-container">
        <form action="{{ route('teacher.revisions.store', $schedule->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="note">Catatan Revisi:</label>
                <textarea name="note" id="note" class="form-control" rows="5" required></textarea>
            </div>
            <button type="submit" class="submit-button">Kirim Catatan</button>
        </form>
    </div>
</div>
@endsection
@extends('teacher.layouts.app')

@section('title', 'Kirim Catatan Revisi')

@section('content')
<style>
    .form-container {
        max-width: 600px;
        margin: 40px auto;
        padding: 20px;
        background-color: white;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }
    .form-group {
        margin-bottom: 20px;
    }
    label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
        color: #555;
    }
    textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-sizing: border-box;
    }
    .submit-button {
        width: 100%;
        background-color: #4CAF50;
        color: white;
        padding: 12px;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }
    .submit-button:hover {
        background-color: #45a049;
    }
    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid transparent;
        border-radius: 4px;
    }
    .alert-success {
        color: #155724;
        background-color: #d4edda;
        border-color: #c3e6cb;
    }
</style>
<div class="dashboard-container">
    <h2>Kirim Catatan Revisi</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="form-container">
        <form action="{{ route('teacher.schedules.submitRevision', $schedule->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="note">Catatan Revisi untuk Jadwal {{ $schedule->jenis_kelas }}:</label>
                <textarea name="note" id="note" class="form-control" rows="5" required></textarea>
            </div>
            <button type="submit" class="submit-button">Kirim Catatan</button>
        </form>
    </div>
</div>
@endsection
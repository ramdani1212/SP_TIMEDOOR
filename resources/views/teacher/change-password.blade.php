@extends('teacher.layouts.app')

@section('title', 'Ubah Password')

@section('content')
<div class="form-container">
    <h2>Ubah Password</h2>

    @if(session('success'))
        <div class="success-message">
            {{ session('success') }}
        </div>
    @endif
    
    <form action="{{ route('teacher.password.update') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="current_password">Password Saat Ini</label>
            <input type="password" id="current_password" name="current_password" required>
            @error('current_password')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="new_password">Password Baru</label>
            <input type="password" id="new_password" name="new_password" required>
            @error('new_password')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="new_password_confirmation">Konfirmasi Password Baru</label>
            <input type="password" id="new_password_confirmation" name="new_password_confirmation" required>
        </div>

        <button type="submit" class="submit-button">Ubah Password</button>
    </form>
</div>
@endsection
@extends('admin.layouts.app')

@section('title', 'Ubah Password')

@section('content')
<style>
    /* Styling for the change password form */
    .form-container {
        max-width: 500px;
        margin: 40px auto;
        padding: 30px;
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }
    .form-container h2 {
        text-align: center;
        color: #66BB6A;
        margin-bottom: 25px;
        font-weight: bold;
    }
    .form-group {
        margin-bottom: 20px;
    }
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
        color: #555;
    }
    .form-group input {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-sizing: border-box;
        transition: border-color 0.3s ease;
    }
    .form-group input:focus {
        border-color: #66BB6A;
        outline: none;
    }
    .submit-button {
        width: 100%;
        padding: 12px;
        background-color: #66BB6A;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }
    .submit-button:hover {
        background-color: #5cb85c;
    }
    .success-message, .error-message {
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 20px;
        text-align: center;
    }
    .success-message {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    .error-message {
        color: #dc3545;
        font-size: 0.9em;
        margin-top: 5px;
    }
    .back-link {
        display: block;
        text-align: center;
        margin-top: 20px;
        color: #66BB6A;
        text-decoration: none;
        transition: color 0.3s ease;
    }
    .back-link:hover {
        text-decoration: underline;
    }
</style>

<div class="form-container">
    <h2>Ubah Password</h2>

    @if(session('success'))
        <div class="success-message">
            {{ session('success') }}
        </div>
    @endif
    
    <form action="{{ route('admin.password.update') }}" method="POST">
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
    
    <a href="{{ route('admin.dashboard') }}" class="back-link">Kembali ke Dashboard</a>
</div>
@endsection
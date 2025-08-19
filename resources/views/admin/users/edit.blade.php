@extends('admin.layouts.app')

@section('title', 'Edit Pengguna')

@section('content')
<style>
    .form-container { max-width: 600px; margin: 40px auto; padding: 20px; background-color: white; border-radius: 12px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); }
    .form-group { margin-bottom: 20px; }
    label { display: block; margin-bottom: 8px; font-weight: bold; color: #555; }
    input[type="text"], input[type="email"], input[type="password"], select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; }
    .submit-button { width: 100%; background-color: #4CAF50; color: white; padding: 12px; border: none; border-radius: 8px; font-size: 1rem; cursor: pointer; }
    .back-link { display: block; text-align: center; margin-top: 20px; color: #008CBA; text-decoration: none; }
    .error-message { color: #d9534f; font-size: 0.9em; margin-top: 5px; }
</style>

<div class="form-container">
    <h2>Edit Pengguna</h2>
    
    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="name">Nama</label>
            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
            @error('name')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
            @error('email')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="role">Peran (Role)</label>
            <select name="role" id="role" required>
                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="teacher" {{ $user->role == 'teacher' ? 'selected' : '' }}>Guru</option>
            </select>
            @error('role')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="password">Password Baru (kosongkan jika tidak ingin mengubah)</label>
            <input type="password" id="password" name="password">
            @error('password')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="password_confirmation">Konfirmasi Password Baru</label>
            <input type="password" id="password_confirmation" name="password_confirmation">
        </div>
        
        <button type="submit" class="submit-button">Simpan Perubahan</button>
    </form>
    
    <a href="{{ route('admin.users.index') }}" class="back-link">Kembali</a>
</div>
@endsection
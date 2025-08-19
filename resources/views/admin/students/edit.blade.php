@extends('admin.layouts.app')

@section('title', 'Edit Data Siswa')

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
    .form-row {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
    }
    .form-group {
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    label {
        font-weight: 600;
        margin-bottom: 8px;
        color: #555;
    }
    input, textarea {
        padding: 10px 12px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 1rem;
        transition: 0.2s;
    }
    input:focus, textarea:focus {
        border-color: #4CAF50;
        box-shadow: 0 0 5px rgba(76,175,80,0.4);
        outline: none;
    }
    textarea {
        resize: vertical;
        min-height: 80px;
    }
    .button-group {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        margin-top: 25px;
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
</style>

<div class="form-container">
    <div class="form-header">Edit Data Siswa</div>
    <form action="{{ route('admin.students.update', $student->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-row">
            <div class="form-group">
                <label for="nama">Nama</label>
                <input type="text" name="nama" id="nama" value="{{ $student->nama }}" required>
            </div>
            <div class="form-group" style="max-width:150px;">
                <label for="umur">Umur</label>
                <input type="number" name="umur" id="umur" value="{{ $student->umur }}">
            </div>
        </div>

        <div class="form-group">
            <label for="alamat">Alamat</label>
            <textarea name="alamat" id="alamat">{{ $student->alamat }}</textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="no_telp">Nomor Telepon</label>
                <input type="text" name="no_telp" id="no_telp" value="{{ $student->no_telp }}">
            </div>
            <div class="form-group">
                <label for="nama_orang_tua">Nama Orang Tua</label>
                <input type="text" name="nama_orang_tua" id="nama_orang_tua" value="{{ $student->nama_orang_tua }}">
            </div>
        </div>

        <div class="button-group">
            <button type="submit" class="btn btn-primary">Perbarui</button>
            <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

@endsection

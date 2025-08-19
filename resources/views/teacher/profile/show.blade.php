@extends('teacher.layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="dashboard-container">
    <h2>Profil Saya</h2>
    <hr>
    <div style="margin-top: 20px;">
        <p><strong>Nama:</strong> {{ $teacher->name }}</p>
        <p><strong>Email:</strong> {{ $teacher->email }}</p>
        </div>
    <a href="{{ route('teacher.dashboard') }}" class="back-link">Kembali ke Dashboard</a>
</div>
@endsection
@extends('teacher.layouts.app')

@section('title', 'Profil Saya')

@section('content')
<style>
    .profile-card {
        max-width: 400px;
        margin: 50px auto;
        padding: 30px;
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        text-align: center;
        transition: transform 0.3s ease-in-out;
    }
    
    .profile-card:hover {
        transform: translateY(-5px);
    }

    .profile-card h2 {
        color: #4CAF50;
        margin-bottom: 25px;
        font-size: 1.8rem;
        font-weight: 600;
        border-bottom: 2px solid #e8e8e8;
        padding-bottom: 15px;
    }

    .profile-info {
        text-align: left;
    }

    .info-item {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
        font-size: 1rem;
        color: #555;
        background-color: #f9f9f9;
        padding: 12px 15px;
        border-radius: 8px;
        border: 1px solid #eee;
    }

    .info-item strong {
        font-weight: bold;
        color: #333;
        width: 80px;
        margin-right: 15px;
    }

    .info-item i {
        color: #4CAF50;
        margin-right: 10px;
    }
</style>

<div class="dashboard-container">
    <div class="profile-card">
        <h2>Profil Saya</h2>
        @if(isset($teacher))
        <div class="profile-info">
            <div class="info-item">
                <i class="fas fa-user-alt"></i> <strong>Nama:</strong> <span>{{ $teacher->name }}</span>
            </div>
            <div class="info-item">
                <i class="fas fa-envelope"></i> <strong>Email:</strong> <span>{{ $teacher->email }}</span>
            </div>
            <div class="info-item">
                <i class="fas fa-user-tag"></i> <strong>Role:</strong> <span>{{ $teacher->role }}</span>
            </div>
        </div>
        @else
        <p>Informasi profil tidak tersedia.</p>
        @endif
    </div>
</div>
@endsection
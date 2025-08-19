<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Panel')</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/sass/app.sass', 'resources/js/app.js'])

    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #F0FFF4; }
        .wrapper { display: flex; min-height: 100vh; }
        .sidebar { width: 250px; background-color: #4CAF50; color: white; padding: 20px; box-shadow: 2px 0 5px rgba(0,0,0,0.1); }
        .sidebar h2 { text-align: center; margin-bottom: 30px; }
        .sidebar ul { list-style: none; padding: 0; }
        .sidebar ul li { margin-bottom: 15px; }
        .sidebar ul li a { 
            color: black;
            text-decoration: none; 
            display: block; 
            padding: 10px; 
            border-radius: 5px; 
            transition: background-color 0.3s, color 0.3s; 
        }
        .sidebar ul li a:hover,
        .sidebar ul li a.active {
            background-color: white;
            color: #4CAF50;
        }
        .content { flex-grow: 1; padding: 0; display: flex; flex-direction: column; }
        
        /* Perubahan di sini untuk topbar */
        .topbar {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            background: #E8F5E9;
            padding: 10px 20px;
            border-bottom: 1px solid #ddd;
            gap: 15px;
        }
        .topbar-profile-section {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .profile-img {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            object-fit: cover;
        }
        .profile-name {
            font-weight: bold;
            color: #333;
        }
        .notif-icon {
            position: relative;
            font-size: 20px;
            color: #555;
            cursor: pointer;
        }
        .notif-icon:hover { color: #4CAF50; }
        .notif-badge {
            position: absolute;
            top: -5px;
            right: -8px;
            background: red;
            color: white;
            font-size: 12px;
            border-radius: 50%;
            padding: 2px 6px;
        }
        
        .profile-btn {
            background-color: #008CBA;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
        }
        .logout-button { background-color: #f44336; color: white; padding: 8px 15px; border: none; border-radius: 5px; cursor: pointer; display: block; width: 100%; margin-top: 20px; }
        .dashboard-container { max-width: 900px; margin: 40px auto; padding: 20px; background-color: white; border-radius: 12px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); }
        h1, h2 { color: #4CAF50; text-align: center; }
        p { text-align: center; color: #666; }
        .create-button { background-color: #008CBA; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; display: inline-block; margin-bottom: 20px; }
        .success-message { background-color: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; text-align: left; margin-top: 20px; }
        th, td { padding: 12px; border: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
        .action-button { padding: 5px 10px; border-radius: 5px; text-decoration: none; font-size: 0.9em; border: none; cursor: pointer; }
        .edit-button { background-color: #ffc107; color: black; border: none; }
        .delete-button { background-color: #f44336; color: white; border: none; cursor: pointer; }
        .revision-note { background-color: #fff3cd; padding: 10px; border-radius: 5px; margin-top: 5px; border-left: 3px solid #ffc107; }
        .form-container { max-width: 600px; margin: 40px auto; padding: 20px; background-color: white; border-radius: 12px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; color: #555; }
        input[type="text"], input[type="email"], input[type="password"], input[type="date"], input[type="time"], select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; }
        .submit-button { width: 100%; background-color: #4CAF50; color: white; padding: 12px; border: none; border-radius: 8px; font-size: 1rem; cursor: pointer; }
        .back-link { display: block; text-align: center; margin-top: 20px; color: #008CBA; text-decoration: none; }
        .error-message { color: #d9534f; font-size: 0.9em; margin-top: 5px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="sidebar">
            <h2>Admin Panel</h2>
            <ul>
                <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li><a href="{{ route('admin.users.index') }}">Kelola Pengguna</a></li>
            </ul>
            <form action="{{ route('admin.logout') }}" method="POST" style="margin-top: auto; text-align: center;">
                @csrf
                <button type="submit" class="logout-button">Logout</button>
            </form>
        </div>
        <div class="content">
            
            {{-- Topbar yang sudah disesuaikan --}}
            <div class="topbar">
                @if(Auth::user()->unreadNotifications->count() > 0)
                    <a href="{{ route('notifications.index') }}" class="notif-icon">
                        <i class="fas fa-bell"></i>
                        <span class="notif-badge">{{ Auth::user()->unreadNotifications->count() }}</span>
                    </a>
                @endif
                <div class="topbar-profile-section">
                    <img src="{{ Auth::user()->profile_image_url ?? asset('images/default_profile.png') }}" alt="Profile" class="profile-img">
                    <span class="profile-name">{{ Auth::user()->name ?? 'Admin' }}</span>
                </div>
                <a href="{{ route('profile.show') }}" class="profile-btn">Lihat Profile</a>
            </div>

            {{-- Main Content --}}
            <main class="py-4">
                @yield('content')
            </main>
        </div>
    </div>
    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const currentPath = window.location.href;
            const navLinks = document.querySelectorAll('.sidebar ul li a');

            navLinks.forEach(link => {
                if (link.href === currentPath) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>
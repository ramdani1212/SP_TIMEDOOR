<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Teacher Panel')</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
    body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #E8F5E9; }
    .wrapper { display: flex; min-height: 100vh; }
    .sidebar { 
        width: 250px; 
        background-color: #4CAF50;
        color: white; 
        padding: 20px; 
        box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        position: fixed;
        height: 100%;
        transition: width 0.3s ease, padding 0.3s ease;
        overflow: hidden;
    }
    .sidebar.closed {
        width: 0;
        padding: 0;
    }
    .logo-image {
        display: block; 
        width: 150px;
        margin: 0 auto 30px auto; 
    }
    .sidebar h2 {
        text-align: center;
        margin-bottom: 30px;
        color: white;
        font-weight: bold;
    }
    .sidebar.closed h2, .sidebar.closed ul, .sidebar.closed .logout-form {
        display: none;
    }
    .sidebar ul { list-style: none; padding: 0; }
    .sidebar ul li { margin-bottom: 15px; }
    .sidebar ul li a { 
        color: white;
        text-decoration: none; 
        display: block; 
        padding: 10px; 
        border-radius: 5px; 
        white-space: nowrap;
        transition: background-color 0.3s, color 0.3s; 
    }
    .sidebar ul li a i {
        margin-right: 10px;
    }
    .sidebar ul li a:hover,
    .sidebar ul li a.active {
        background-color: white;
        color: #4CAF50;
    }
    .content { 
        flex-grow: 1; 
        padding: 40px;
        margin-left: 250px;
        transition: margin-left 0.3s ease;
        position: relative;
    }
    .content.full-width {
        margin-left: 0;
    }
    .top-header {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        padding-bottom: 20px;
        margin-bottom: 20px;
        border-bottom: 1px solid #ddd;
    }
    .top-header a {
        text-decoration: none;
        color: white;
        background-color: #2196F3;
        padding: 8px 15px;
        border-radius: 5px;
        margin-left: 10px;
        transition: background-color 0.3s ease;
    }
    .top-header a:hover {
        background-color: #1976D2;
    }
    .top-header .password-btn {
        background-color: #FF9800;
    }
    .top-header .password-btn:hover {
        background-color: #FB8C00;
    }
    .logout-button { background-color: #f44336; color: white; padding: 8px 15px; border: none; border-radius: 5px; cursor: pointer; display: block; width: 100%; margin-top: 20px; }
    .dashboard-container { 
        max-width: 900px; 
        margin: 40px auto; 
        padding: 20px; 
        background-color: white; 
        border-radius: 12px; 
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        border-top: 1px solid #ddd;
    }
    h1, h2 { color: #4CAF50; text-align: center; }
    p { text-align: center; color: #666; }
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

    .sidebar-toggle {
        position: fixed;
        top: 15px;
        left: 340px;
        z-index: 1000;
        background-color: #4CAF50;
        color: white;
        border: none;
        padding: 8px 12px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1.2rem;
        box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        transition: left 0.3s ease;
    }
    .sidebar.closed + .content .sidebar-toggle {
        left: 10px;
    }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="sidebar" id="sidebar">
            <img src="{{ asset('images/logo.png') }}" alt="Logo Admin" class="logo-image">
            <ul>
                <li><a href="{{ route('teacher.dashboard') }}"><i class="fas fa-tachometer-alt"></i>Dashboard</a></li>
                <li><a href="{{ route('teacher.notifications.index') }}"><i class="fas fa-bell"></i>Riwayat Notifikasi</a></li>
            </ul>
            <form action="{{ route('teacher.logout') }}" method="POST" style="margin-top: auto; text-align: center;">
                @csrf
                <button type="submit" class="logout-button">Logout</button>
            </form>
        </div>
        <div class="content" id="content">
            <button id="sidebarToggle" class="sidebar-toggle">«</button>
            
            <div class="top-header">
                @if(Auth::check())
                <a href="{{ route('teacher.profile.show') }}" class="profile-btn"><i class="fas fa-user-circle"></i> {{ Auth::user()->name }} | {{ Auth::user()->role }}</a>
                @else
                <a href="{{ route('teacher.profile.show') }}" class="profile-btn"><i class="fas fa-user-circle"></i> Lihat Profile</a>
                @endif
                <a href="{{ route('teacher.password.edit') }}" class="password-btn" aria-label="Ubah Password"><i class="fas fa-key"></i></a>
            </div>
            
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

            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('content');
            const toggleButton = document.getElementById('sidebarToggle');
            
            toggleButton.addEventListener('click', function() {
                sidebar.classList.toggle('closed');
                content.classList.toggle('full-width');
                if (sidebar.classList.contains('closed')) {
                    toggleButton.innerHTML = '»';
                } else {
                    toggleButton.innerHTML = '«';
                }
            });
        });
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Timedoor Academy</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>
<body class="bg-light-green font-inter">

    <div id="admin-dashboard" class="dashboard-container">
        <header class="header-card">
            <h1>Dashboard Admin</h1>
            <p>Kelola semua jadwal guru.</p>
        </header>

        <section class="notification-panel">
            <h2>Notifikasi Terbaru</h2>
            <ul id="notification-list">
                </ul>
        </section> 

        <section class="schedule-panel">
            <h2>Daftar Jadwal</h2>
            <div id="schedule-list">
                </div>
        </section>

        <footer class="text-center mt-8">
            <button id="logout-button-admin" class="button-primary">Logout</button>
        </footer>
    </div>

    <div id="teacher-dashboard" class="dashboard-container" style="display: none;">
        <header class="header-card">
            <h1>Dashboard Guru</h1>
            <p>Kelola jadwal mengajar Anda.</p>
        </header>

        <section class="schedule-panel">
            <h2>Jadwal Saya</h2>
            <button id="add-schedule-button" class="button-primary mb-4">Tambah Jadwal</button>
            <div id="teacher-schedule-list">
                </div>
        </section>

        <footer class="text-center mt-8">
            <button id="logout-button-teacher" class="button-primary">Logout</button>
        </footer>
    </div>

    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
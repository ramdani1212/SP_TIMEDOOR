@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
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
        border-bottom: 1px solid #ddd;
        margin-bottom: 20px;
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
    .dashboard-container { max-width: 900px; margin: 40px auto; padding: 20px; background-color: white; border-radius: 12px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); }
    h1, h2, h3 { color: #4CAF50; }
    p { color: #666; }
    .success-message { background-color: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { padding: 12px 15px; border: 1px solid #ddd; text-align: left; }
    th { background-color: #4CAF50; color: white; font-weight: bold; }
    th.aksi-header, td.aksi-cell { text-align: center; }
    tr:nth-child(even) { background-color: #f2f2f2; }
    tr:hover { background-color: #f1f1f1; }
    .action-buttons-container { display: flex; gap: 8px; justify-content: center; align-items: center; }
    .action-button { 
        display: inline-block; 
        padding: 8px 12px; 
        border-radius: 6px; 
        font-weight: bold; 
        color: white; 
        text-decoration: none; 
        transition: background-color 0.3s ease; 
        border: none; 
        cursor: pointer; 
        box-sizing: border-box; 
        font-size: 14px; 
        line-height: 1; 
        white-space: nowrap; 
    }
    .create-button-container { margin-bottom: 20px; text-align: left; }
    .create-button {
        display: inline-block;
        background-color: #4CAF50;
        color: white;
        padding: 10px 15px;
        text-align: center;
        text-decoration: none;
        border-radius: 5px;
        transition: background-color 0.3s ease;
        font-weight: bold;
    }
    .create-button:hover { background-color: #45a049; }
    .success-message { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 10px; margin-bottom: 20px; border-radius: 5px; text-align: center; }
    
    .edit-button { background-color: #ffc107; }
    .edit-button:hover { background-color: #e0a800; }
    .delete-button { background-color: #dc3545; }
    .delete-button:hover { background-color: #c82333; }
    .action-buttons-container > form { display: contents; }
    .revision-note { font-style: italic; color: #666; }
    .form-container { max-width: 600px; margin: 40px auto; padding: 20px; background-color: white; border-radius: 12px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); }
    .form-group { margin-bottom: 20px; }
    label { display: block; margin-bottom: 8px; font-weight: bold; color: #555; }
    input[type="text"], input[type="email"], input[type="password"], input[type="date"], input[type="time"], select, textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; }
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
    .sidebar.closed + .content .sidebar-toggle { left: 10px; }
    .d-flex { display: flex; }
    .justify-content-between { justify-content: space-between; }
    .align-items-center { align-items: center; }
    .btn { padding: .375rem .75rem; font-size: 1rem; line-height: 1.5; border-radius: .25rem; text-decoration: none; display: inline-block; cursor: pointer; border: 1px solid transparent; }
    .btn-success { color: #fff; background-color: #28a745; border-color: #28a745; }
    .btn-warning { color: #212529; background-color: #ffc107; border-color: #ffc107; }
    .btn-danger { color: #fff; background-color: #dc3545; border-color: #dc3545; }
    .btn-primary { color: #fff; background-color: #007bff; border-color: #007bff; }
    .btn-secondary { color: #fff; background-color: #6c757d; border-color: #6c757d; }
    .btn-sm { padding: .25rem .5rem; font-size: .875rem; line-height: 1.5; border-radius: .2rem; }
    .card { position: relative; display: flex; flex-direction: column; min-width: 0; word-wrap: break-word; background-color: #fff; background-clip: border-box; border: 1px solid rgba(0,0,0,.125); border-radius: .25rem; }
    .card-header { padding: .75rem 1.25rem; margin-bottom: 0; background-color: rgba(0,0,0,.03); border-bottom: 1px solid rgba(0,0,0,.125); }
    .card-body { flex: 1 1 auto; padding: 1.25rem; }
    .table-responsive { display: block; width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .table { width: 100%; margin-bottom: 1rem; color: #212529; }
    .table-striped tbody tr:nth-of-type(odd) { background-color: rgba(0,0,0,.05); }
    .table-hover tbody tr:hover { color: #212529; background-color: rgba(0,0,0,.075); }
    .alert { position: relative; padding: .75rem 1.25rem; margin-bottom: 1rem; border: 1px solid transparent; border-radius: .25rem; }
    .alert-success { color: #155724; background-color: #d4edda; border-color: #c3e6cb; }
    .d-inline { display: inline-block; }
</style>
<div class="dashboard-container">
    <h1>Dashboard Admin</h1>
    <p>Selamat datang, Admin!</p>
    
    <div class="centered-title">
        <h2>Daftar Semua Jadwal</h2>
    </div>

    <div class="create-button-container">
        <a href="{{ route('admin.schedules.create') }}" class="create-button">Buat Jadwal Baru</a>
    </div>

    @if(session('success'))
        <div class="success-message">
            {{ session('success') }}
        </div>
    @endif

    @if($schedules->isEmpty())
        <p style="text-align: center;">Tidak ada jadwal yang tersedia.</p>
    @else
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Guru</th>
                        <th>Siswa</th>
                        <th>Tanggal</th>
                        <th>Waktu</th>
                        <th>Status</th>
                        <th>Catatan Revisi</th>
                        <th>Jenis Kelas</th>
                        <th class="aksi-header">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($schedules as $schedule)
                    <tr>
                        <td>{{ $schedule->teacher->name }}</td>
                        <td>
                            @if($schedule->students->isNotEmpty())
                                @foreach($schedule->students as $student)
                                    {{ $student->nama }}<br>
                                @endforeach
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $schedule->schedule_date }}</td>
                        <td>{{ $schedule->start_time }} - {{ $schedule->end_time }}</td>
                        <td>{{ $schedule->status }}</td>
                        <td>
                            @if($schedule->status == 'revision' && $schedule->revision_note)
                                <span class="revision-note">{{ $schedule->revision_note }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $schedule->jenis_kelas }}</td>
                        <td class="aksi-cell">
                            <div class="action-buttons-container">
                                <a href="{{ route('admin.schedules.edit', $schedule) }}" class="action-button edit-button">Edit</a>
                                <form action="{{ route('admin.schedules.destroy', $schedule) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-button delete-button" onclick="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?');">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
@extends('admin.layouts.app')

@section('title', 'Daftar Siswa')

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
    /* CSS untuk logo */
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
    h1, h2, h3 { color: #4CAF50; } /* Tambahkan h3 di sini */
    p { color: #666; }
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
    /* CSS tambahan untuk tabel di index.blade.php */
    .d-flex { display: flex; }
    .justify-content-between { justify-content: space-between; }
    .align-items-center { align-items: center; }
    .btn { padding: .375rem .75rem; font-size: 1rem; line-height: 1.5; border-radius: .25rem; text-decoration: none; display: inline-block; cursor: pointer; }
    .btn-success { color: #fff; background-color: #28a745; border-color: #28a745; }
    .btn-warning { color: #212529; background-color: #ffc107; border-color: #ffc107; }
    .btn-danger { color: #fff; background-color: #dc3545; border-color: #dc3545; }
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

<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3>Daftar Siswa</h3>
                <a href="{{ route('admin.students.create') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> Tambah Siswa Baru
                </a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if ($students->isEmpty())
                <p class="text-center">Tidak ada data siswa yang tersedia.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Umur</th>
                                <th>Alamat</th>
                                <th>Nomor Telepon</th>
                                <th>Nama Orang Tua</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $student)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $student->nama }}</td>
                                    <td>{{ $student->umur ?? '-' }}</td>
                                    <td>{{ $student->alamat ?? '-' }}</td>
                                    <td>{{ $student->no_telp ?? '-' }}</td>
                                    <td>{{ $student->nama_orang_tua ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route('admin.students.edit', $student->id) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                <i class="fas fa-trash-alt"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
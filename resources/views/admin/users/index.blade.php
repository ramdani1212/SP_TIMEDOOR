@extends('admin.layouts.app')

@section('title', 'Kelola Pengguna')

@section('content')
<style>
    /* Styling untuk tombol "Tambah Pengguna Baru" */
    .create-button-container { margin-bottom: 20px; }
    .create-button {
        display: inline-block;
        background-color: #4CAF50; /* Warna hijau, sesuai tema admin */
        color: white;
        padding: 10px 15px;
        text-align: center;
        text-decoration: none;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }
    .create-button:hover {
        background-color: #45a049;
    }
    .dashboard-container { max-width: 900px; margin: 40px auto; padding: 20px; background-color: white; border-radius: 12px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); }
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
    .edit-button { background-color: #ffc107; }
    .edit-button:hover { background-color: #e0a800; }
    .delete-button { background-color: #dc3545; }
    .delete-button:hover { background-color: #c82333; }
    .success-message { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 10px; margin-bottom: 20px; border-radius: 5px; text-align: center; }
</style>

<div class="dashboard-container">
    <h2>Kelola Pengguna</h2>
    
    <div class="create-button-container">
        <a href="{{ route('admin.users.create') }}" class="create-button">Tambah Pengguna Baru</a>
    </div>

    @if(session('success'))
        <div class="success-message">
            {{ session('success') }}
        </div>
    @endif
    
    @if($users->isEmpty())
        <p style="text-align: center;">Tidak ada data pengguna yang tersedia.</p>
    @else
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Peran (Role)</th>
                        <th class="aksi-header">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ ucfirst($user->role) }}</td>
                        <td class="aksi-cell">
                            <div class="action-buttons-container">
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="action-button edit-button">Edit</a>
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-button delete-button" onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">Hapus</button>
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
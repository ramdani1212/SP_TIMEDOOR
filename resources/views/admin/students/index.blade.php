@extends('admin.layouts.app')

@section('title', 'Daftar Siswa')

@section('content')
<style>
    body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #E8F5E9; }

    /* ===== Layout dasar (sidebar dll—biarkan jika dipakai layout) ===== */
    .wrapper { display: flex; min-height: 100vh; }
    .sidebar { width: 250px; background-color: #4CAF50; color: #fff; padding: 20px;
        box-shadow: 2px 0 5px rgba(0,0,0,.1); position: fixed; height: 100%; transition: width .3s, padding .3s; overflow: hidden; }
    .sidebar.closed { width: 0; padding: 0; }
    .logo-image { display: block; width: 150px; margin: 0 auto 30px; }
    .sidebar h2 { text-align: center; margin-bottom: 30px; font-weight: 700; }
    .sidebar.closed h2, .sidebar.closed ul, .sidebar.closed .logout-form { display: none; }
    .sidebar ul { list-style: none; padding: 0; }
    .sidebar ul li { margin-bottom: 15px; }
    .sidebar ul li a { color: #fff; text-decoration: none; display: block; padding: 10px; border-radius: 5px;
        white-space: nowrap; transition: background-color .3s, color .3s; }
    .sidebar ul li a i { margin-right: 10px; }
    .sidebar ul li a:hover, .sidebar ul li a.active { background-color: #fff; color: #4CAF50; }

    .content { flex-grow: 1; padding: 40px; margin-left: 250px; transition: margin-left .3s; position: relative; }
    .content.full-width { margin-left: 0; }

    .top-header { display:flex; justify-content:flex-end; align-items:center; padding-bottom:20px; border-bottom:1px solid #ddd; margin-bottom:20px; }
    .top-header a { text-decoration:none; color:#fff; background:#2196F3; padding:8px 15px; border-radius:5px; margin-left:10px; transition:background-color .3s; }
    .top-header a:hover { background:#1976D2; }
    .top-header .password-btn { background:#FF9800; }
    .top-header .password-btn:hover { background:#FB8C00; }

    .logout-button { background:#f44336; color:#fff; padding:8px 15px; border:none; border-radius:5px; cursor:pointer; width:100%; margin-top:20px; }

    .dashboard-container { max-width: 900px; margin: 40px auto; padding: 20px; background: #fff;
        border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,.1); }
    h1, h2, h3 { color: #4CAF50; }
    p { color:#666; }
    .success-message { background:#d4edda; color:#155724; padding:10px; border-radius:5px; margin-bottom:15px; }

    /* ===== TABEL HIJAU ===== */
    .table-wrapper{ overflow-x:auto; border-radius:10px; box-shadow: 0 6px 16px rgba(0,0,0,.06); }
    .table { width:100%; border-collapse:separate; border-spacing:0; margin-top:20px; }
    .table thead th {
        background:#43a047; color:#fff; font-weight:700; border-right:1px solid #3c8f42; padding:12px;
    }
    .table thead th:last-child{ border-right:0; }
    .table tbody td { padding:12px; border-bottom:1px solid #e6efe8; }
    /* ganjil putih, genap abu tebal */
    .table tbody tr:nth-child(odd)  { background:#ffffff; }
    .table tbody tr:nth-child(even) { background:#f0f0f0; }
    .table tbody tr:hover{ background:#e8f5e9; }
    .col-actions{ width:170px; white-space:nowrap; }

    /* ===== Tombol aksi: flat, tipis, teks putih ===== */
    .btn { display:inline-block; font-size:.85rem; font-weight:500; padding:4px 12px;
           border:none; border-radius:6px; cursor:pointer; line-height:1.2; text-decoration:none; }
    .btn-success { background:#28a745; color:#fff; } /* tombol tambah */
    .btn-success:hover{ background:#218838; }

    .btn-edit   { background:#ffc107; color:#fff; }  /* kuning, teks putih */
    .btn-edit:hover   { background:#e0a800; }

    .btn-delete { background:#dc3545; color:#fff; }  /* merah, teks putih */
    .btn-delete:hover { background:#bd2130; }

    /* utilitas */
    .card { position:relative; display:flex; flex-direction:column; background:#fff; border:1px solid rgba(0,0,0,.125); border-radius:.25rem; }
    .card-header { padding:.75rem 1.25rem; background:rgba(0,0,0,.03); border-bottom:1px solid rgba(0,0,0,.125); }
    .card-body { padding:1.25rem; }
    .alert { position:relative; padding:.75rem 1.25rem; margin-bottom:1rem; border:1px solid transparent; border-radius:.25rem; }
    .alert-success { color:#155724; background:#d4edda; border-color:#c3e6cb; }
    .d-flex{display:flex;} .justify-content-between{justify-content:space-between;} .align-items-center{align-items:center;}
    .d-inline{display:inline-block;}
</style>

<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3>Daftar Siswa</h3>
                <a href="{{ route('admin.students.create') }}" class="btn btn-success">
                    Tambah Siswa Baru
                </a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if ($students->isEmpty())
                <p class="text-center">Tidak ada data siswa yang tersedia.</p>
            @else
                <div class="table-wrapper">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width:60px;">No</th>
                                <th>Nama</th>
                                <th style="width:90px;">Umur</th>
                                <th>Alamat</th>
                                <th>Nomor Telepon</th>
                                <th>Nama Orang Tua</th>
                                <th class="col-actions">Aksi</th>
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
                                        <a href="{{ route('admin.students.edit', $student->id) }}" class="btn btn-edit">Edit</a>
                                        <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-delete">Hapus</button>
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

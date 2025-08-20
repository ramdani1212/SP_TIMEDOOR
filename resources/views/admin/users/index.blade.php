@extends('admin.layouts.app')

@section('title', 'Kelola Pengguna')

@section('content')
<style>
    /* Card & layout */
    .container-users { margin-top: 24px; }
    .card { background:#fff; border:1px solid rgba(0,0,0,.12); border-radius:0px; }
    .card-header { padding:.9rem 1.25rem; background:rgba(0,0,0,.03); border-bottom:1px solid rgba(0,0,0,.12); }
    .card-body { padding:1.25rem; }
    .d-flex{display:flex} .justify-content-between{justify-content:space-between} .align-items-center{align-items:center}

    /* Button utama (Tambah) */
    .btn{display:inline-block; font-size:0.95rem; line-height:1.4; border-radius:.5rem; text-decoration:none; cursor:pointer; border:none}
    .btn-success{background:#28a745; color:#fff; padding:.45rem .8rem} .btn-success:hover{background:#218838}

    /* Tabel hijau + zebra abu (match Daftar Siswa) */
    .table-wrap{overflow-x:auto}
    .table{width:100%; border-collapse:separate; border-spacing:0; border-radius:0px; overflow:hidden; /* rounded inner */}
    .table th, .table td{padding:12px 14px; border:1px solid #e5e7eb; text-align:left}
    .table thead th{background:#43a047; color:#fff; font-weight:700; border-color:#3c8f42}
    .table tbody tr:nth-child(odd){background:#ffffff}
    .table tbody tr:nth-child(even){background:#f0f0f0}
    .table tbody tr:hover{background:#e8f5e9}

    /* Kolom aksi */
    .col-actions{width:170px; white-space:nowrap; text-align:left}

    /* Tombol aksi tipis, flat (seperti di halaman siswa) */
    .btn-action{display:inline-block; font-size:.85rem; font-weight:600; padding:4px 12px; border:none; border-radius:8px; cursor:pointer; text-decoration:none; line-height:1.2}
    .btn-edit{background:#ffc107; color:#fff} .btn-edit:hover{background:#e0a800}
    .btn-delete{background:#dc3545; color:#fff} .btn-delete:hover{background:#bd2130}
    .d-inline{display:inline-block}

    /* Alert sukses */
    .alert-success{background:#d4edda; color:#155724; border:1px solid #c3e6cb; padding:.7rem .9rem; border-radius:.5rem; margin-bottom:1rem}
</style>

<div class="container container-users">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 style="margin:0; color:#43a047;">Kelola Pengguna</h3>
                <a href="{{ route('admin.users.create') }}" class="btn btn-success">
                    Tambah Pengguna Baru
                </a>
            </div>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif

            @if($users->isEmpty())
                <p class="text-center" style="margin:0.5rem 0;">Belum ada data pengguna.</p>
            @else
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width:70px">ID</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Peran (Role)</th>
                                <th class="col-actions">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $u)
                                <tr>
                                    <td>{{ $u->id }}</td>
                                    <td>{{ $u->name }}</td>
                                    <td>{{ $u->email }}</td>
                                    <td>{{ ucfirst($u->role) }}</td>
                                    <td>
                                        <a href="{{ route('admin.users.edit', $u->id) }}" class="btn-action btn-edit">Edit</a>
                                        <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('Hapus pengguna ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-action btn-delete">Hapus</button>
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

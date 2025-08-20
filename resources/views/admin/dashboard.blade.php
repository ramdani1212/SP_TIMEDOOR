@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<style>
/* ==== SCOPE khusus halaman ini ==== */
.admin-schedule .dashboard-card{
    max-width:1100px; margin:24px auto; background:#fff; border:1px solid rgba(0,0,0,.12);
    border-radius:0px; box-shadow:0 10px 24px rgba(0,0,0,.08);
}
.admin-schedule .card-header{
    padding:.9rem 1.25rem; background:rgba(0,0,0,.03); border-bottom:1px solid rgba(0,0,0,.12);
}
.admin-schedule .header-bar{display:flex; align-items:center; justify-content:space-between; gap:12px}
.admin-schedule .title{margin:0; color:#43a047}

.admin-schedule .btn-create{
    display:inline-block; background:#28a745; color:#fff; padding:.45rem .85rem; border:none;
    border-radius:.55rem; text-decoration:none; font-weight:600; cursor:pointer;
}
.admin-schedule .btn-create:hover{background:#218838}

.admin-schedule .card-body{padding:1.25rem}
.admin-schedule .welcome{margin:0 0 8px; color:#5f6368}
.admin-schedule .centered-title h2{margin:6px 0 12px; text-align:center; color:#43a047}

.admin-schedule .alert-success{
    background:#d4edda; color:#155724; border:1px solid #c3e6cb; padding:.7rem .9rem;
    border-radius:.6rem; text-align:center; margin-bottom:14px;
}

/* ==== Tabel: header hijau, zebra abu, hover hijau muda ==== */
.admin-schedule .table-responsive{overflow-x:auto}
.admin-schedule table{width:100%; border-collapse:separate; border-spacing:0}
.admin-schedule thead th{
    background:#43a047; color:#fff; font-weight:700; padding:12px 14px;
    border-right:1px solid #3c8f42;
}
.admin-schedule thead th:last-child{border-right:0}
.admin-schedule tbody td{padding:12px 14px; border-bottom:1px solid #e6efe8; vertical-align:top}
.admin-schedule tbody tr:nth-child(odd){background:#fff}
.admin-schedule tbody tr:nth-child(even){background:#f0f0f0}
.admin-schedule tbody tr:hover{background:#e8f5e9}
.admin-schedule th.aksi-header, .admin-schedule td.aksi-cell{text-align:center}

/* ==== Tombol aksi: flat & tipis ==== */
.admin-schedule .action-buttons-container{display:flex; gap:8px; justify-content:center; align-items:center}
.admin-schedule .action-button{
    display:inline-block; font-size:.85rem; font-weight:600; padding:4px 12px; line-height:1.2;
    border:none; border-radius:8px; cursor:pointer; text-decoration:none;
}
.admin-schedule .edit-button{background:#ffc107; color:#fff}
.admin-schedule .edit-button:hover{background:#e0a800}
.admin-schedule .delete-button{background:#dc3545; color:#fff}
.admin-schedule .delete-button:hover{background:#bd2130}
.admin-schedule .action-buttons-container > form{display:contents}

/* ==== Badge status ==== */
.admin-schedule .badge{display:inline-block; padding:4px 10px; border-radius:999px; font-size:.78rem; font-weight:600}
.admin-schedule .badge-pending{background:#fff3cd; color:#8a6d3b; border:1px solid #ffe08a}
.admin-schedule .badge-approved{background:#d1fae5; color:#065f46; border:1px solid #a7f3d0}
.admin-schedule .badge-completed{background:#e0e7ff; color:#3730a3; border:1px solid #c7d2fe}
.admin-schedule .badge-revision{background:#fde68a; color:#92400e; border:1px solid #fcd34d}
.admin-schedule .badge-cancelled{background:#fee2e2; color:#991b1b; border:1px solid #fecaca}
.admin-schedule .badge-default{background:#f3f4f6; color:#374151; border:1px solid #e5e7eb}

/* teks catatan revisi */
.admin-schedule .revision-note{font-style:italic; color:#6b7280}
</style>

<div class="admin-schedule">
    <div class="dashboard-card">
        <div class="card-header">
            <div class="header-bar">
                <h3 class="title">Dashboard Admin</h3>
                <a href="{{ route('admin.schedules.create') }}" class="btn-create">Buat Jadwal Baru</a>
            </div>
        </div>

        <div class="card-body">

            <div class="centered-title">
                <h2>Daftar Semua Jadwal</h2>
            </div>

            @if(session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif

            @if($schedules->isEmpty())
                <p style="text-align:center;">Tidak ada jadwal yang tersedia.</p>
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
                                @php
                                    $statusClass = match($schedule->status){
                                        'pending'   => 'badge-pending',
                                        'approved'  => 'badge-approved',
                                        'completed' => 'badge-completed',
                                        'revision'  => 'badge-revision',
                                        'cancelled' => 'badge-cancelled',
                                        default     => 'badge-default',
                                    };
                                @endphp
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
                                    <td><span class="badge {{ $statusClass }}">{{ ucfirst($schedule->status) }}</span></td>
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
                                            <form action="{{ route('admin.schedules.destroy', $schedule) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="action-button delete-button"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?');">
                                                    Hapus
                                                </button>
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
    </div>
</div>
@endsection

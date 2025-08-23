@extends('admin.layouts.app')

@section('title', 'Riwayat Notifikasi Admin')

@section('content')
<style>
    /* Mengatur kontainer utama */
    .container {
        padding-top: 30px;
        padding-bottom: 30px;
    }

    /* Mengatur kartu utama */
    .card {
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .card-header {
        background-color: #4CAF50; /* Warna hijau, konsisten dengan dashboard */
        color: white;
        font-weight: bold;
        font-size: 1.25rem;
        /* Diperbaiki: Mengurangi padding vertikal untuk membuat header lebih pendek */
        padding: 0.75rem 1.25rem; 
        border-bottom: none;
    }

    /* Mengatur daftar notifikasi */
    .table-responsive {
        overflow-x: auto;
    }
    
    .table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .table thead th {
        background-color: #4CAF50;
        color: white;
        padding: 12px 15px;
        text-align: left;
    }
    
    .table tbody tr:nth-child(odd) {
        background-color: #f9f9f9;
    }
    
    .table tbody tr:hover {
        background-color: #f1f1f1;
    }
    
    .table tbody td {
        padding: 12px 15px;
        border-bottom: 1px solid #ddd;
        vertical-align: top;
    }

    /* Mengatur pesan notifikasi */
    .alert {
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 0;
        font-size: 1rem;
    }

    .alert-warning {
        background-color: #fff3e0;
        border-color: #ffe0b2;
        color: #ff9800;
    }

    .alert-info {
        background-color: #e3f2fd;
        border-color: #bbdefb;
        color: #2196F3;
    }

    /* Mengatur teks jika tidak ada notifikasi */
    .text-center {
        color: #777;
        padding: 40px 0;
    }

    /* Styling pagination */
    .pagination .page-item .page-link {
        color: #4CAF50;
    }

    .pagination .page-item.active .page-link {
        background-color: #4CAF50;
        border-color: #4CAF50;
    }
</style>

<div class="container">
    <div class="card">
        <div class="card-header">
            Riwayat Notifikasi
        </div>
        <div class="card-body">
            @if($notifications->isEmpty())
                <p class="text-center">Tidak ada notifikasi yang tersedia.</p>
            @else
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Pengirim</th>
                                <th>Pesan</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($notifications as $notification)
                                <tr>
                                    <td>{{ $notification->data['teacher']['name'] ?? 'Admin' }}</td>
                                    <td>
                                        @if(isset($notification->data['schedule']))
                                            <div class="alert alert-warning" role="alert">
                                                <strong>Notifikasi Revisi:</strong> Jadwal Kelas <strong>{{ $notification->data['schedule']['jenis_kelas'] }}</strong> membutuhkan revisi.
                                                <br>
                                                <strong>Catatan:</strong> {{ $notification->data['revision_note'] ?? 'Tidak ada catatan.' }}
                                            </div>
                                        @elseif(isset($notification->data['note_to_admin']))
                                            <div class="alert alert-info" role="alert">
                                                <strong>Catatan Umum:</strong>
                                                <br>
                                                <strong>Pesan:</strong> {{ $notification->data['note_to_admin'] }}
                                            </div>
                                        @else
                                            <div class="alert alert-info" role="alert">
                                                Notifikasi baru.
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
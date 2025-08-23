@extends('teacher.layouts.app')

@section('title', 'Riwayat Notifikasi')

@section('content')
<style>
    /* Mengatur kontainer utama */
    .dashboard-container {
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
        padding: 0.75rem 1.25rem;
        border-bottom: none;
    }

    /* Mengatur daftar notifikasi */
    .list-group-item {
        border: none;
        border-bottom: 1px solid #e0e0e0;
        padding: 20px 25px;
    }

    .list-group-item:last-child {
        border-bottom: none;
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

    /* Mengatur tautan dan waktu */
    .text-muted {
        font-size: 0.85rem;
        margin-top: 10px;
        display: block;
    }

    .float-right {
        float: right;
    }

    /* Mengatur teks jika tidak ada notifikasi */
    .text-center {
        color: #777;
        padding: 40px 0;
    }

    /* Menghilangkan style default tabel dan menggantinya dengan list */
    table {
        display: none; /* Menyembunyikan tabel yang lama */
    }
</style>
<div class="dashboard-container">
    <div class="card">
        <div class="card-header">
            Riwayat Notifikasi
        </div>
        <div class="card-body">
            @if($notifications->isEmpty())
                <p class="text-center">Tidak ada notifikasi yang tersedia.</p>
            @else
                <ul class="list-group list-group-flush">
                    @foreach($notifications as $notification)
                        <li class="list-group-item">
                            {{-- Memeriksa tipe notifikasi untuk menampilkan pesan yang sesuai --}}
                            @if(isset($notification->data['revision_note']))
                                <div class="alert alert-warning" role="alert">
                                    <strong>Pemberitahuan Revisi:</strong> Jadwal Anda telah direvisi oleh admin.
                                    <br>
                                    <strong>Catatan Admin:</strong> {{ $notification->data['revision_note'] }}
                                </div>
                            @elseif(isset($notification->data['message']))
                                <div class="alert alert-info" role="alert">
                                    <strong>Pesan Umum:</strong>
                                    <br>
                                    {{ $notification->data['message'] }}
                                </div>
                            @else
                                <div class="alert alert-info" role="alert">
                                    <strong>Pesan Baru:</strong> Silakan periksa detailnya.
                                </div>
                            @endif
                            <small class="text-muted float-right">{{ $notification->created_at->diffForHumans() }}</small>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
@endsection
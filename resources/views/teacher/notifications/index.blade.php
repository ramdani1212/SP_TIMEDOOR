@extends('admin.layouts.app')
@section('title','Notifikasi')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>

<style>
/* ===== Kontainer & Layout Utama ===== */
.container {
    padding-top: 30px;
    padding-bottom: 30px;
}

.card {
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

/* Mengatur ukuran backdrop hijau untuk judul */
.card-header {
    background-color: #4CAF50;
    color: white;
    font-weight: bold;
    font-size: 1rem;
    padding: 0.25rem 0.75rem; /* UKURAN DIUBAH MENJADI LEBIH KECIL */
    border-bottom: none;
}

.card-body {
    padding: 1rem;
    background-color: #f7fff7; /* Backdrop hijau muda untuk konten */
}

/* ===== Notifikasi Boxes (Card) ===== */
.notification-box {
    background-color: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 1.25rem;
    margin-bottom: 1rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.notification-box:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
}

.notification-title {
    font-weight: 700;
    color: #333;
    display: flex;
    align-items: center;
    font-size: 1.1rem;
}

/* Ikon notifikasi */
.notification-icon {
    margin-right: 10px;
    color: #4CAF50;
}

.notification-message {
    margin-top: 0.5rem;
    color: #555;
}

.notification-extra-info {
    margin-top: 1rem;
    font-size: 0.9rem;
    color: #666;
    background-color: #F8F9FA;
    border-left: 3px solid #4CAF50;
    padding: 10px;
    border-radius: 4px;
}

.notification-timestamp {
    font-size: 0.8rem;
    color: #999;
    margin-top: 0.5rem;
}

.notification-link {
    display: inline-block;
    margin-top: 10px;
    font-weight: 600;
    color: #007bff;
    text-decoration: none;
}

/* ===== Tombol & Alerts ===== */
.btn-read {
    padding: 0.4rem 0.8rem;
    font-size: 0.9rem;
    border-radius: 50px;
    font-weight: 600;
    transition: background-color 0.3s, color 0.3s, transform 0.2s;
}

.btn-read:hover {
    transform: translateY(-1px);
}

.alert-empty {
    background-color: #e9ecef;
    color: #6c757d;
    border: 1px solid #ced4da;
    padding: 1rem;
    border-radius: 0.5rem;
    text-align: center;
}

.pagination-container {
    display: flex;
    justify-content: center;
    margin-top: 2rem;
}

.card-header h2 {
    font-size: 1rem;       /* perkecil ukuran teks judul */
    margin: 0;  
    color: white;
}
</style>

<div class="container">
    {{-- Flash message --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-header">
            <h2>Notifikasi (Teacher)</h2>
        </div>

        <div class="card-body">
            {{-- UNREAD --}}
            <h5 class="mt-2">Belum Dibaca</h5>
            @forelse($unread as $n)
                @php
                    $data = is_array($n->data) ? $n->data : [];
                    $title = $data['title'] ?? 'Notifikasi';
                    $message = $data['message'] ?? '';
                    $url = $data['url'] ?? null;
                @endphp

                <div class="notification-box d-flex justify-content-between align-items-start">
                    <div>
                        <div class="notification-title">
                            <i class="fas fa-bell notification-icon"></i> {{ $title }}
                        </div>
                        @if($message !== '')
                            <div class="notification-message">{{ $message }}</div>
                        @endif

                        {{-- extra info opsional (misal data jadwal) --}}
                        @if(!empty($data['schedule']) && is_array($data['schedule']))
                            <div class="notification-extra-info">
                                @php $sch = $data['schedule']; @endphp
                                @if(!empty($sch['date']))<div><strong>Tanggal:</strong> {{ $sch['date'] }}</div>@endif
                                @if(!empty($sch['start_time']) || !empty($sch['end_time']))
                                    <div><strong>Waktu:</strong> {{ $sch['start_time'] ?? '-' }} - {{ $sch['end_time'] ?? '-' }}</div>
                                @endif
                                @if(!empty($sch['jenis']))<div><strong>Jenis:</strong> {{ $sch['jenis'] }}</div>@endif
                                @if(!empty($sch['status']))<div><strong>Status:</strong> {{ ucfirst($sch['status']) }}</div>@endif
                            </div>
                        @endif

                        @if(!empty($url))
                            <div class="notification-link">
                                <a href="{{ $url }}">Buka</a>
                            </div>
                        @endif

                        <div class="notification-timestamp">{{ $n->created_at->diffForHumans() }}</div>
                    </div>

                    <form action="{{ route('teacher.notifications.markAsRead',$n->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <button class="btn btn-sm btn-outline-primary btn-read">
                            <i class="fas fa-check"></i> Tandai dibaca
                        </button>
                    </form>
                </div>
            @empty
                <div class="alert-empty">Tidak ada notifikasi baru.</div>
            @endforelse

            {{-- ALL --}}
            <h5 class="mt-4">Semua Notifikasi</h5>
            @forelse($all as $n)
                @php
                    $data = is_array($n->data) ? $n->data : [];
                    $title = $data['title'] ?? 'Notifikasi';
                    $message = $data['message'] ?? '';
                    $url = $data['url'] ?? null;
                @endphp

                <div class="notification-box">
                    <div class="notification-title">
                        <i class="far fa-bell notification-icon"></i> {{ $title }}
                    </div>
                    @if($message !== '')
                        <div class="notification-message">{{ $message }}</div>
                    @endif

                    @if(!empty($data['schedule']) && is_array($data['schedule']))
                        <div class="notification-extra-info">
                            @php $sch = $data['schedule']; @endphp
                            @if(!empty($sch['date']))<div><strong>Tanggal:</strong> {{ $sch['date'] }}</div>@endif
                            @if(!empty($sch['start_time']) || !empty($sch['end_time']))
                                <div><strong>Waktu:</strong> {{ $sch['start_time'] ?? '-' }} - {{ $sch['end_time'] ?? '-' }}</div>
                            @endif
                            @if(!empty($sch['jenis']))<div><strong>Jenis:</strong> {{ $sch['jenis'] }}</div>@endif
                            @if(!empty($sch['status']))<div><strong>Status:</strong> {{ ucfirst($sch['status']) }}</div>@endif
                        </div>
                    @endif

                    @if(!empty($url))
                        <div class="notification-link">
                            <a href="{{ $url }}">Buka</a>
                        </div>
                    @endif

                    <div class="notification-timestamp">{{ $n->created_at->format('d M Y H:i') }}</div>
                </div>
            @empty
                <div class="alert-empty">Belum ada riwayat notifikasi.</div>
            @endforelse

            <div class="mt-3 pagination-container">
                {{ $all->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
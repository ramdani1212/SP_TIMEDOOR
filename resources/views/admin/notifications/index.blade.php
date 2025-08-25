@extends('admin.layouts.app')
@section('title','Notifikasi')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>

<style>
.container{padding-top:30px;padding-bottom:30px;margin-left:20px}
.card{margin-top:20px}
.card-header{background:#4CAF50;color:#fff;font-weight:bold;font-size:1rem;padding:.25rem .75rem;border-bottom:none}
.card-body{padding:.5rem;background:#f7fff7}

.notification-box{background:#fff;border:1px solid #e0e0e0;border-radius:8px;padding:1.25rem;margin-bottom:1rem;box-shadow:0 2px 4px rgba(0,0,0,.05);transition:transform .2s,box-shadow .2s}
.notification-box:hover{transform:translateY(-3px);box-shadow:0 8px 16px rgba(0,0,0,.1)}
.notification-title{font-weight:700;color:#333;display:flex;align-items:center;font-size:1.1rem}
.notification-icon{margin-right:10px;color:#4CAF50}
.notification-message{margin-top:.5rem;color:#555}
.notification-extra-info{margin-top:1rem;font-size:.9rem;color:#666;background:#F8F9FA;border-left:3px solid #4CAF50;padding:10px;border-radius:4px}
.notification-timestamp{font-size:.8rem;color:#999;margin-top:.5rem}

.btn-read{padding:.4rem .8rem;font-size:.9rem;border-radius:50px;font-weight:600;transition:.3s}
.btn-bar{display:flex;gap:.5rem;align-items:center;margin-left:1rem}
.btn-danger{border-radius:50px}

.alert-empty{background:#e9ecef;color:#6c757d;border:1px solid #ced4da;padding:1rem;border-radius:.5rem;text-align:center}
.pagination-container{display:flex;justify-content:center;margin-top:2rem}
.card-header h2{font-size:1rem;margin:0;color:#fff}

.btn-danger {
    background-color: #e53935;   /* merah solid */
    color: #fff;
    border: none;
    border-radius: 50px;
    font-weight: 600;
    padding: 6px 14px;
    font-size: 0.9rem;
    transition: background-color 0.3s, transform 0.2s;
}
.btn-danger:hover {
    background-color: #c62828;  /* merah lebih gelap */
    transform: translateY(-1px);
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
            <h2>Notifikasi (Admin)</h2>
        </div>

        <div class="card-body">
            {{-- UNREAD --}}
            <h5 class="mt-2">Belum Dibaca</h5>
            @forelse($unread as $n)
                @php
                    $data = is_array($n->data) ? $n->data : [];
                    $title = $data['title'] ?? 'Notifikasi';
                    $message = $data['message'] ?? '';
                @endphp

                <div class="notification-box d-flex justify-content-between align-items-start">
                    <div>
                        <div class="notification-title">
                            <i class="fas fa-bell notification-icon"></i> {{ $title }}
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

                        <div class="notification-timestamp">{{ $n->created_at->diffForHumans() }}</div>
                    </div>

                    <div class="d-flex align-items-start btn-bar">
                        {{-- Tandai dibaca --}}
                        <form action="{{ route('admin.notifications.markAsRead',$n->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <button class="btn btn-sm btn-outline-primary btn-read">
                                <i class="fas fa-check"></i> Tandai dibaca
                            </button>
                        </form>

                        {{-- Hapus --}}
                        <form action="{{ route('admin.notifications.destroy',$n->id) }}" method="POST" onsubmit="return confirm('Hapus notifikasi ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">
                                <i class="fas fa-trash-alt"></i> Hapus
                            </button>
                        </form>
                    </div>
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
                @endphp

                <div class="notification-box d-flex justify-content-between align-items-start">
                    <div>
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

                        <div class="notification-timestamp">{{ $n->created_at->format('d M Y H:i') }}</div>
                    </div>

                    {{-- Hapus --}}
                    <form action="{{ route('admin.notifications.destroy',$n->id) }}" method="POST" onsubmit="return confirm('Hapus notifikasi ini?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">
                            <i class="fas fa-trash-alt"></i> Hapus
                        </button>
                    </form>
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

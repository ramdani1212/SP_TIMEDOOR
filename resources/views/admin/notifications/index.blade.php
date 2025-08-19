@extends('admin.layouts.app')

@section('title', 'Riwayat Notifikasi Admin')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            Riwayat Notifikasi
        </div>
        <div class="card-body">
            @if($notifications->isEmpty())
                <p class="text-center">Tidak ada notifikasi yang tersedia.</p>
            @else
                <ul class="list-group">
                    @foreach($notifications as $notification)
                        <li class="list-group-item">
                            @if($notification->data['type'] == 'revision')
                                <div class="alert alert-warning" role="alert">
                                    <strong>Notifikasi Revisi:</strong> Jadwal
                                    <strong>{{ $notification->data['class'] }}</strong> dari
                                    <strong>{{ $notification->data['teacher_name'] }}</strong> membutuhkan revisi.
                                    <br>
                                    <strong>Catatan:</strong> {{ $notification->data['note'] }}
                                </div>
                            @elseif($notification->data['type'] == 'general_note')
                                <div class="alert alert-info" role="alert">
                                    <strong>Catatan Umum:</strong> Pesan dari
                                    <strong>{{ $notification->data['teacher_name'] }}</strong>
                                    <br>
                                    <strong>Catatan:</strong> {{ $notification->data['note'] }}
                                </div>
                            @endif
                            <small class="text-muted float-right">{{ $notification->created_at->diffForHumans() }}</small>
                        </li>
                    @endforeach
                </ul>
                <div class="mt-3">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
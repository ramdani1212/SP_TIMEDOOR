@extends('teacher.layouts.app')

@section('title', 'Riwayat Notifikasi')

@section('content')
<div class="dashboard-container">
    <h2>Riwayat Notifikasi</h2>
    @if($notifications->isEmpty())
        <p>Tidak ada notifikasi yang tersedia.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Pesan Notifikasi</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                @foreach($notifications as $notification)
                <tr>
                    {{-- Akses data dari array 'data' di notifikasi --}}
                    <td>{{ $notification->data['message'] }}</td>
                    <td>{{ $notification->created_at->diffForHumans() }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
@extends('teacher.layouts.app')
@section('title','Notifikasi')

@section('content')
<div class="container py-3">
  {{-- Flash message --}}
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  <h2 class="mb-3" style="color:#4CAF50;">Notifikasi (Teacher)</h2>

  {{-- UNREAD --}}
  <h5 class="mt-2">Belum Dibaca</h5>
  @forelse($unread as $n)
    @php
      $data = is_array($n->data) ? $n->data : [];
      $title = $data['title'] ?? 'Notifikasi';
      $message = $data['message'] ?? '';
      $url = $data['url'] ?? null;
    @endphp

    <div class="border rounded p-3 mb-2 d-flex justify-content-between align-items-start" style="background:#fff;">
      <div>
        <div class="fw-bold">
          <i class="fas fa-bell"></i> {{ $title }}
        </div>
        @if($message !== '')
          <div class="mt-1">{{ $message }}</div>
        @endif

        {{-- Extra info (opsional) --}}
        @if(!empty($data['schedule']) && is_array($data['schedule']))
          <div class="mt-2" style="font-size: 0.92rem; color:#555;">
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
          <div class="mt-2">
            <a href="{{ $url }}" class="text-primary">Buka</a>
          </div>
        @endif

        <div class="text-muted small mt-1">{{ $n->created_at->diffForHumans() }}</div>
      </div>

      <form action="{{ route('teacher.notifications.markAsRead',$n->id) }}" method="POST">
        @csrf @method('PATCH')
        <button class="btn btn-sm btn-outline-primary">
          <i class="fas fa-check"></i> Tandai dibaca
        </button>
      </form>
    </div>
  @empty
    <div class="alert alert-secondary">Tidak ada notifikasi baru.</div>
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

    <div class="border rounded p-3 mb-2" style="background:#fff;">
      <div class="fw-bold">
        <i class="far fa-bell"></i> {{ $title }}
      </div>
      @if($message !== '')
        <div class="mt-1">{{ $message }}</div>
      @endif

      {{-- Extra info (opsional) --}}
      @if(!empty($data['schedule']) && is_array($data['schedule']))
        <div class="mt-2" style="font-size: 0.92rem; color:#555;">
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
        <div class="mt-2">
          <a href="{{ $url }}" class="text-primary">Buka</a>
        </div>
      @endif

      <div class="text-muted small mt-1">{{ $n->created_at->format('d M Y H:i') }}</div>
    </div>
  @empty
    <div class="alert alert-secondary">Belum ada riwayat notifikasi.</div>
  @endforelse

  <div class="mt-3">
    {{ $all->links() }}
  </div>
</div>
@endsection

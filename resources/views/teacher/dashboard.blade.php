@extends('teacher.layouts.app')

@section('title', 'Teacher Dashboard')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>

<style>
/* ===== Page/Card (selaras dgn admin) ===== */
.page-wrap{max-width:1100px;margin:24px auto}
.card{background:#fff;border-radius:0;box-shadow:0 10px 28px rgba(0,0,0,.08);overflow:hidden}
.card-header{display:flex;align-items:center;justify-content:space-between;padding:16px 18px;background:#f6f7f8;border-bottom:1px solid #e9ecef}
.card-header h2{margin:0;color:#4CAF50;font-size:22px;font-weight:600}
.card-body{padding:18px}

/* ===== Alerts ===== */
.alert-success{background:#e9f9ef;border:1px solid #c8efd6;color:#1e7a3b;border-radius:8px;padding:10px 12px;margin-bottom:16px}

/* ===== Table ===== */
.table-responsive{width:100%;overflow-x:auto}
table{width:100%;border-collapse:collapse;table-layout:auto}
th,td{padding:12px 14px;border:1px solid #ddd;text-align:left;vertical-align:middle}
thead th{background:#4CAF50;color:#fff;font-weight:700}
tbody tr:nth-child(even){background:#f7f7f7}
tbody tr:hover{background:#f1f8f4}

/* width kolom */
.col-guru{width:140px}
.col-siswa{width:180px}
.col-tanggal{width:120px}
.col-waktu{width:120px}
.col-status{width:110px}
.col-revisi{width:220px}
.col-jenis{width:110px}
.col-aksi{width:170px}
.col-notif{width:80px;text-align:center}

/* ===== Badges ===== */
.badge{
  display:inline-block;
  padding:6px 10px;
  border-radius:999px;
  font-size:12px;
  font-weight:600;
  text-transform:capitalize;
}
.badge.pending{
  background:#fff7e6;   /* krem */
  color:#ad6b00;        /* oranye tua */
  border:1px solid #ffd699;
}
.badge.revision{
  background:#fff3cd;
  color:#856404;
  border:1px solid #ffeeba;
}
.badge.approved{
  background:#e9f9ef;
  color:#1e7a3b;
  border:1px solid #c8efd6;
}
.badge.cancelled{
  background:#ffe8e8;
  color:#a12020;
  border:1px solid #ffc9c9;
}

/* ===== Buttons ===== */
.btn{display:inline-flex;justify-content:center;align-items:center;padding:6px 12px;height:32px;border-radius:6px;border:none;font-weight:600;font-size:13px;cursor:pointer;text-decoration:none;white-space:nowrap}
.btn-success{background:#28a745;color:#fff}.btn-success:hover{background:#218838}
.btn-danger{background:#dc3545;color:#fff}.btn-danger:hover{background:#c82333}
.btn-bell{background:#0d6efd;color:#fff;width:36px;height:32px;padding:0}

.actions{display:flex;gap:8px;justify-content:center;align-items:center;flex-wrap:nowrap}

/* ===== Chip catatan revisi ===== */
.note-chip{background:#fff3cd;border:1px solid #ffe8a1;border-radius:8px;padding:6px 10px;display:inline-block;max-width:100%}

/* ===== Modal ===== */
.backdrop{display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:1000}
.modal{background:#fff;width:92%;max-width:620px;margin:6% auto;border-radius:10px;box-shadow:0 10px 28px rgba(0,0,0,.2);overflow:hidden}
.modal-head{padding:14px 16px;border-bottom:1px solid #e9ecef;display:flex;justify-content:space-between;align-items:center}
.modal-head h3{margin:0;font-size:18px;color:#4CAF50;font-weight:600}
.modal-close{border:none;background:transparent;font-size:22px;line-height:1;color:#888;cursor:pointer}
.modal-body{padding:16px}
.modal-body textarea{width:95%;min-height:80px;resize:vertical;border:2px solid #66BB6A;border-radius:8px;background:#f9f9f9;padding:12px;font-size:14px}
.modal-foot{padding:16px;border-top:1px solid #e9ecef;text-align:right}
</style>

<div class="page-wrap">
  <div class="card">
    <div class="card-header">
      <h2>Dashboard Guru</h2>
    </div>

    <div class="card-body">
      @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
      @endif

      @if(!isset($schedules) || $schedules->isEmpty())
        <p style="text-align:center;color:#666;">Tidak ada jadwal yang ditugaskan.</p>
      @else
        <div class="table-responsive">
          <table>
            <thead>
              <tr>
                <th class="col-guru">Guru</th>
                <th class="col-siswa">Siswa</th>
                <th class="col-tanggal">Tanggal</th>
                <th class="col-waktu">Waktu</th>
                <th class="col-status">Status</th>
                <th class="col-revisi">Catatan Revisi</th>
                <th class="col-jenis">Jenis Kelas</th>
                <th class="col-aksi">Aksi</th>
                <th class="col-notif">Notifikasi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($schedules as $schedule)
                <tr>
                  <td>{{ $schedule->teacher->name ?? '-' }}</td>
                  <td>
                    @php
                      $names = $schedule->students?->pluck('nama') ?? collect([$schedule->student->nama ?? $schedule->student->name ?? null]);
                      $names = $names->filter()->values();
                    @endphp
                    {{ $names->isNotEmpty() ? $names->join(', ') : '-' }}
                  </td>
                  <td>{{ $schedule->schedule_date }}</td>
                  <td>{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</td>
                  <td><span class="badge {{ $schedule->status }}">{{ $schedule->status }}</span></td>
                  <td>
                    @if($schedule->status === 'revision' && $schedule->revision_note)
                      <span class="note-chip">{{ $schedule->revision_note }}</span>
                    @else
                      -
                    @endif
                  </td>
                  <td>{{ $schedule->jenis_kelas }}</td>
                  <td>
                    <div class="actions">
                      @if($schedule->status === 'pending')
                        <form action="{{ route('teacher.schedules.approve', $schedule) }}" method="POST">
                          @csrf
                          <button type="submit" class="btn btn-success">Setuju</button>
                        </form>
                        <button type="button" class="btn btn-danger" onclick="openRevisionModal({{ $schedule->id }})">Revisi</button>
                      @elseif($schedule->status === 'approved')
                        <span class="badge approved">Sudah disetujui</span>
                      @elseif($schedule->status === 'revision')
                        <span class="badge revision">Perlu revisi</span>
                      @else
                        -
                      @endif
                    </div>
                  </td>
                  <td>
                    <button type="button" class="btn btn-bell" onclick="openChatModal({{ $schedule->id }})" title="Kirim pesan ke Admin">
                      <i class="fa-solid fa-bell"></i>
                    </button>
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

{{-- Modal Revisi --}}
<div id="revBackdrop" class="backdrop">
  <div class="modal">
    <div class="modal-head">
      <h3>Catatan Revisi</h3>
      <button class="modal-close" onclick="closeRevisionModal()">&times;</button>
    </div>
    <form id="revForm" method="POST">
      @csrf
      <div class="modal-body">
        <textarea name="revision_note" placeholder="Tulis catatan revisi..." required></textarea>
      </div>
      <div class="modal-foot">
        <button type="submit" class="btn btn-success">Kirim Revisi</button>
      </div>
    </form>
  </div>
</div>

{{-- Modal Chat --}}
<div id="chatBackdrop" class="backdrop">
  <div class="modal">
    <div class="modal-head">
      <h3>Kirim Pesan ke Admin</h3>
      <button class="modal-close" onclick="closeChatModal()">&times;</button>
    </div>
    <form id="chatForm" method="POST" action="{{ route('teacher.send-note') }}">
      @csrf
      <input type="hidden" name="schedule_id" id="chatScheduleId">
      <div class="modal-body">
        <textarea name="note_to_admin" placeholder="Tulis pesan Anda..." required></textarea>
      </div>
      <div class="modal-foot">
        <button type="submit" class="btn btn-success">Kirim Pesan</button>
      </div>
    </form>
  </div>
</div>

<script>
function openRevisionModal(id){
  const form = document.getElementById('revForm');
  form.action = "{{ url('/teacher/schedules') }}/"+id+"/revision";
  document.getElementById('revBackdrop').style.display = 'block';
}
function closeRevisionModal(){ document.getElementById('revBackdrop').style.display = 'none'; }

function openChatModal(id){
  document.getElementById('chatScheduleId').value = id;
  document.getElementById('chatBackdrop').style.display = 'block';
}
function closeChatModal(){ document.getElementById('chatBackdrop').style.display = 'none'; }

window.addEventListener('click', function(e){
  if(e.target === document.getElementById('revBackdrop')) closeRevisionModal();
  if(e.target === document.getElementById('chatBackdrop')) closeChatModal();
});
</script>
@endsection

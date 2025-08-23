@extends('teacher.layouts.app')

@section('title', 'Teacher Dashboard')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>

<style>
/* --- Layout dasar --- */
.dashboard-container{padding:20px;background:#fff;border-radius:12px;box-shadow:0 6px 16px rgba(0,0,0,.06)}
h1,h2{color:#66BB6A;text-align:center}
h2{margin-top:16px}
.success-message{background:#d4edda;color:#155724;border:1px solid #c3e6cb;padding:10px;margin-bottom:16px;border-radius:6px;text-align:center}

/* --- Tabel hijau konsisten --- */
.table-wrap{overflow-x:auto;border-radius:10px;box-shadow:0 6px 16px rgba(0,0,0,.06);margin-top:16px}
table{width:100%;border-collapse:separate;border-spacing:0}
thead th{background:#43a047;color:#fff;font-weight:700;border-right:1px solid #3c8f42;padding:12px}
thead th:last-child{border-right:0}
tbody td{padding:12px;border-bottom:1px solid #e6efe8}
tbody tr:nth-child(odd){background:#fff}
tbody tr:nth-child(even){background:#f0f0f0}
tbody tr:hover{background:#e8f5e9}
th.aksi-header,td.aksi-cell,th.notification-header,td.notification-cell{text-align:center}

/* --- Tombol aksi kecil, flat --- */
.action-buttons-container{display:flex;gap:8px;justify-content:center;align-items:center;flex-wrap:nowrap}
.action-button{display:inline-flex;justify-content:center;align-items:center;padding:0;border:none;border-radius:6px;color:#fff;cursor:pointer;text-align:center;width:90px;height:30px;font-size:14px;line-height:1;text-decoration:none}
.approve-button{background:#4CAF50}.approve-button:hover{background:#45a049}
.revise-button{background:#dc3545}.revise-button:hover{background:#c82333}
.notify-button{background:#007bff;width:36px;height:36px}.notify-button:hover{background:#0056b3}
.approved-text{text-align:center;font-style:italic;color:#5cb85c}

/* --- Modal (revisi & chat) --- */
.modal-toggle{display:none}
.modal,.modal-chat{display:none;position:fixed;z-index:1000;left:0;top:0;width:100%;height:100%;overflow:auto;background:rgba(0,0,0,.4);padding-top:60px}
.modal-toggle:checked ~ .modal{display:block}
.modal-content,.modal-chat-content{background:#fff;margin:5% auto;padding:20px;border:1px solid #888;width:90%;max-width:520px;border-radius:10px;position:relative}
.close-button{color:#aaa;float:right;font-size:28px;font-weight:700;cursor:pointer}.close-button:hover{color:#000}
.modal-content textarea,.modal-chat-content textarea{width:100%;height:120px;margin-bottom:12px;box-sizing:border-box;padding:14px;border:2px solid #66BB6A;border-radius:8px;resize:vertical;font-size:16px;background:#f9f9f9}
.submit-revision-button,.submit-chat-button{background:#4CAF50;color:#fff;padding:10px 16px;border:none;border-radius:6px;cursor:pointer;font-size:16px;width:100%}
</style>

<div class="dashboard-container">
    <h1>Dashboard Guru</h1>
    <p>Selamat datang, {{ Auth::guard('teacher')->user()->name }}!</p>

    @if(session('success'))
        <div class="success-message">{{ session('success') }}</div>
    @endif

    <h2>Jadwal yang Perlu Anda Setujui</h2>

    @if(!isset($schedules) || $schedules->isEmpty())
        <p style="text-align:center;">Tidak ada jadwal yang ditugaskan kepada Anda.</p>
    @else
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Waktu</th>
                        <th>Jenis Kelas</th>
                        <th>Nama Siswa</th>
                        <th>Status</th>
                        <th>Catatan Revisi</th>
                        <th class="aksi-header">Aksi</th>
                        <th class="notification-header">Notifikasi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($schedules as $schedule)
                        <tr>
                            <td>{{ $schedule->schedule_date }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}
                                –
                                {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                            </td>
                            <td>{{ $schedule->jenis_kelas }}</td>
                            <td>
                                @if($schedule->students && $schedule->students->isNotEmpty())
                                    {{ $schedule->students->pluck('nama')->join(', ') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $schedule->status }}</td>
                            <td>{{ $schedule->revision_note ?? '-' }}</td>
                            <td class="aksi-cell">
                                @if(in_array($schedule->status, ['pending','revision']))
                                    <div class="action-buttons-container">
                                        <form action="{{ route('teacher.schedules.approve', $schedule) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="action-button approve-button">Setujui</button>
                                        </form>

                                        <label for="modal-toggle-{{ $schedule->id }}" class="action-button revise-button">
                                            Revisi
                                        </label>
                                    </div>
                                @elseif($schedule->status === 'approved')
                                    <span class="approved-text">Sudah disetujui</span>
                                @endif
                            </td>
                            <td class="notification-cell">
                                <button class="action-button notify-button open-chat-modal"
                                        data-schedule-id="{{ $schedule->id }}" title="Kirim chat ke Admin">
                                    <i class="fas fa-bell"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

{{-- MODAL REVISI (per-row) --}}
@isset($schedules)
@foreach($schedules as $schedule)
    <input type="checkbox" id="modal-toggle-{{ $schedule->id }}" class="modal-toggle">
    <div class="modal">
        <div class="modal-content">
            <label for="modal-toggle-{{ $schedule->id }}" class="close-button">&times;</label>
            <h2>Tambahkan Catatan Revisi</h2>
            <form method="POST" action="{{ route('teacher.schedules.revision', $schedule->id) }}">
                @csrf
                <textarea name="revision_note" placeholder="Tulis catatan revisi di sini..." required>{{ $schedule->revision_note ?? '' }}</textarea>
                <button type="submit" class="submit-revision-button">Kirim Revisi</button>
            </form>
        </div>
    </div>
@endforeach
@endisset

{{-- MODAL CHAT UNTUK NOTIFIKASI --}}
<div id="chatModal" class="modal-chat">
    <div class="modal-chat-content">
        <span class="close-button" onclick="closeChatModal()">&times;</span>
        <h2>Kirim Pesan ke Admin</h2>
        <p>Jadwal dipilih: <strong id="chat-schedule-id"></strong></p>
        <form id="chatForm" method="POST" action="{{ route('teacher.send-note') }}">
            @csrf
            <input type="hidden" name="schedule_id" id="chat-schedule-input">
            <textarea name="note_to_admin" placeholder="Tulis pesan Anda di sini..." required></textarea>
            <button type="submit" class="submit-chat-button">Kirim Pesan</button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const current = window.location.href;
    document.querySelectorAll('.sidebar ul li a').forEach(a => { if (a.href === current) a.classList.add('active'); });

    document.querySelectorAll('.open-chat-modal').forEach(btn => {
        btn.addEventListener('click', function () {
            openChatModal(this.getAttribute('data-schedule-id'));
        });
    });
});

const chatModal = document.getElementById('chatModal');
const chatScheduleId = document.getElementById('chat-schedule-id');
const chatScheduleInput = document.getElementById('chat-schedule-input');

function openChatModal(id){
    chatScheduleId.textContent = id;
    chatScheduleInput.value = id;
    chatModal.style.display = 'block';
}
function closeChatModal(){ chatModal.style.display = 'none'; }
window.onclick = function(e){ if(e.target === chatModal){ closeChatModal(); } }
</script>
@endsection

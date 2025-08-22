@extends('teacher.layouts.app')

@section('title', 'Teacher Dashboard')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
/* Style Anda di sini */
.modal-toggle {
    display: none;
}

.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.4);
    padding-top: 60px;
}

.modal-toggle:checked ~ .modal {
    display: block;
}

.modal-chat {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.4);
    padding-top: 60px;
}
.modal-chat-content {
    background-color: #fefefe;
    margin: 5% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    max-width: 500px;
    border-radius: 8px;
    position: relative;
}

/* --- Perubahan Utama di Sini --- */
/* Mengubah ukuran textarea agar jauh lebih besar */
.modal-chat-content textarea,
.modal-content textarea {
    width: 100%;
    height: 100px; /* Tinggi diperbesar lagi agar terlihat jelas */
    margin-bottom: 15px;
    box-sizing: border-box;
    padding: 15px;
    border: 2px solid #66BB6A; /* Border diubah agar lebih mencolok */
    border-radius: 8px;
    resize: vertical;
    font-size: 18px; /* Ukuran font diperbesar */
    line-height: 1.6;
    background-color: #f9f9f9; /* Ditambah warna latar belakang */
}
/* ------------------------------- */

.modal-content {
    background-color: #fefefe;
    margin: 5% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    max-width: 500px;
    border-radius: 8px;
    position: relative;
}

.close-button {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}
.close-button:hover {
    color: black;
}

.submit-revision-button, .submit-chat-button {
    background-color: #4CAF50;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    width: 100%;
}

.dashboard-container {
    padding: 20px;
    background-color: white;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}
h1, h2 {
    color: #66BB6A;
    text-align: center;
}
h2 {
    margin-top: 20px;
}

.success-message {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
    padding: 10px;
    margin-bottom: 20px;
    border-radius: 5px;
    text-align: center;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}
th, td {
    padding: 12px 15px;
    border: 1px solid #ddd;
    text-align: left;
}
th {
    background-color: #66BB6A;
    color: white;
    font-weight: bold;
}
tr:nth-child(even) {
    background-color: #f2f2f2;
}
tr:hover {
    background-color: #f1f1f1;
}

.action-buttons-container {
    display: flex;
    gap: 8px;
    justify-content: center;
    align-items: center;
    flex-wrap: nowrap;
}
.action-buttons-container form,
.action-buttons-container label {
    margin: 0;
}

.action-button {
    display: inline-flex;
    justify-content: center;
    align-items: center;
    padding: 0;
    margin: 0;
    border: none;
    border-radius: 6px;
    color: white;
    cursor: pointer;
    text-align: center;
    width: 90px;
    height: 30px;
    font-size: 14px;
    line-height: 1;
    text-decoration: none;
}
.approve-button {
    background-color: #4CAF50;
}
.approve-button:hover {
    background-color: #45a049;
}
.revise-button {
    background-color: #dc3545;
    display: inline-flex;
    justify-content: center;
    align-items: center;
}
.revise-button:hover {
    background-color: #c82333;
}
.notify-button {
    background-color: #007bff;
    width: 36px;
    height: 36px;
    padding: 0;
}
.notify-button:hover {
    background-color: #0056b3;
}
.approved-text {
    text-align: center;
    font-style: italic;
    color: #5cb85c;
}
th.aksi-header, td.aksi-cell, th.notification-header, td.notification-cell {
    text-align: center;
}
</style>

<div class="dashboard-container">
    <h1>Dashboard Guru</h1>
    <p>Selamat datang, {{ Auth::guard('teacher')->user()->name }}!</p>

    @if(session('success'))
        <div class="success-message">
            {{ session('success') }}
        </div>
    @endif

    <h2>Jadwal yang Perlu Anda Setujui</h2>
    
    @if($schedules->isEmpty())
        <p style="text-align: center;">Tidak ada jadwal yang ditugaskan kepada Anda.</p>
    @else
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
                    <td>{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</td>
                    <td>{{ $schedule->jenis_kelas }}</td>
                    <td>{{ $schedule->student->name ?? 'N/A' }}</td>
                    <td>{{ $schedule->status }}</td>
                    <td>{{ $schedule->revision_note ?? '-' }}</td>
                    <td class="aksi-cell">
                        @if($schedule->status == 'pending' || $schedule->status == 'revision')
                        <div class="action-buttons-container">
                            <form action="{{ route('teacher.schedules.approve', $schedule) }}" method="POST">
                                @csrf
                                <button type="submit" class="action-button approve-button">Setujui</button>
                            </form>
                            
                            <label for="modal-toggle-{{ $schedule->id }}" class="action-button revise-button">
                                Revisi
                            </label>
                        </div>
                        @elseif($schedule->status == 'approved')
                            <span class="approved-text">Sudah disetujui</span>
                        @endif
                    </td>
                    <td class="notification-cell">
                        <button class="action-button notify-button open-chat-modal" data-schedule-id="{{ $schedule->id }}" title="Kirim chat ke Admin">
                            <i class="fas fa-bell"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    {{-- MODAL REVISI --}}
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

    {{-- MODAL CHAT BARU UNTUK NOTIFIKASI --}}
    <div id="chatModal" class="modal-chat">
        <div class="modal-chat-content">
            <span class="close-button" onclick="closeChatModal()">&times;</span>
            <h2>Kirim Pesan ke Admin</h2>
            <p>Jadwal yang dipilih: <strong id="chat-schedule-id"></strong></p>
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
            const currentPath = window.location.href;
            const navLinks = document.querySelectorAll('.sidebar ul li a');
            navLinks.forEach(link => {
                if (link.href === currentPath) {
                    link.classList.add('active');
                }
            });
        });

        const chatModal = document.getElementById('chatModal');
        const chatScheduleId = document.getElementById('chat-schedule-id');
        const chatScheduleInput = document.getElementById('chat-schedule-input');
        
        function openChatModal(scheduleId) {
            chatScheduleId.textContent = scheduleId;
            chatScheduleInput.value = scheduleId;
            chatModal.style.display = 'block';
        }

        function closeChatModal() {
            chatModal.style.display = 'none';
        }

        document.querySelectorAll('.open-chat-modal').forEach(button => {
            button.addEventListener('click', function() {
                const scheduleId = this.getAttribute('data-schedule-id');
                openChatModal(scheduleId);
            });
        });

        window.onclick = function(event) {
            if (event.target == chatModal) {
                closeChatModal();
            }
        }
    </script>
@endsection
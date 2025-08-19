import './bootstrap';
document.addEventListener('DOMContentLoaded', () => {
    const adminDashboard = document.getElementById('admin-dashboard');
    const teacherDashboard = document.getElementById('teacher-dashboard');
    
    // Contoh data dummy
    const notifications = [
        { message: 'Jadwal baru dari Guru A' },
        { message: 'Jadwal baru dari Guru B' }
    ];
    
    const teacherSchedules = [
        { date: '2025-08-05', time: '09:00 - 11:00' },
        { date: '2025-08-06', time: '13:00 - 15:00' }
    ];
    
    // Fungsi untuk menampilkan notifikasi
    function renderNotifications() {
        const list = document.getElementById('notification-list');
        list.innerHTML = '';
        notifications.forEach(notif => {
            const li = document.createElement('li');
            li.className = 'notification-item';
            li.textContent = notif.message;
            list.appendChild(li);
        });
    }

    // Fungsi untuk menampilkan jadwal guru
    function renderTeacherSchedules() {
        const list = document.getElementById('teacher-schedule-list');
        list.innerHTML = '';
        teacherSchedules.forEach(schedule => {
            const div = document.createElement('div');
            div.className = 'schedule-item';
            div.innerHTML = `
                <div>
                    <strong>${schedule.date}</strong><br>
                    <span>${schedule.time}</span>
                </div>
            `;
            list.appendChild(div);
        });
    }

    // Panggil fungsi render saat halaman dimuat
    if (adminDashboard) {
        renderNotifications();
    }
    if (teacherDashboard) {
        renderTeacherSchedules();
    }

    // Contoh interaksi sederhana: beralih tampilan (hanya untuk simulasi)
    // Asumsikan tombol-tombol ini ada di halaman lain
    window.toggleDashboard = function(role) {
        if (role === 'admin') {
            adminDashboard.style.display = 'block';
            teacherDashboard.style.display = 'none';
        } else {
            adminDashboard.style.display = 'none';
            teacherDashboard.style.display = 'block';
        }
    }
});
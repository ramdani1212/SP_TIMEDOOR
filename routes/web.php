<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Teacher\Auth\LoginController as TeacherLoginController;
use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Teacher\ScheduleController as TeacherScheduleController; // ⬅️ tambah ini
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\AdminStudentController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\ScheduleController as AdminScheduleController; // ⬅️ alias biar tidak konflik
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Rute login umum
Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

/* =========================
|        ADMIN ROUTES
========================= */
Route::prefix('admin')->name('admin.')->group(function () {
    // Auth admin
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminLoginController::class, 'login']);
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');

    Route::middleware('auth:web')->group(function () {
        // Dashboard & notifikasi
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/notifications', [AdminDashboardController::class, 'notificationsIndex'])->name('notifications.index');

        // Students (resource)
        Route::resource('students', AdminStudentController::class);

        // Users (kelola admin & guru satu halaman)
        Route::resource('users', AdminUserController::class);

        // Schedules (resource) — gunakan alias controller admin
        Route::resource('schedules', AdminScheduleController::class);

        // Persetujuan & revisi jadwal (manual)
        Route::post('/schedules/{schedule}/approve', [AdminDashboardController::class, 'approve'])->name('schedules.approve');
        Route::post('/schedules/{schedule}/revision', [AdminDashboardController::class, 'revision'])->name('schedules.revision');

        // Profil & password admin
        Route::get('/profile', [AdminController::class, 'showProfile'])->name('profile.show');
        Route::get('/change-password', [AdminController::class, 'showChangePasswordForm'])->name('password.edit');
        Route::post('/change-password', [AdminController::class, 'updatePassword'])->name('password.update');

        // Notifikasi: tandai sudah dibaca
        Route::patch('/notifications/{notification}/mark-as-read', [AdminController::class, 'markAsRead'])->name('notifications.markAsRead');
    });
});

/* =========================
|        TEACHER ROUTES
========================= */
Route::prefix('teacher')->name('teacher.')->group(function () {
    // Auth guru
    Route::get('/login', [TeacherLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [TeacherLoginController::class, 'login']);
    Route::post('/logout', [TeacherLoginController::class, 'logout'])->name('logout');

    Route::middleware('auth:teacher')->group(function () {
        // Dashboard guru
        Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');

        // Profil guru
        Route::get('/profile', [TeacherDashboardController::class, 'showProfile'])->name('profile.show');

        // Ubah password guru
        Route::get('/change-password', [TeacherDashboardController::class, 'showChangePasswordForm'])->name('password.edit');
        Route::post('/change-password', [TeacherDashboardController::class, 'updatePassword'])->name('password.update');

        // STATUS jadwal (approve/revision) yang dikirim guru ke admin
        Route::post('/schedules/{schedule}/approve', [TeacherDashboardController::class, 'approve'])->name('schedules.approve');
        Route::post('/schedules/{schedule}/revision', [TeacherDashboardController::class, 'revision'])->name('schedules.revision');

        // Kirim catatan umum ke admin
        Route::post('/send-note', [TeacherDashboardController::class, 'sendNoteToAdmin'])->name('send-note');

        // Riwayat notifikasi guru
        Route::get('/notifications', [TeacherDashboardController::class, 'notificationsIndex'])->name('notifications.index');

        // Schedules CRUD untuk guru (ini yang bikin Create/Edit jalan)
        Route::resource('schedules', TeacherScheduleController::class)->except(['show']);
        // -> routes yang otomatis dibuat:
        // GET    /teacher/schedules           -> teacher.schedules.index
        // GET    /teacher/schedules/create    -> teacher.schedules.create
        // POST   /teacher/schedules           -> teacher.schedules.store
        // GET    /teacher/schedules/{schedule}/edit -> teacher.schedules.edit
        // PUT    /teacher/schedules/{schedule} -> teacher.schedules.update
        // PATCH  /teacher/schedules/{schedule} -> teacher.schedules.update
        // DELETE /teacher/schedules/{schedule} -> teacher.schedules.destroy
    });
});

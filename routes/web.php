<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Teacher\Auth\LoginController as TeacherLoginController;
use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\AdminStudentController;
use App\Http\Controllers\Admin\AdminUserController; // Menggunakan AdminUserController untuk mengelola admin dan guru
use App\Http\Controllers\Admin\ScheduleController;
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

// --- Grup Rute untuk Admin ---
Route::prefix('admin')->name('admin.')->group(function () {
    // Rute login admin
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminLoginController::class, 'login']);
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');
    
    Route::middleware('auth:web')->group(function () {

        // Rute untuk dashboard dan notifikasi admin
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/notifications', [AdminDashboardController::class, 'notificationsIndex'])->name('notifications.index');
        
        // Rute untuk mengelola siswa dengan resource controller
        Route::resource('students', AdminStudentController::class);

        // Rute untuk mengelola semua pengguna (admin dan guru) dalam satu halaman
        Route::resource('users', AdminUserController::class);

        // Rute untuk mengelola jadwal dengan resource controller
        Route::resource('schedules', ScheduleController::class);

        // Rute untuk persetujuan dan revisi (ini tetap manual karena bukan bagian dari resource)
        Route::post('/schedules/{schedule}/approve', [AdminDashboardController::class, 'approve'])->name('schedules.approve');
        Route::post('/schedules/{schedule}/revision', [AdminDashboardController::class, 'revision'])->name('schedules.revision');
        
        // Rute untuk mengelola profil dan password admin
        Route::get('/profile', [AdminController::class, 'showProfile'])->name('profile.show');
        Route::get('/change-password', [AdminController::class, 'showChangePasswordForm'])->name('password.edit');
        Route::post('/change-password', [AdminController::class, 'updatePassword'])->name('password.update');
        
        // Rute untuk menandai notifikasi sebagai sudah dibaca
        Route::patch('/notifications/{notification}/mark-as-read', [AdminController::class, 'markAsRead'])->name('notifications.markAsRead');
    });
});

// --- Grup Rute untuk Guru ---
Route::prefix('teacher')->name('teacher.')->group(function () {
    // Rute login guru
    Route::get('/login', [TeacherLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [TeacherLoginController::class, 'login']);
    Route::post('/logout', [TeacherLoginController::class, 'logout'])->name('logout');

    Route::middleware('auth:teacher')->group(function () {
        // Rute dashboard guru
        Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');
        
        // Rute untuk halaman profil guru
        Route::get('/profile', [TeacherDashboardController::class, 'showProfile'])->name('profile.show');

        // Rute untuk mengelola password guru
        Route::get('/change-password', [TeacherDashboardController::class, 'showChangePasswordForm'])->name('password.edit');
        Route::post('/change-password', [TeacherDashboardController::class, 'updatePassword'])->name('password.update');

        // Rute untuk mengelola status jadwal
        Route::post('/schedules/{schedule}/approve', [TeacherDashboardController::class, 'approve'])->name('schedules.approve');
        Route::post('/schedules/{schedule}/revision', [TeacherDashboardController::class, 'revision'])->name('schedules.revision');
        
        // Rute untuk mengirim notifikasi catatan umum ke admin
        Route::post('/send-note', [TeacherDashboardController::class, 'sendNoteToAdmin'])->name('send-note');
        
        // Rute untuk melihat riwayat notifikasi
        Route::get('/notifications', [TeacherDashboardController::class, 'notificationsIndex'])->name('notifications.index');
    });
});
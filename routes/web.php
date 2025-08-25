<?php

use Illuminate\Support\Facades\Route;

// Auth
use App\Http\Controllers\Teacher\Auth\LoginController as TeacherLoginController;
use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;

// Admin
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminStudentController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\ScheduleController as AdminScheduleController;

// Teacher
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Teacher\ScheduleController as TeacherScheduleController;
use App\Http\Controllers\TeacherController; // opsional bila dipakai untuk form revisi

// Landing
Route::get('/', fn () => view('welcome'));

// Redirect /login -> admin login
Route::get('/login', fn () => redirect()->route('admin.login'))->name('login');

/* =========================
|       ADMIN ROUTES
========================= */
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login',   [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login',  [AdminLoginController::class, 'login']);
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');

    Route::middleware('auth:web')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Notifikasi admin
        Route::get('/notifications', [AdminDashboardController::class, 'notificationsIndex'])
            ->name('notifications.index');
        Route::patch('/notifications/{notification}/mark-as-read', [AdminController::class, 'markAsRead'])
            ->name('notifications.markAsRead');
        Route::delete('/notifications/{notification}', [AdminController::class, 'destroyNotification'])->name('notifications.destroy');

        // CRUD resources
        Route::resource('students',  AdminStudentController::class);
        Route::resource('users',     AdminUserController::class);
        Route::resource('schedules', AdminScheduleController::class);

        // Quick actions: approve / revision
        Route::post('/schedules/{schedule}/approve',  [AdminDashboardController::class, 'approve'])
            ->name('schedules.approve');
        Route::post('/schedules/{schedule}/revision', [AdminDashboardController::class, 'revision'])
            ->name('schedules.revision');

        // Profil & password
        Route::get('/profile',         [AdminController::class, 'showProfile'])->name('profile.show');
        Route::get('/change-password', [AdminController::class, 'showChangePasswordForm'])->name('password.edit');
        Route::post('/change-password',[AdminController::class, 'updatePassword'])->name('password.update');
    });
});

/* =========================
|       TEACHER ROUTES
========================= */
Route::prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/login',   [TeacherLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login',  [TeacherLoginController::class, 'login']);
    Route::post('/logout', [TeacherLoginController::class, 'logout'])->name('logout');

    Route::middleware('auth:teacher')->group(function () {
        Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');

        // Profil & password
        Route::get('/profile',         [TeacherDashboardController::class, 'showProfile'])->name('profile.show');
        Route::get('/change-password', [TeacherDashboardController::class, 'showChangePasswordForm'])->name('password.edit');
        Route::post('/change-password',[TeacherDashboardController::class, 'updatePassword'])->name('password.update');

        // Notifikasi teacher
        Route::get('/notifications', [TeacherDashboardController::class, 'notificationsIndex'])
            ->name('notifications.index');
        Route::patch('/notifications/{notification}/mark-as-read', [TeacherDashboardController::class, 'markAsRead'])
            ->name('notifications.markAsRead');
        Route::delete('/notifications/{notification}', [TeacherDashboardController::class, 'destroyNotification'])
    ->name('notifications.delete');
        // Teacher kirim catatan ke admin
        Route::post('/send-note', [TeacherDashboardController::class, 'sendNoteToAdmin'])->name('send-note');

        // Quick actions: approve / revision
        Route::post('/schedules/{schedule}/approve',  [TeacherDashboardController::class, 'approve'])
            ->name('schedules.approve');
        Route::post('/schedules/{schedule}/revision', [TeacherDashboardController::class, 'revision'])
            ->name('schedules.revision');

        // (Opsional) form revisi via TeacherController umum
        Route::get('/schedules/{schedule}/create-revision',  [TeacherController::class, 'createRevision'])
            ->name('schedules.createRevision');
        Route::post('/schedules/{schedule}/submit-revision', [TeacherController::class, 'submitRevision'])
            ->name('schedules.submitRevision');

        // CRUD schedules milik teacher
        Route::resource('schedules', TeacherScheduleController::class)->except(['show']);
    });
});

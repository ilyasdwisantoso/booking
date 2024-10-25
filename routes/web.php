<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\{
    DashboardController as AdminDashboardController,
    MahasiswaController,
    DosenController,
    PermissionController,
    RoleController,
    UserController,
    ProdiController,
    MatakuliahController,
    RuanganController,
    BookingController,
    ClassmahasiswaController,
    AttendanceController as AdminAttendanceController
};
use App\Http\Controllers\Dosen\{
    DashboardController as DosenDashboardController,
    AttendanceController as DosenAttendanceController
};
use App\Http\Controllers\Mahasiswa\{
    DashboardController as MahasiswaDashboardController,
    AttendanceController as MahasiswaAttendanceController
};



// Auth routes
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::group(['middleware' => ['auth']], function() {

    // Admin routes
    Route::group(['middleware' => 'isAdmin', 'prefix' => 'admin', 'as' => 'admin.'], function() {
        Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard.index');
        Route::resource('permissions', PermissionController::class);
        Route::delete('permissions_mass_destroy', [PermissionController::class, 'massDestroy'])->name('permissions.mass_destroy');
        Route::resource('roles', RoleController::class);
        Route::delete('roles_mass_destroy', [RoleController::class, 'massDestroy'])->name('roles.mass_destroy');
        Route::resource('users', UserController::class);
        Route::delete('users_mass_destroy', [UserController::class, 'massDestroy'])->name('users.mass_destroy');
        Route::resource('mahasiswas', MahasiswaController::class);
        Route::delete('mahasiswas_mass_destroy', [MahasiswaController::class, 'massDestroy'])->name('mahasiswas.mass_destroy');
        Route::resource('dosen', DosenController::class);
        Route::delete('dosen_mass_destroy', [DosenController::class, 'massDestroy'])->name('dosen.mass_destroy');
        Route::resource('prodi', ProdiController::class);
        Route::delete('prodi_mass_destroy', [ProdiController::class, 'massDestroy'])->name('prodi.mass_destroy');
        Route::resource('matakuliah', MatakuliahController::class);
        Route::delete('matakuliah_mass_destroy', [MatakuliahController::class, 'massDestroy'])->name('matakuliah.mass_destroy');
        Route::resource('ruangan', RuanganController::class);
        Route::delete('ruangan_mass_destroy', [RuanganController::class, 'massDestroy'])->name('ruangan.mass_destroy');
        Route::resource('booking', BookingController::class);
        Route::delete('booking_mass_destroy', [BookingController::class, 'massDestroy'])->name('booking.mass_destroy');
        Route::resource('attendance', AdminAttendanceController::class);
        Route::delete('attendance_mass_destroy', [AdminAttendanceController::class, 'massDestroy'])->name('attendance.mass_destroy');

       // Tambahkan route ini ke dalam grup admin
       Route::get('/update-class-status', [BookingController::class, 'updateClassStatus'])->name('update-class-status');
       Route::get('/get-class-statuses', [BookingController::class, 'getClassStatuses'])->name('get-class-statuses');

    });

    // Dosen routes
    Route::group(['middleware' => 'isDosen', 'prefix' => 'dosen', 'as' => 'dosen.'], function() {
        Route::get('dashboard', [DosenDashboardController::class, 'index'])->name('dashboard.index');
        Route::get('attendance', [DosenAttendanceController::class, 'index'])->name('attendance.index');
        Route::get('courses', [DosenDashboardController::class, 'courses'])->name('courses');
        Route::get('attendance/{booking}/percentage', [DosenAttendanceController::class, 'showAttendancePercentage'])->name('attendance.percentage');
        Route::put('attendance/{booking}/updatePertemuan', [DosenAttendanceController::class, 'updatePertemuan'])->name('attendance.updatePertemuan');
        Route::get('attendance/{booking}/chart', [DosenAttendanceController::class, 'showAttendanceChart'])->name('attendance.attendance_chart');
        Route::delete('attendance_mass_destroy', [DosenAttendanceController::class, 'massDestroy'])->name('attendance.mass_destroy');

        Route::get('/update-class-status', [DosenDashboardController::class, 'updateClassStatus'])->name('update-class-status');
        Route::get('/get-class-statuses', [DosenDashboardController::class, 'getClassStatuses'])->name('get-class-statuses');

       

        
    });

    // Mahasiswa routes
    Route::group(['middleware' => 'isMahasiswa', 'prefix' => 'mahasiswa', 'as' => 'mahasiswa.'], function() {
        Route::get('dashboard', [MahasiswaDashboardController::class, 'index'])->name('dashboard.index');
        Route::get('attendance', [MahasiswaAttendanceController::class, 'index'])->name('attendance');
        Route::get('courses', [MahasiswaDashboardController::class, 'courses'])->name('courses');
    });
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Redirect root to dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Authentication Routes
Auth::routes();

// Home route (after login redirect)
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('auth');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Employee Management (Admin & HR only)
    Route::middleware(['role:admin,hr'])->group(function () {
        Route::resource('employees', EmployeeController::class);
    });
    
    // Department Management (Admin & HR only)
    Route::middleware(['role:admin,hr'])->group(function () {
        Route::resource('departments', DepartmentController::class);
    });
    
    // Attendance Management
    Route::resource('attendances', AttendanceController::class);
    Route::post('/attendance/clock-in', [AttendanceController::class, 'clockIn'])->name('attendance.clock-in');
    Route::post('/attendance/clock-out', [AttendanceController::class, 'clockOut'])->name('attendance.clock-out');
    
    // Leave Management
    Route::resource('leaves', LeaveController::class);
    
    // Leave Approval (Admin & HR only)
    Route::middleware(['role:admin,hr'])->group(function () {
        Route::patch('/leaves/{id}/approve', [LeaveController::class, 'approve'])->name('leaves.approve');
        Route::patch('/leaves/{id}/reject', [LeaveController::class, 'reject'])->name('leaves.reject');
    });
});

<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Tickets
    Route::resource('tickets', TicketController::class);
    Route::post('tickets/{ticket}/assign', [TicketController::class, 'assignTechnician'])->name('tickets.assign');
    Route::post('tickets/{ticket}/take', [TicketController::class, 'takeTicket'])->name('tickets.take');
    Route::post('tickets/{ticket}/resolve', [TicketController::class, 'resolveTicket'])->name('tickets.resolve');
    Route::post('tickets/{ticket}/comment', [TicketController::class, 'addComment'])->name('tickets.comment');

    // Admin routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('categories', CategoryController::class);
        Route::resource('departments', DepartmentController::class);
        Route::resource('users', UserController::class);
    });
});

require __DIR__.'/auth.php';

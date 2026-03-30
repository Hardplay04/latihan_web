<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\InstructorApplicationController;
use App\Http\Controllers\Admin\InstructorApplicationsController;
use App\Http\Controllers\Admin\AdminDashboardController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard',[AdminDashboardController::class,'index'])->name('dashboard');
});

Route::middleware('auth')->group(function () {
    //student apply menjadi instructor
    Route::get('/instructor/apply', [InstructorApplicationController::class,'create'])->name('instructor.apply');
    Route::post('/instructor/apply', [InstructorApplicationController::class,'store'])->name('instructor.apply.store');
    //pemisah aja
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //lanjutan student apply
    Route::middleware(['role:admin'])->prefix('admin')->name('admin')->group(function(){
        Route::get('instructor-applications',[InstructorApplicationsController::class,'index'])->name('instructor_application.index');
        Route::post('instructor-application/{user}/approve',[InstructorApplicationsController::class,'approve'])->name('instructor_application.approve');
        Route::post('instructor-application/{user}/reject',[InstructorApplicationsController::class,'reject'])->name('instructor_application.reject');
    });
});

require __DIR__.'/auth.php';

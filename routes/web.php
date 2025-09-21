<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NestController;
use App\Models\Nest;
use App\Http\Controllers\ThreatController;
use App\Http\Controllers\IncubationController;
use App\Http\Controllers\HatchReleaseController;
use App\Http\Controllers\ReportController;

Route::post('/generate-pdf', [ReportController::class, 'generatePdf'])->name('generate.pdf');


// report generate
Route::middleware('auth')->get('/generate-report/{nest}', [ReportController::class, 'generate'])
    ->name('report.generate');

// incubation
Route::middleware('auth')->post('/incubation', [IncubationController::class, 'store'])
    ->name('incubation.store');

Route::middleware('auth')->get('/profile', function () {
    return 'Profile feature not implemented yet.';
})->name('profile.edit');


// threats report
Route::middleware('auth')->get('/dashboard', function () {
    $nestCount = Nest::count();
    $nests = Nest::all();
    return view('dashboard', compact('nestCount','nests'));
})->name('dashboard');

// Dashboard with stats
Route::middleware('auth')->get('/dashboard', function () {
    $nestCount = \App\Models\Nest::count();
    $nests = \App\Models\Nest::all();
    return view('dashboard', compact('nestCount','nests'));
})->name('dashboard');


// Nest logging
Route::middleware('auth')->group(function () {
    Route::get('/nests', [NestController::class, 'index'])->name('nests.index');
    Route::post('/nests', [NestController::class, 'store'])->name('nests.store');
});

// incubation
Route::middleware('auth')->post('/hatch-release', [HatchReleaseController::class, 'store'])->name('hatch.store');


// Show login form
Route::get('/login', function () {
    return view('home');
})->middleware('guest')->name('login');

// Handle login submission
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// Logout
Route::middleware('auth')->post('/logout', [AuthController::class, 'logout'])->name('logout');
// Redirect root
Route::redirect('/', '/login');

Route::middleware('auth')->post('/threats', [ThreatController::class, 'store'])->name('threats.store');
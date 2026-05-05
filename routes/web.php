<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NestController;
use App\Http\Controllers\ThreatController;
use App\Http\Controllers\IncubationController;
use App\Http\Controllers\HatchReleaseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : view('home');
})->name('home');

Route::get('/login', function () {
    return auth()->check() ? redirect()->route('dashboard') : view('home');
})->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::post('/logout', function () {
    auth()->logout();
    return redirect('/');
})->name('logout');

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Charts (AJAX)
    Route::get('/charts/data', [ChartController::class, 'getData']);
    Route::get('/charts/years', [ChartController::class, 'getYears']);

    // Nests
    Route::get('/nests', [NestController::class, 'index'])->name('nests.index');
    Route::post('/nests', [NestController::class, 'store'])->name('nests.store');
    Route::delete('/nests/{nest}', [NestController::class, 'destroy'])->name('nests.destroy');

    // Other modules
    Route::post('/incubation', [IncubationController::class, 'store'])->name('incubation.store');
    Route::post('/hatch-release', [HatchReleaseController::class, 'store'])->name('hatch.store');
    Route::post('/threats', [ThreatController::class, 'store'])->name('threats.store');

    // Reports
    Route::post('/generate-pdf', [ReportController::class, 'generatePdf'])->name('generate.pdf');
    Route::get('/generate-report/{nest}', [ReportController::class, 'generate'])->name('report.generate');
});
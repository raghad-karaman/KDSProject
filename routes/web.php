<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NeedReportController;
use App\Http\Controllers\Admin\ResourceController;
use App\Http\Controllers\Admin\NeedController;
use App\Http\Controllers\Admin\RegionController;
use App\Http\Controllers\Admin\VictimController;
use App\Http\Controllers\Admin\ReliefTeamController;
use App\Http\Controllers\Admin\DistributionController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\NotificationController;
use App\Models\Region;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Api\PythonApiController;
use App\Http\Controllers\Admin\DashboardController;



Route::get('/', [HomeController::class, 'index'])->name('home');



Route::post('/need-report', [NeedReportController::class, 'store'])
    ->name('need.store');



Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth'])
    ->group(function () {
        Route::resource('resources', App\Http\Controllers\Admin\ResourceController::class);
        Route::resource('needs', App\Http\Controllers\Admin\NeedController::class);
        Route::resource('regions', App\Http\Controllers\Admin\RegionController::class);
        Route::resource('victims', App\Http\Controllers\Admin\VictimController::class);
        Route::resource('relief-teams', App\Http\Controllers\Admin\ReliefTeamController::class);
        Route::resource('distributions', App\Http\Controllers\Admin\DistributionController::class);
        Route::resource('reports', App\Http\Controllers\Admin\ReportController::class);
        Route::resource('notifications', App\Http\Controllers\Admin\NotificationController::class);
    });

Route::get('/python-summary', [PythonApiController::class, 'summary']);
Route::get('/python-top-regions', [PythonApiController::class, 'topRegions']);
Route::get('/python-needs-distribution', [PythonApiController::class, 'needsDistribution']);
Route::get('/python-map', [PythonApiController::class, 'mapData']);

require __DIR__.'/auth.php';

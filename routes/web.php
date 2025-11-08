<?php

use App\Http\Controllers\DriverLogbookController;
use App\Http\Controllers\OptimalPathController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShortestPathController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    // Optimal Path Routes
    Route::get('/optimal-path', [OptimalPathController::class, 'index'])->name('optimal-path');
    Route::post('/deriveTSP', [ShortestPathController::class, 'deriveTSP'])->name('deriveTSP');
    Route::post('/save-route', [OptimalPathController::class, 'saveRoute'])->name('saveRoute');
    Route::get('/user-routes', [OptimalPathController::class, 'getUserRoutes'])->name('getUserRoutes');
    Route::get('/route/{id}', [OptimalPathController::class, 'show'])->name('route.show');
    Route::delete('/route/{id}', [OptimalPathController::class, 'delete'])->name('route.delete');

    // Driver Logbook Routes
    Route::get('/driver-logbook', [DriverLogbookController::class, 'index'])->name('driver-logbook');
    Route::post('/save-driver-checklist', [DriverLogbookController::class, 'saveChecklist'])->name('saveDriverChecklist');
    Route::post('/save-odometer-reading', [DriverLogbookController::class, 'saveOdometer'])->name('saveOdometerReading');
    Route::get('/driver-logs', [DriverLogbookController::class, 'getLogs'])->name('getDriverLogs');
    Route::delete('/driver-log/{id}', [DriverLogbookController::class, 'deleteLog'])->name('deleteDriverLog');
});

Route::middleware('auth')->group(function () {
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
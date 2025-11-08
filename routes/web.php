<?php

use App\Http\Controllers\AddressController;
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


// 

// Route::get('/', [AddressController::class, 'index']);

// Route::get('/tsp', [AddressController::class, 'tspVue']);


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/optimal-path', function () {
    return view('optimal-path');
})->middleware(['auth', 'verified'])->name('optimal-path');

Route::post('/deriveTSP', [ShortestPathController::class, 'deriveTSP'])->middleware(['auth', 'verified'])->name('deriveTSP');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
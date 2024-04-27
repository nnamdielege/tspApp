<?php

use App\Http\Controllers\AddressController;
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

// Route::get('/searchPath', function () {
//     return view('searchPath');
// });

Route::get('/searchPath', function () {
    return view('app');
})->name('application');

Route::post('/deriveTSP', [ShortestPathController::class, 'deriveTSP'])->name('deriveTSP');

Route::get('address', [AddressController::class, 'index']);

<?php

use BoundlessAnalytics\Tsp\Http\Controllers\TspController;

Route::get('/solve-tsp', [TspController::class, 'solveTSP']);
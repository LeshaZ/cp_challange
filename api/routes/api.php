<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChargingController;

Route::post('/start-charging', [ChargingController::class, 'startCharging']);

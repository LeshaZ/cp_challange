<?php

use App\Http\Controllers\ChargingController;
use Illuminate\Support\Facades\Route;

Route::get('start-charging', [ChargingController::class, 'startCharging']);

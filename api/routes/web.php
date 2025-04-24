<?php

use App\Http\Controllers\ChargingController;
use Illuminate\Support\Facades\Route;

//TODO: for debugging purposes
Route::get('callback', [ChargingController::class, 'callbackDebug']);

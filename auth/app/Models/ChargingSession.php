<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChargingSession extends Model
{
    protected $table = 'charging_sessions';

    protected $fillable = [
        'station_id',
        'driver_token',
        'callback_url',
        'decision',
        'status',
        'idempotency_key',
    ];
}

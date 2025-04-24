<?php

namespace App\Http\Enums;

enum ChargingSessionDecision: string
{
    case ALLOWED = 'allowed';
    case NOT_ALLOWED = 'not_allowed';
    case UNKNOWN = 'unknown';
    case INVALID = 'invalid';
}

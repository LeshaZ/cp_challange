<?php

namespace App\Http\Enums;

enum EventStatus: string
{
    case PENDING = 'pending';
    case COMPLETED = 'completed';
    case FAILED = 'failed';
}

<?php

namespace App\Enums;

enum ResponseStatusEnum: string
{
    case ALLOWED = 'allowed';
    case NOT_ALLOWED = 'not_allowed';
    case UNKNOWN = 'unknown';
    case INVALID = 'invalid';
}

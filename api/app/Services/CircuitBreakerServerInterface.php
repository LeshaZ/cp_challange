<?php

namespace App\Services;

interface CircuitBreakerServerInterface
{
    public function isAuthServiceAvailable(int $timeout = null): bool;
}

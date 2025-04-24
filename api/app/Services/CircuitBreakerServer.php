<?php

namespace App\Services;

class CircuitBreakerServer implements CircuitBreakerServerInterface
{
    //TODO: Implement Circuit Breaker logic here
    public function isAuthServiceAvailable(int $timeout = null): bool
    {
        return $timeout === null;
    }
}

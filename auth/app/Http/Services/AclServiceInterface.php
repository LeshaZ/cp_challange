<?php

namespace App\Http\Services;

interface AclServiceInterface
{
    public function makeDecision(string $driverToken): string;
}

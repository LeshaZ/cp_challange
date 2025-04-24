<?php

namespace App\Http\Services;

use App\Http\Enums\ChargingSessionDecision;

class AclService implements AclServiceInterface
{
    public function makeDecision(string $driverToken): string
    {
        //TODO: Implement ACL logic here
        if ($driverToken === 'validDriverToken1234') {
            return ChargingSessionDecision::ALLOWED->value;
        }

        if ($driverToken === 'invalidDriverToken1234') {
            return ChargingSessionDecision::INVALID->value;
        }

        return ChargingSessionDecision::NOT_ALLOWED->value;
    }
}

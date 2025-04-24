<?php

namespace App\Services;

interface ChargingSessionServiceInterface
{
    public function sendStartChargingEvent(array $payload): void;

    public function sendUnknownDriverToken(array $payload): void;
}

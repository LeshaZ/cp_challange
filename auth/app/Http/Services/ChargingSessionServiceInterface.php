<?php

namespace App\Http\Services;

use App\Models\ChargingSession;

interface ChargingSessionServiceInterface
{
    public function create(array $data): ChargingSession;

    public function update(array $data, ?int $id): void;

    public function findOne(array $data): ?ChargingSession;
}

<?php

namespace App\Http\Repositories;

use App\Models\ChargingSession;

interface ChargingSessionRepositoryInterface
{
    public function create(array $data): ChargingSession;

    public function update(array $data, ?int $id): void;

    public function findOne(array $data): ?ChargingSession;
}

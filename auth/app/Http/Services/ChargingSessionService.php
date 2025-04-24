<?php

namespace App\Http\Services;

use App\Http\Repositories\ChargingSessionRepositoryInterface;
use App\Models\ChargingSession;

class ChargingSessionService implements ChargingSessionServiceInterface
{
    public function __construct(private readonly ChargingSessionRepositoryInterface $chargingSessionRepository)
    {
    }

    public function findOne(array $data): ?ChargingSession
    {
        return $this->chargingSessionRepository->findOne($data);
    }

    public function create(array $data): ChargingSession
    {
        return $this->chargingSessionRepository->create($data);
    }

    public function update(array $data, ?int $id): void
    {
        $this->chargingSessionRepository->update($data, $id);
    }
}

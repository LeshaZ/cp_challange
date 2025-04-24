<?php

namespace App\Http\Repositories;

use App\Models\ChargingSession;

class ChargingSessionRepository extends BaseRepository implements ChargingSessionRepositoryInterface
{
    public function findOne(array $data): ?ChargingSession
    {
        return $this->model->where($data)->first();
    }

    public function create(array $data): ChargingSession
    {
        return $this->model->create([
            'station_id' =>  $data['station_id'],
            'driver_token' => $data['driver_token'],
            'callback_url' => $data['callback_url'],
            'decision' => $data['decision'],
            'status' => $data['status'],
            'idempotency_key' => $data['idempotency_key'],
        ]);
    }

    public function update(array $data, ?int $id): void
    {
        $this->model->where('id', $id)->update($data);
    }

    protected function getModelName(): string
    {
        return ChargingSession::class;
    }
}

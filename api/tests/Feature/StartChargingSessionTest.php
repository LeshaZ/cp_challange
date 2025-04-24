<?php

namespace Tests\Feature;


use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class StartChargingSessionTest extends TestCase
{
    public function testBadRequestCall(): void
    {
        Redis::spy();
        Log::spy();

        $response = $this->post('/api/start-charging', []);

        $response->assertStatus(400);

        Redis::shouldNotHaveReceived('lpush');
        Log::shouldNotHaveReceived('info');
    }

    public function testSuccessfulCall(): void
    {
        Redis::spy();
        Log::spy();

        $idempotencyKey = base64_encode("123e4567-e89b-12d3-a456-426614174000:validDriverToken1234:http://nginx/callback");
        $eventData = [
            'station_id' => '123e4567-e89b-12d3-a456-426614174000',
            'driver_token' => 'validDriverToken1234',
            'callback_url' => 'http://nginx/callback',
            'idempotency_key' => $idempotencyKey,
        ];

        $response = $this->post('/api/start-charging', $eventData);

        $response->assertStatus(200);
        $response->assertExactJson([
            'status' => 'accepted',
            'message' => 'Request is being processed asynchronously. The result will be sent to the provided callback URL.'
        ]);

        Redis::shouldHaveReceived('lpush')
            ->once()
            ->with('charging_queue', json_encode($eventData));
        Log::shouldHaveReceived('info')
            ->once()
            ->with("Data pushed to Redis charging_queue queue", ['data' => $eventData]);
    }
}

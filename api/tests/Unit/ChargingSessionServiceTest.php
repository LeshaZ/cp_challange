<?php

namespace Tests\Unit;

use App\Services\ChargingSessionService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class ChargingSessionServiceTest extends TestCase
{
    public function testSendStartChargingEvent()
    {
        Redis::spy();
        Log::spy();

        $service = new ChargingSessionService();

        $payload = [
            'station_id' => 'some-station-id',
            'driver_token' => 'driver123',
            'callback_url' => 'http://example.com/callback',
        ];

        $service->sendStartChargingEvent($payload);

        Redis::shouldHaveReceived('lpush')
            ->once()
            ->with(
                'charging_queue',
                json_encode([
                    'station_id' => 'some-station-id',
                    'driver_token' => 'driver123',
                    'callback_url' => 'http://example.com/callback',
                    'idempotency_key' => base64_encode('some-station-id:driver123:http://example.com/callback'),
                ])
            );
        Log::shouldHaveReceived('info')
            ->once()
            ->with(
                'Data pushed to Redis charging_queue queue',
                ['data' => $payload]
            );
    }
}

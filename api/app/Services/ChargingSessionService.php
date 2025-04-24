<?php

namespace App\Services;

use App\Http\Enums\ChargingSessionDecision;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Exception;

class ChargingSessionService implements ChargingSessionServiceInterface
{
   public function sendStartChargingEvent(array $payload): void
   {
       $queueName = env('REDIS_MESSAGE_QUEUE_NAME', 'charging_queue');
       $idempotencyKey = base64_encode("{$payload['station_id']}:{$payload['driver_token']}:{$payload['callback_url']}");

       Redis::lpush($queueName, json_encode([
           'station_id' => $payload['station_id'],
           'driver_token' => $payload['driver_token'],
           'callback_url' => $payload['callback_url'],
           'idempotency_key' => $idempotencyKey,
       ]));

       Log::info("Data pushed to Redis $queueName queue", ['data' => $payload]);
   }

   public function sendUnknownDriverToken(array $payload): void
   {
       try {
           Http::get($payload['callback_url'], [
               'status' => ChargingSessionDecision::NOT_ALLOWED->value,
               'station_id' => $payload['station_id'],
               'driver_token' => 'unknown.',
           ]);

           Log::info("Unknown driver token response has been sent", ['data' => $payload]);
       } catch (Exception) {
           Log::error("Failed to send unknown driver token response", ['data' => $payload]);
       }

   }
}

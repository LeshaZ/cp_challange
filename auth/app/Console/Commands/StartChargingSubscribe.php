<?php

namespace App\Console\Commands;

use App\Http\Enums\EventStatus;
use App\Http\Services\AclServiceInterface;
use App\Http\Services\ChargingSessionServiceInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;
use Exception;

class StartChargingSubscribe extends Command
{
    public function __construct(
        private readonly ChargingSessionServiceInterface $chargingSessionService,
        private readonly AclServiceInterface $aclService
    ){
        parent::__construct();
    }

    protected $signature = 'redis:start-charging:subscribe';
    protected $description = 'Start charging session subscribe command';

    public function handle(): void
    {
        $queueName = env('REDIS_MESSAGE_QUEUE_NAME', 'charging_queue');

        $this->info("Listening to Redis channel: $queueName");

        while (true) {
            try {
                $eventId = null;
                $message = Redis::lpop($queueName);

                if ($message && strlen($message)) {
                    $this->info("Received message: $message");

                    $data = json_decode($message, true);
                    // TODO: status should be checked as well to avoid processing the same event multiple times.
                    $existingEvent = $this->chargingSessionService->findOne([
                        'idempotency_key' => $data['idempotency_key']
                    ]);

                    $driverAccessDecision = $this->aclService->makeDecision($data['driver_token']);

                    if(!$existingEvent) {
                        $chargingSessionEvent = $this->chargingSessionService->create([
                            'station_id' =>  $data['station_id'],
                            'driver_token' => $data['driver_token'],
                            'callback_url' => $data['callback_url'],
                            'decision' => $driverAccessDecision,
                            'status' => EventStatus::PENDING->value,
                            'idempotency_key' => $data['idempotency_key'],
                        ]);

                        $eventId = $chargingSessionEvent->id;
                    } else {
                        //TODO: Should be skipped if processing. The event is processing only for debugging purposes.
                        $this->info("Event: $message is still processing");
                        $eventId = $existingEvent->id;
                    }

                    $response = Http::get($data['callback_url'], [
                        'status' => $driverAccessDecision,
                        'station_id' => $data['station_id'],
                        'driver_token' => $data['driver_token'],
                    ]);

                    $this->info("Callback URL: {$data['callback_url']} has been called");
                    $this->info("Response:" . $response->body());

                    $this->chargingSessionService->update(['status' => EventStatus::COMPLETED->value], $eventId);
                }

                sleep(3);
            } catch (Exception $e) {
                $failedEventStatus = EventStatus::FAILED->value;
                $this->error("Error: " . $e->getMessage());
                $this->error("Changing status to: $failedEventStatus for event: $eventId");
                $this->chargingSessionService->update(['status' => $failedEventStatus], $eventId);
            }
        }
    }
}

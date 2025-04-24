<?php

namespace App\Http\Controllers;

use App\Http\Requests\StartChargingRequest;
use App\Services\ChargingSessionServiceInterface;
use App\Services\CircuitBreakerServerInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;


class ChargingController extends Controller
{
    public function __construct(
        private readonly ChargingSessionServiceInterface $chargingSessionService,
        private readonly CircuitBreakerServerInterface $circuitBreakerServer
    ){}

    #[OA\Post(path: '/api/start-charging')]
    #[OA\RequestBody(
        description: 'Request body containing the parameters',
        required: true,
        content: new OA\JsonContent(
            required: ['station_id', 'driver_token', 'callback_url'],
            properties: [
                new OA\Property(property: 'station_id', description: 'UUID of the charging station', type: 'string'),
                new OA\Property(
                    property: 'driver_token', description: 'Driver token (20-80 characters)', type: 'string'
                ),
                new OA\Property(
                    property: 'callback_url', description: 'Callback URL for async response', type: 'string'
                ),
                new OA\Property(
                    property: 'timeout', description: 'Timeout for demonstration', type: 'integer'
                ),
            ]
        )
    )]
    #[OA\Response(response: Response::HTTP_OK, description: 'OK. Request is being processed asynchronously. The result will be sent to the provided callback URL.')]
    #[OA\Response(response: Response::HTTP_BAD_REQUEST, description: 'Bad request. Invalid input data.')]
    public function startCharging(StartChargingRequest $request): JsonResponse
    {
        $payload = $request->validated();
        $eventData = [
            'station_id' => $payload['station_id'],
            'callback_url' => $payload['callback_url'],
            'driver_token' => $payload['driver_token'],
            'idempotency_key' => base64_encode("{$payload['station_id']}:{$payload['driver_token']}:{$payload['callback_url']}"),
        ];

        $isAuthServiceAvailable = $this->circuitBreakerServer->isAuthServiceAvailable($payload['timeout'] ?? null);

        if (!$isAuthServiceAvailable) {
            $this->chargingSessionService->sendUnknownDriverToken($eventData);
        } else {
            $this->chargingSessionService->sendStartChargingEvent($eventData);
        }

        return response()->json([
            'status' => 'accepted',
            'message' => 'Request is being processed asynchronously. The result will be sent to the provided callback URL.'
        ]);
    }

    // TODO: Added for debugging purposes.
    public function callbackDebug(Request $request): JsonResponse
    {
        $data = $request->all();

        Log::info("Received callback data", ['data' => $data]);

        return response()->json([
            'status' => 'ok',
            'message' => $data
        ]);
    }
}

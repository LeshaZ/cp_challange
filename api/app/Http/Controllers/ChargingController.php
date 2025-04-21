<?php

namespace App\Http\Controllers;

use App\Http\Requests\StartChargingRequest;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;


class ChargingController extends Controller
{
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
            ]
        )
    )]
    #[OA\Response(response: Response::HTTP_OK, description: 'OK. Request is being processed asynchronously. The result will be sent to the provided callback URL.')]
    #[OA\Response(response: Response::HTTP_BAD_REQUEST, description: 'Bad request. Invalid input data.')]
    public function startCharging(/*StartChargingRequest $request*/): JsonResponse
    {
        $queueName = env('REDIS_MESSAGE_QUEUE_NAME');
//        $payload = $request->validated();
        $payload = [
            'station_id' => '123e4567-e89b-12d3-a456-426614174000',
            'driver_token' => 'abcde12345fghij67890klmnopqrstu',
            'callback_url' => 'https://example.com/callback'
        ];

        Redis::lpush($queueName, json_encode([
            'station_id' => $payload['station_id'],
            'driver_token' => $payload['driver_token'],
            'callback_url' => $payload['callback_url'],
        ]));
        var_dump($payload['station_id']);
        Log::info("Data pushed to Redis $queueName queue", ['data' => $payload]);

        return response()->json([
            'status' => 'accepted',
            'message' => 'Request is being processed asynchronously. The result will be sent to the provided callback URL.'
        ]);
    }
}

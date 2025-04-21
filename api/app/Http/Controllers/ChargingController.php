<?php

namespace App\Http\Controllers;

use App\Http\Requests\StartChargingRequest;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;


class ChargingController extends Controller
{
    #[OA\Get(path: '/api/users')]
    #[OA\Response(response: Response::HTTP_OK, description: 'OK')]
    #[OA\Response(response: Response::HTTP_BAD_REQUEST, description: 'Not allowed')]
    public function startCharging(StartChargingRequest $request): JsonResponse
    {
        return response()->json([
            'status' => 'accepted',
            'message' => 'Request is being processed asynchronously. The result will be sent to the provided callback URL.'
        ]);
    }
}

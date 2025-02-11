<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponses
{
    protected function success($message, $data = null, $statusCode = Response::HTTP_OK): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
            'status' => $statusCode,
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        return response()->json($response, $statusCode);
    }

    protected function errorResponse($message, $statusCode = Response::HTTP_UNAUTHORIZED): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
            'status' => $statusCode,
        ];

        return response()->json($response, $statusCode);
    }

    protected function respondWithToken($token, $status = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ], $status);
    }
}

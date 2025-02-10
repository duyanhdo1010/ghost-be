<?php

namespace App\Traits;

use Symfony\Component\HttpFoundation\Response;

trait ApiResponses
{
    protected function success($message, $data = null, $statusCode = Response::HTTP_OK)
    {
        $response = [
            'success' => true,
            'message' => $message,
            'status'  => $statusCode,
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        return response()->json($response, $statusCode);
    }

    protected function respondWithToken($token, $status = Response::HTTP_OK)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ], $status);
    }
}

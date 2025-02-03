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
}

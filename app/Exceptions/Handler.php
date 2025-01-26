<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels
        = [
            //
        ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport
        = [
            //
        ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash
        = [
            'current_password',
            'password',
            'password_confirmation',
        ];

    protected function createErrorResponse($message, $statusCode, $errors = [])
    {
        $response = [
            'success' => false,
            'message' => $message,
            'status'  => $statusCode
        ];

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            \Log::error($e->getMessage());
        });

        $this->renderable(function (Throwable $e, Request $request) {
            if ($request->wantsJson()) {
                if ($e instanceof ResourceNotFoundException) {
                    return $this->createErrorResponse('Resource not found!', Response::HTTP_NOT_FOUND);
                }
                if ($e instanceof ValidationException) {
                    return $this->createErrorResponse(
                        'Validation failed',
                        Response::HTTP_UNPROCESSABLE_ENTITY,
                        $e->errors()
                    );
                }
                return $this->createErrorResponse('Internal server error!', Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        });
    }
}

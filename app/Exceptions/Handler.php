<?php

namespace App\Exceptions;

use App\Http\Responses\ApiErrorsResponse;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontReport = [];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {

        $this->reportable(function (Throwable $e) {});
    }

    public function render($request, Throwable $exception)
    {
        if ($request->expectsJson()) {

            if ($exception instanceof \Illuminate\Validation\ValidationException) {
                return new ApiErrorsResponse(Response::HTTP_UNPROCESSABLE_ENTITY, $exception->errors());
            }

            if ($exception instanceof UserNotFoundException) {
                return new ApiErrorsResponse(Response::HTTP_NOT_FOUND, ['message' => $exception->getMessage()]);
            }

            if ($exception instanceof ModelNotFoundException) {
                return new ApiErrorsResponse(Response::HTTP_NOT_FOUND, ['message' => 'Resource not found']);
            }


            if ($exception instanceof NegativeBalanceException) {
                return new ApiErrorsResponse(Response::HTTP_CONFLICT, ['message' => $exception->getMessage()]);
            }


            if ($exception instanceof QueryException) {
                $code = $exception->getCode() === '23000'
                    ? Response::HTTP_CONFLICT
                    : Response::HTTP_INTERNAL_SERVER_ERROR;

                return new ApiErrorsResponse($code, ['message' => $exception->getMessage()]);
            }

            return new ApiErrorsResponse(Response::HTTP_INTERNAL_SERVER_ERROR, ['message' => $exception->getMessage()]);
        }

        return parent::render($request, $exception);
    }
}

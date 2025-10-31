<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Throwable;
use Symfony\Component\HttpFoundation\Response;

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

            if ($exception instanceof ModelNotFoundException) {
                return response()->json([
                    'status' => Response::HTTP_NOT_FOUND,
                    'message' => 'Запрашиваемый ресурс не найден',
                    'errors' => [
                        'model' => class_basename($exception->getModel())
                    ]
                ], Response::HTTP_NOT_FOUND);
            }

            if ($exception instanceof ValidationException) {
                return response()->json([
                    'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                    'message' => 'Ошибка валидации данных',
                    'errors' => $exception->errors(),
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            return response()->json([
                'status' => $exception->getCode() ?: 500,
                'message' => $exception->getMessage() ?: 'Внутренняя ошибка сервера',
            ], $exception->getCode() ?: 500);
        }

        return parent::render($request, $exception);
    }
}

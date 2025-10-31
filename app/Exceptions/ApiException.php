<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class ApiException extends Exception
{
    private int $httpResponseCode;

    public function __construct(string $message, int $httpResponseCode)
    {
        $this->httpResponseCode = $httpResponseCode;
        parent::__construct($message);
    }

    public function getStatusCode()
    {
        return $this->httpResponseCode;
    }

    public function render(): JsonResponse
    {
        return response()->json([
            'error' => $this->getMessage()
        ], $this->getStatusCode());
    }
}

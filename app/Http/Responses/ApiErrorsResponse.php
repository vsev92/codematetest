<?php

namespace App\Http\Responses;

use Throwable;

class ApiErrorsResponse extends ApiResponse
{
    public function __construct(Throwable $exception, array $meta = [], int $defaultStatusCode = 500)
    {
        $statusCode = $exception->getCode() > 0 ? $exception->getCode() : $defaultStatusCode;

        parent::__construct($statusCode, $meta);

        $this->response['errors'] = [$exception->getMessage()];
    }
}

<?php


namespace App\Http\Responses;

class ApiErrorsResponse extends ApiResponse
{
    public function __construct(int $statusCode, array $errors,  array $meta = [])
    {
        parent::__construct($statusCode, $meta);
        $this->response['errors'] = $errors;
    }
}

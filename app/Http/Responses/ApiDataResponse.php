<?php

namespace App\Http\Responses;

class ApiDataResponse extends ApiResponse
{
    public function __construct(int $statusCode, mixed  $data, array $meta = [])
    {
        parent::__construct($statusCode, $meta);

        if (is_object($data) && method_exists($data, 'toArray')) {
            $this->response['data'] = $data->toArray(request());
        } else {
            $this->response['data'] = $data;
        }
    }
}

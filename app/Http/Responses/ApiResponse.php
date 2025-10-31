<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Symfony\Component\HttpFoundation\Response;

class ApiResponse implements Responsable
{
    protected int $statusCode;
    protected array $response;

    public function __construct(int $statusCode, array $meta = [])
    {
        $this->statusCode = $statusCode;
        if (!empty($meta)) {
            $this->response['meta'] = $meta;
        }
    }

    /**
     * Создаёт HTTP-ответ для запроса.
     *
     * @param [type] $request
     * @return Response
     */
    public  function toResponse($request): Response
    {
        return response()->json($this->response, $this->statusCode);
    }

    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}

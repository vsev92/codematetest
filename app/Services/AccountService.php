<?php

namespace App\Services;

use App\Exceptions\UserNotFoundException;
use App\Http\Responses\ApiDataResponse;
use App\Http\Responses\ApiResponse;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class AccountService
{
    public function getBalance(int $userId): ApiResponse
    {

        $user = User::find($userId);
        if (!$user) {
            throw new UserNotFoundException("Пользователь с ID {$userId} не найден", Response::HTTP_NOT_FOUND);
        }
        $message = 'остаток по счёту';
        return new ApiDataResponse(200, $user->account, compact('message'));
    }
}

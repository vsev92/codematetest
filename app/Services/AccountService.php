<?php

namespace App\Services;


use App\Models\Account;
use App\Http\Responses\ApiDataResponse;
use App\Http\Responses\ApiErrorsResponse;
use App\Http\Responses\ApiResponse;
use Throwable;

class AccountService
{
    public function getBalance(int $accountId): ApiResponse
    {
        try {
            $account = Account::findOrFail($accountId);
            $message = 'остаток по счёту';
            return new ApiDataResponse(200, $account, compact('message'));
        } catch (Throwable $e) {
            return new ApiErrorsResponse($e, ['message' => 'Ошибка транзакции']);
        }
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AccountService;

class AccountController extends Controller
{
    public function balance(int $userId, AccountService $service)
    {
        return $service->getBalance($userId);
    }
}

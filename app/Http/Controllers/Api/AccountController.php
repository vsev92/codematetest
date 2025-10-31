<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AccountService;

class AccountController extends Controller
{
    public function balance(User $user, AccountService $service)
    {
        return $service->getBalance($user->account->id);
    }
}

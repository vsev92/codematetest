<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionRequest;
use App\Models\User;
use App\Services\TransactionService;

class TransactionController extends Controller
{
    public function deposit(TransactionRequest $request, TransactionService $service)
    {
        $user = User::findOrFail($request->user_id);
        return $service->depositTransaction($user->account, $request->amount);
    }

    public function withdraw(TransactionRequest $request, TransactionService $service)
    {
        $user = User::findOrFail($request->user_id);
        return $service->withdrawTransaction($user->account, $request->amount);
    }

    public function transfer(TransactionRequest $request, TransactionService $service)
    {
        $userFrom = User::findOrFail($request->from_user_id);
        $userTo = User::findOrFail($request->to_user_id);
        return $service->transferTransaction($userFrom->account, $userTo->account, $request->amount);
    }
}

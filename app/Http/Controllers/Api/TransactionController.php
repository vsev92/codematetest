<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BalanceUpdateRequest;
use App\Http\Requests\TransferRequest;
use App\Models\User;
use App\Services\TransactionService;

class TransactionController extends Controller
{
    public function deposit(BalanceUpdateRequest $request, TransactionService $service)
    {
        $user = User::findOrFail($request->user_id);
        return $service->depositTransaction($user, $request->amount, $request->comment);
    }

    public function withdraw(BalanceUpdateRequest $request, TransactionService $service)
    {
        $user = User::findOrFail($request->user_id);
        return $service->withdrawTransaction($user->account, $request->amount, $request->comment);
    }

    public function transfer(TransferRequest $request, TransactionService $service)
    {
        $userFrom = User::findOrFail($request->from_user_id);
        $userTo = User::findOrFail($request->to_user_id);
        return $service->transferTransaction($userFrom->account, $userTo->account, $request->amount, $request->comment);
    }
}

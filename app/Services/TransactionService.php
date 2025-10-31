<?php

namespace App\Services;

use App\Models\Account;
use App\Exceptions\NegativeBalanceException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\TransactionType;
use App\Http\Responses\ApiDataResponse;
use App\Http\Responses\ApiErrorsResponse;
use App\Http\Responses\ApiResponse;
use Throwable;

class TransactionService
{

    /**
     * Пополняет счёт
     *
     * @param float
     * @return float         Новый баланс после пополнения
     */
    public function depositTransaction(Account $account, float $amount, string|null $transferId = null): ApiResponse
    {
        try {
            $result =  DB::transaction(fn() => $this->updateBalance($account->id, $amount, TransactionType::DEPOSIT, $transferId));
            $account->refresh();
            $message = 'пополнение счёта';
            $data = [
                'account' => $account->toArray(),
                'amount_deposited' => $amount,
            ];
            return new ApiDataResponse(200, $data, compact('message'));
        } catch (Throwable $e) {
            return new ApiErrorsResponse($e, ['message' => 'Ошибка транзакции']);
        }
    }

    /**
     * Списывает со счёта
     *
     * @param float
     * @return float         Новый баланс после списания
     */
    public function withdrawTransaction(Account $account, float $amount, string|null $transferId = null): ApiResponse
    {
        try {
            $result = DB::transaction(fn() => $this->updateBalance($account->id, -$amount, TransactionType::WITHDRAW, $transferId));
            $account->refresh();
            $message = 'списание со счёта';
            $data = [
                'account' => $account->toArray(),
                'amount_withdrawed' => $amount,
            ];
            return new ApiDataResponse(200, $data, compact('message'));
        } catch (Throwable $e) {
            return new ApiErrorsResponse($e, ['message' => 'Ошибка транзакции']);
        }
    }


    /**
     * Перевод
     *
     * @param float
     * @return array        Новый баланс после трансфера
     */
    public function transferTransaction(Account $account, Account $recipientsAccount, float $amount): ApiResponse
    {
        try {
            $data =  DB::transaction(function () use ($account, $amount, $recipientsAccount) {
                $transferId = (string) Str::uuid();
                $this->updateBalance($account->id, -$amount, TransactionType::WITHDRAW, $transferId);
                $this->updateBalance($recipientsAccount->id, $amount, TransactionType::DEPOSIT, $transferId);
                $account->refresh();
                $recipientsAccount->refresh();
                return [
                    'transfer_id'      => $transferId,
                    'amount_transfered' => $amount,
                    'sender'   => $account,
                    'recipient' => $recipientsAccount,
                ];
            });
            $message = 'перевод между счетами';
            return new ApiDataResponse(200, $data, compact('message'));
        } catch (Throwable $e) {
            return new ApiErrorsResponse($e, ['message' => 'Ошибка транзакции']);
        }
    }


    private function updateBalance(int $accountId, float $amount, string $transactionType, ?string $transferId): float
    {
        $account = Account::where('id', $accountId)
            ->lockForUpdate()
            ->firstOrFail();

        $newAmount = $account->amount + $amount;
        if ($newAmount < 0) {
            throw new NegativeBalanceException();
        }

        $account->amount = $newAmount;
        $account->save();

        $account->transactions()->create([
            'type_id' => $transactionType,
            'amount' => abs($amount),
            'transfer_id' => $transferId
        ]);

        return $account->amount;
    }
}

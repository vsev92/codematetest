<?php

namespace App\Services;

use App\Models\Account;
use App\Exceptions\NegativeBalanceException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\TransactionType;
use App\Http\Responses\ApiDataResponse;

class TransactionService
{

    /**
     * Пополняет счёт
     *
     * @param float
     * @return float         Новый баланс после пополнения
     */
    public function depositTransaction(Account $account, float $amount, ?string $comment, string|null $transferId = null): ApiDataResponse
    {
        $result =  DB::transaction(fn() => $this->updateBalance($account->id, $amount, TransactionType::DEPOSIT, $comment, $transferId));
        $account->refresh();
        $message = 'пополнение счёта';
        $data = [
            'account' => $account->toArray(),
            'amount_deposited' => $amount,
            'comment' => $comment
        ];
        return new ApiDataResponse(200, $data, compact('message'));
    }

    /**
     * Списывает со счёта
     *
     * @param float
     * @return float         Новый баланс после списания
     */
    public function withdrawTransaction(Account $account, float $amount, ?string $comment, string|null $transferId = null): ApiDataResponse
    {

        $result = DB::transaction(fn() => $this->updateBalance($account->id, -$amount, TransactionType::WITHDRAW, $comment, $transferId));
        $account->refresh();
        $message = 'списание со счёта';
        $data = [
            'account' => $account->toArray(),
            'amount_withdrawed' => $amount,
            'comment' => $comment

        ];
        return new ApiDataResponse(200, $data, compact('message'));
    }


    /**
     * Перевод
     *
     * @param float
     * @return array        Новый баланс после трансфера
     */
    public function transferTransaction(Account $account, Account $recipientsAccount, float $amount, ?string $comment): ApiDataResponse
    {
        $data =  DB::transaction(function () use ($account, $amount, $recipientsAccount, $comment) {
            $transferId = (string) Str::uuid();
            $this->updateBalance($account->id, -$amount, TransactionType::WITHDRAW, $comment, $transferId);
            $this->updateBalance($recipientsAccount->id, $amount, TransactionType::DEPOSIT, $comment, $transferId);
            $account->refresh();
            $recipientsAccount->refresh();
            return [
                'transfer_id'      => $transferId,
                'amount_transfered' => $amount,
                'sender'   => $account,
                'recipient' => $recipientsAccount,
                'comment' => $comment
            ];
        });
        $message = 'перевод между счетами';
        return new ApiDataResponse(200, $data, compact('message'));
    }


    private function updateBalance(int $accountId, float $amount, string $transactionType, ?string $comment, ?string $transferId): float
    {
        $account = Account::where('id', $accountId)
            ->lockForUpdate()
            ->firstOrFail();

        $transactionTypeId = TransactionType::getIdByName($transactionType);

        $newAmount = $account->amount + $amount;
        if ($newAmount < 0) {
            throw new NegativeBalanceException();
        }

        $account->amount = $newAmount;
        $account->save();

        $account->transactions()->create([
            'type_id' => $transactionTypeId,
            'amount' => abs($amount),
            'transfer_id' => $transferId,
            'comment' => $comment
        ]);

        return $account->amount;
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Account;
use App\Models\TransactionType;
use App\Models\Transaction;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user1 = User::factory()->create([
            'name' => 'User One',
            'email' => 'user1@example.com',
        ]);

        $user2 = User::factory()->create([
            'name' => 'User Two',
            'email' => 'user2@example.com',
        ]);

        $account1 = Account::factory()->create([
            'user_id' => $user1->id,
            'amount' => 1000.00,
        ]);

        $account2 = Account::factory()->create([
            'user_id' => $user2->id,
            'amount' => 500.00,
        ]);

        $types = [
            TransactionType::DEPOSIT,
            TransactionType::WITHDRAW,
            TransactionType::TRANSFER,
        ];

        foreach ($types as $type) {
            TransactionType::firstOrCreate(['name' => $type]);
        }

        Transaction::factory()->create([
            'account_id' => $account1->id,
            'type_id' => TransactionType::where('name', TransactionType::DEPOSIT)->first()->id,
            'amount' => 200.00,
        ]);

        Transaction::factory()->create([
            'account_id' => $account1->id,
            'type_id' => TransactionType::where('name', TransactionType::WITHDRAW)->first()->id,
            'amount' => 100.00,
        ]);

        $transferId = Str::uuid();

        Transaction::factory()->create([
            'account_id' => $account1->id,
            'type_id' => TransactionType::where('name', TransactionType::TRANSFER)->first()->id,
            'amount' => 150.00,
            'transfer_id' => $transferId,
        ]);

        Transaction::factory()->create([
            'account_id' => $account2->id,
            'type_id' => TransactionType::where('name', TransactionType::TRANSFER)->first()->id,
            'amount' => 150.00,
            'transfer_id' => $transferId,
        ]);
    }
}

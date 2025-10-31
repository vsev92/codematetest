<?php

namespace Database\Seeders;

use App\Models\TransactionType;
use Illuminate\Database\Seeder;

class TransactionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TransactionType::insert([
            ['name' => TransactionType::DEPOSIT],
            ['name' => TransactionType::WITHDRAW],
            ['name' => TransactionType::TRANSFER],
        ]);
    }
}

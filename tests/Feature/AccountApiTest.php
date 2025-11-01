<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Account;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\TransactionType;

class AccountApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $user1;
    protected User $user2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user1 = User::factory()->create();
        $this->user2 = User::factory()->create();

        Account::factory()->create([
            'user_id' => $this->user1->id,
            'amount'  => 0,
        ]);

        Account::factory()->create([
            'user_id' => $this->user2->id,
            'amount'  => 0,
        ]);

        $types = [
            TransactionType::DEPOSIT,
            TransactionType::WITHDRAW,
            TransactionType::TRANSFER,
        ];

        foreach ($types as $type) {
            TransactionType::firstOrCreate(['name' => $type]);
        }
    }



    public function testGetBalance()
    {
        $this->user1->account->update(['amount' => 350]);

        $response = $this->getJson("/api/balance/{$this->user1->id}");

        $response->assertStatus(200)
            ->assertJsonFragment([
                'balance' => 350,
            ]);
    }


    public function testErrorNonExistentUser()
    {
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->get('/api/balance/999');

        $response->assertStatus(404)
            ->assertJson([
                'error' => 'Пользователь с ID 999 не найден'
            ]);
    }
}

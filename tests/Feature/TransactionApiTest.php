<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Account;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransactionApiTest extends TestCase
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
    }

    public function testDiposit()
    {
        $response = $this->postJson('/api/deposit', [
            'user_id' => $this->user1->id,
            'amount' => 500,
            'comment' => 'Пополнение через карту',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'account' => ['user_id', 'amount'],
                    'amount_deposited',
                ],
                'meta' => ['message'],
            ]);

        $this->assertDatabaseHas('accounts', [
            'user_id' => $this->user1->id,
            'amount' => 500,
        ]);
    }


    public function testWithdraw()
    {

        $this->user1->account->update(['amount' => 500]);

        $response = $this->postJson('/api/withdraw', [
            'user_id' => $this->user1->id,
            'amount' => 200,
            'comment' => 'Покупка подписки',
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'amount_withdrawed' => 200,
            ]);

        $this->assertDatabaseHas('accounts', [
            'user_id' => $this->user1->id,
            'amount' => 300,
        ]);
    }


    public function testCannotWithdrawMoreThanBalance()
    {
        $this->user1->account->update(['amount' => 100]);

        $response = $this->postJson('/api/withdraw', [
            'user_id' => $this->user1->id,
            'amount' => 200,
        ]);

        $response->assertStatus(409)
            ->assertJsonFragment([
                'message' => 'Недостаточно средств на счете',
            ]);
    }

    public function testTransfer()
    {
        $this->user1->account->update(['amount' => 500]);

        $response = $this->postJson('/api/transfer', [
            'from_user_id' => $this->user1->id,
            'to_user_id' => $this->user2->id,
            'amount' => 150,
            'comment' => 'Перевод другу',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'transfer_id',
                    'amount_transfered',
                    'sender' => ['user_id', 'amount',],
                    'recipient' => ['user_id', 'amount'],
                ],
                'meta' => ['message'],
            ]);

        $this->assertDatabaseHas('accounts', [
            'user_id' => $this->user1->id,
            'amount' => 350,
        ]);

        $this->assertDatabaseHas('accounts', [
            'user_id' => $this->user2->id,
            'amount' => 150,
        ]);
    }
}

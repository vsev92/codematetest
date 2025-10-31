<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Account;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
}

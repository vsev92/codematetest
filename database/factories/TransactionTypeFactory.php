<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\TransactionType;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TransactionStatus>
 */
class TransactionTypeFactory extends Factory
{
    protected $model = TransactionType::class;

    public function definition(): array
    {
        $types = [
            TransactionType::DEPOSIT,
            TransactionType::WITHDRAW,
            TransactionType::TRANSFER,
        ];

        return [
            'name' => $this->faker->unique()->randomElement($types),
        ];
    }
}

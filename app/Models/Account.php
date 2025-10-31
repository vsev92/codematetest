<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Account extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'amount'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function toArray(): array
    {
        return [
            'user_id' => $this->user_id,
            'balance' => $this->amount,
        ];
    }
}

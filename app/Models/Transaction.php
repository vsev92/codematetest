<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['account_id', 'type_id', 'amount'];


    public function account()
    {
        return $this->belongsTo(Account::class);
    }


    public function type()
    {
        return $this->belongsTo(TransactionType::class, 'type_id');
    }
}

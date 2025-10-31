<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionType extends Model
{
    use HasFactory;

    public const DEPOSIT  = 'deposit';
    public const WITHDRAW = 'withdraw';
    public const TRANSFER = 'transfer';


    protected $fillable = ['name'];

    public static function getIdByName(string $name): int
    {
        $type = self::where('name', $name)->firstOrFail();
        return $type->id;
    }
}

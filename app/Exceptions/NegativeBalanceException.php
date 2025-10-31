<?php

namespace App\Exceptions;

class NegativeBalanceException extends ApiException
{
    public function __construct()
    {
        parent::__construct('Недостаточно средств для списания', 409);
    }
}

<?php
namespace App\Mappers;

use App\Models\Transaction;

class TransactionMapper
{
    public function toModel(array $data)
    {
        return new Transaction($data);
    }
    
    public function toArray(Transaction $transaction)
    {
        return $transaction->toArray();
    }
}

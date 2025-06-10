<?php
namespace App\Models;

class Client extends Model
{
    public function getFullAddress()
    {
        return $this->address ?? 'No address provided';
    }
    
    public function getBalance()
    {
        // This would typically be calculated from transactions
        // We'll implement this later when we have the transaction repository
        return 0;
    }
}

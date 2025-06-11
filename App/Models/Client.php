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
        return 0;
    }
}

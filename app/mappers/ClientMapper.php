<?php
namespace App\Mappers;

use App\Models\Client;

class ClientMapper
{
    public function toModel(array $data)
    {
        return new Client($data);
    }
    
    public function toArray(Client $client)
    {
        return $client->toArray();
    }
}

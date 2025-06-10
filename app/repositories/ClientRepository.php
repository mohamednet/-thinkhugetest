<?php
namespace App\Repositories;

use App\Core\Database;
use App\Models\Client;
use App\Repositories\Interfaces\ClientRepositoryInterface;
use App\Mappers\ClientMapper;

class ClientRepository implements ClientRepositoryInterface
{
    private $db;
    private $mapper;
    
    public function __construct(Database $db)
    {
        $this->db = $db;
        $this->mapper = new ClientMapper();
    }
    
    public function findAll()
    {
        $rows = $this->db->fetchAll("SELECT * FROM clients ORDER BY name");
        return array_map([$this->mapper, 'toModel'], $rows);
    }
    
    public function findById($id)
    {
        $row = $this->db->fetch("SELECT * FROM clients WHERE id = ?", [$id]);
        
        if (!$row) {
            return null;
        }
        
        return $this->mapper->toModel($row);
    }
    
    public function findByName($name)
    {
        $rows = $this->db->fetchAll(
            "SELECT * FROM clients WHERE name LIKE ? ORDER BY name",
            ["%$name%"]
        );
        
        return array_map([$this->mapper, 'toModel'], $rows);
    }
    
    public function findByEmail($email)
    {
        $row = $this->db->fetch("SELECT * FROM clients WHERE email = ?", [$email]);
        
        if (!$row) {
            return null;
        }
        
        return $this->mapper->toModel($row);
    }
    
    public function create(array $data)
    {
        $id = $this->db->insert('clients', $data);
        return $this->findById($id);
    }
    
    public function update($id, array $data)
    {
        $this->db->update('clients', $data, 'id = ?', [$id]);
        return $this->findById($id);
    }
    
    public function delete($id)
    {
        return $this->db->delete('clients', 'id = ?', [$id]);
    }
    
    public function count()
    {
        $result = $this->db->fetch("SELECT COUNT(*) as count FROM clients");
        return $result ? (int)$result['count'] : 0;
    }
    
    public function findRecent($limit = 5)
    {
        $rows = $this->db->fetchAll(
            "SELECT * FROM clients ORDER BY created_at DESC LIMIT ?", 
            [$limit]
        );
        return array_map([$this->mapper, 'toModel'], $rows);
    }
}

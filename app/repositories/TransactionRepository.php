<?php
namespace App\Repositories;

use App\Core\Database;
use App\Models\Transaction;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use App\Mappers\TransactionMapper;

class TransactionRepository implements TransactionRepositoryInterface
{
    private $db;
    private $mapper;
    
    public function __construct(Database $db)
    {
        $this->db = $db;
        $this->mapper = new TransactionMapper();
    }
    
    public function findAll()
    {
        $rows = $this->db->fetchAll("SELECT * FROM transactions ORDER BY date DESC");
        return array_map([$this->mapper, 'toModel'], $rows);
    }
    
    public function findById($id)
    {
        $row = $this->db->fetch("SELECT * FROM transactions WHERE id = ?", [$id]);
        
        if (!$row) {
            return null;
        }
        
        return $this->mapper->toModel($row);
    }
    
    public function findByClientId($clientId)
    {
        $rows = $this->db->fetchAll(
            "SELECT * FROM transactions WHERE client_id = ? ORDER BY date DESC",
            [$clientId]
        );
        
        return array_map([$this->mapper, 'toModel'], $rows);
    }
    
    public function findByType($type)
    {
        $rows = $this->db->fetchAll(
            "SELECT * FROM transactions WHERE type = ? ORDER BY date DESC",
            [$type]
        );
        
        return array_map([$this->mapper, 'toModel'], $rows);
    }
    
    public function findByDateRange($startDate, $endDate)
    {
        $rows = $this->db->fetchAll(
            "SELECT * FROM transactions WHERE date BETWEEN ? AND ? ORDER BY date DESC",
            [$startDate, $endDate]
        );
        
        return array_map([$this->mapper, 'toModel'], $rows);
    }
    
    public function getClientBalance($clientId)
    {
        $result = $this->db->fetch(
            "SELECT 
                SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as income,
                SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as expenses
            FROM transactions 
            WHERE client_id = ?",
            [$clientId]
        );
        
        $income = (float) ($result['income'] ?? 0);
        $expenses = (float) ($result['expenses'] ?? 0);
        
        return $income - $expenses;
    }
    
    public function create(array $data)
    {
        $id = $this->db->insert('transactions', $data);
        return $this->findById($id);
    }
    
    public function update($id, array $data)
    {
        $this->db->update('transactions', $data, 'id = ?', [$id]);
        return $this->findById($id);
    }
    
    public function delete($id)
    {
        return $this->db->delete('transactions', 'id = ?', [$id]);
    }
    
    public function getTotalByType($type)
    {
        $result = $this->db->fetch(
            "SELECT SUM(amount) as total FROM transactions WHERE type = ?",
            [$type]
        );
        
        return (float) ($result['total'] ?? 0);
    }
    
    public function findRecent($limit = 5)
    {
        $rows = $this->db->fetchAll(
            "SELECT t.*, c.name as client_name 
            FROM transactions t 
            LEFT JOIN clients c ON t.client_id = c.id 
            ORDER BY t.created_at DESC LIMIT ?", 
            [$limit]
        );
        return array_map([$this->mapper, 'toModel'], $rows);
    }
}

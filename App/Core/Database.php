<?php
namespace App\Core;

class Database
{
    private $connection;
    private static $instance;
    
    public function __construct()
    {
        $host = getenv('DB_HOST') ?: 'localhost';
        $port = getenv('DB_PORT') ?: '3306';
        $database = getenv('DB_DATABASE') ?: 'finance_app';
        $username = getenv('DB_USERNAME') ?: 'root';
        $password = getenv('DB_PASSWORD') ?: '';
        
        try {
            $this->connection = new \PDO(
                "mysql:host=$host;port=$port;dbname=$database",
                $username,
                $password,
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                    \PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch (\PDOException $e) {
            throw new \Exception("Database connection failed: " . $e->getMessage());
        }
    }
    
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }
    
    public function getConnection()
    {
        return $this->connection;
    }
    
    public function query($sql, $params = [])
    {
        $statement = $this->connection->prepare($sql);
        $statement->execute($params);
        
        return $statement;
    }
    
    public function fetch($sql, $params = [])
    {
        $statement = $this->query($sql, $params);
        return $statement->fetch();
    }
    
    public function fetchAll($sql, $params = [])
    {
        $statement = $this->query($sql, $params);
        return $statement->fetchAll();
    }
    
    public function insert($table, $data)
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        
        $this->query($sql, array_values($data));
        
        return $this->connection->lastInsertId();
    }
    
    public function update($table, $data, $where, $whereParams = [])
    {
        $set = [];
        foreach (array_keys($data) as $column) {
            $set[] = "$column = ?";
        }
        
        $sql = "UPDATE $table SET " . implode(', ', $set) . " WHERE $where";
        
        $params = array_merge(array_values($data), $whereParams);
        
        $this->query($sql, $params);
    }
    
    public function delete($table, $where, $params = [])
    {
        $sql = "DELETE FROM $table WHERE $where";
        $this->query($sql, $params);
    }
}

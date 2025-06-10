<?php
namespace App\Core;

class ServiceContainer
{
    private $services = [];
    private $instances = [];
    
    public function register($name, $callback)
    {
        $this->services[$name] = $callback;
    }
    
    public function get($name)
    {
        if (!isset($this->services[$name])) {
            throw new \Exception("Service '$name' not found in container");
        }
        
        if (!isset($this->instances[$name])) {
            $this->instances[$name] = call_user_func($this->services[$name]);
        }
        
        return $this->instances[$name];
    }
    
    public function has($name)
    {
        return isset($this->services[$name]);
    }
}

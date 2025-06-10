<?php
namespace App\Models;

abstract class Model
{
    protected $attributes = [];
    
    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
    }
    
    public function fill(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            $this->attributes[$key] = $value;
        }
    }
    
    public function __get($name)
    {
        return $this->attributes[$name] ?? null;
    }
    
    public function __set($name, $value)
    {
        $this->attributes[$name] = $value;
    }
    
    public function __isset($name)
    {
        return isset($this->attributes[$name]);
    }
    
    public function toArray()
    {
        return $this->attributes;
    }
}

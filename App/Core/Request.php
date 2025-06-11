<?php
namespace App\Core;

class Request
{
    public function getPath()
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        
        // Remove query string if present
        $position = strpos($path, '?');
        if ($position !== false) {
            $path = substr($path, 0, $position);
        }

        
        // First, normalize the path by removing both testv1thinkhug and public parts
        $projectRoot = '/testv1thinkhug';
        $publicDir = '/public';
        
        if (strpos($path, $projectRoot) === 0) {
            $path = substr($path, strlen($projectRoot));

        }
        
        if (strpos($path, $publicDir) === 0) {
            $path = substr($path, strlen($publicDir));

        }
        
        // Handle direct access to PHP files
        if (strpos($path, '.php') !== false) {
            $parts = explode('.php', $path);
            if (count($parts) > 1) {
                $path = $parts[1];
            } else {
                $path = '/';
            }

        }
        
        if (empty($path) || $path[0] !== '/') {
            $path = '/' . $path;
        }
        
        $path = preg_replace('#/+#', '/', $path);
        

        
        return $path;
    }
    
    public function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }
    
    public function getBody()
    {
        $body = [];
        
        if ($this->getMethod() === 'GET') {
            foreach ($_GET as $key => $value) {
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        
        if ($this->getMethod() === 'POST') {
            foreach ($_POST as $key => $value) {
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        
        return $body;
    }
    
    public function get($key, $default = null)
    {
        $body = $this->getBody();
        return $body[$key] ?? $default;
    }
    
    public function isGet()
    {
        return $this->getMethod() === 'GET';
    }
    
    public function isPost()
    {
        return $this->getMethod() === 'POST';
    }
}

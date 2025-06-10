<?php
namespace App\Core;

class Router
{
    private $routes = [
        'GET' => [],
        'POST' => []
    ];
    
    private $request;
    private $response;
    private $notFoundCallback;
    
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }
    
    public function get($path, $callback)
    {
        // Normalize the path to ensure consistent matching
        $path = $this->normalizePath($path);
        $this->routes['GET'][$path] = $callback;
        return $this;
    }
    
    public function post($path, $callback)
    {
        // Normalize the path to ensure consistent matching
        $path = $this->normalizePath($path);
        $this->routes['POST'][$path] = $callback;
        return $this;
    }
    
    /**
     * Normalize a path to ensure consistent matching
     * 
     * @param string $path The path to normalize
     * @return string The normalized path
     */
    private function normalizePath($path)
    {
        // Ensure path starts with a slash
        if (empty($path) || $path[0] !== '/') {
            $path = '/' . $path;
        }
        
        // Remove trailing slash except for root path
        if ($path !== '/' && substr($path, -1) === '/') {
            $path = rtrim($path, '/');
        }
        
        return $path;
    }
    
    public function notFound($callback)
    {
        $this->notFoundCallback = $callback;
        return $this;
    }
    
    public function resolve()
    {
        $method = $this->request->getMethod();
        $path = $this->request->getPath();
        
        // Normalize the request path for consistent matching
        $path = $this->normalizePath($path);
        
        // Add debugging to see what's happening
        echo "<div style='background:#f8f9fa;padding:10px;margin:10px;border:1px solid #ddd;'>"; 
        echo "<strong>Router Debug:</strong> Method: {$method}, Normalized Path: {$path}<br>";
        echo "<strong>Routes registered:</strong><br>";
        foreach ($this->routes[$method] as $route => $callback) {
            echo "Route: {$route}<br>";
        }
        echo "</div>";
        
        // Check if route exists (exact match)
        if (isset($this->routes[$method][$path])) {
            echo "<div style='background:#e8f5e9;padding:5px;margin:5px;border:1px solid #4caf50;'>";
            echo "<strong style='color:green'>EXACT MATCH FOUND!</strong> Path: {$path}<br>";
            echo "</div>";
            $callback = $this->routes[$method][$path];
            return $this->executeCallback($callback);
        }

        // Check for dynamic routes with parameters
        foreach ($this->routes[$method] as $route => $callback) {
            // Convert route pattern to regex
            $pattern = preg_replace('/{([a-zA-Z0-9_]+)}/', '([^/]+)', $route);
            $pattern = "#^$pattern$#";
            
            echo "<div style='background:#f8f9fa;padding:5px;margin:5px;border:1px solid #ddd;'>";
            echo "Checking if path: <strong>{$path}</strong> matches pattern: <strong>{$pattern}</strong><br>";
            
            if (preg_match($pattern, $path, $matches)) {
                echo "<strong style='color:green'>DYNAMIC MATCH FOUND!</strong><br>";
                echo "</div>";
                array_shift($matches); // Remove the first match (full string)
                return $this->executeCallback($callback, $matches);
            }
            echo "</div>";
        }
        
        // Route not found - try to find a similar route for debugging
        echo "<div style='background:#ffebee;padding:10px;margin:10px;border:1px solid #f44336;'>"; 
        echo "<strong>No route found for:</strong> {$path}<br>";
        echo "<strong>Available routes:</strong><br>";
        foreach ($this->routes[$method] as $route => $callback) {
            echo "- {$route}<br>";
        }
        echo "</div>";
        
        // Route not found
        if ($this->notFoundCallback) {
            return call_user_func($this->notFoundCallback);
        }
        
        // Default 404 response
        $this->response->setStatusCode(404);
        return $this->response->setContent('404 Page Not Found');
    }
    
    private function executeCallback($callback, $params = [])
    {
        if (is_string($callback)) {
            
            // Format: "ControllerName@methodName"
            list($controller, $method) = explode('@', $callback);
            $controller = "App\\Controllers\\$controller";
            
            if (!class_exists($controller)) {
                throw new \Exception("Controller $controller not found");
            }
            
            $controllerInstance = new $controller();
            
            if (!method_exists($controllerInstance, $method)) {
                throw new \Exception("Method $method not found in controller $controller");
            }
            
            return call_user_func_array([$controllerInstance, $method], $params);
        }
        
        return call_user_func_array($callback, $params);
    }
}

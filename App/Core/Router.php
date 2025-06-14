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
        $path = $this->normalizePath($path);
        $this->routes['GET'][$path] = $callback;
        return $this;
    }
    
    public function post($path, $callback)
    {
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
        if (empty($path) || $path[0] !== '/') {
            $path = '/' . $path;
        }
        
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
        
        $path = $this->normalizePath($path);
        
        
        
        if (isset($this->routes[$method][$path])) {
            $callback = $this->routes[$method][$path];
            return $this->executeCallback($callback);
        }

        foreach ($this->routes[$method] as $route => $callback) {
            $pattern = preg_replace('/{([a-zA-Z0-9_]+)}/', '([^/]+)', $route);
            $pattern = "#^$pattern$#";
            
            if (preg_match($pattern, $path, $matches)) {
                array_shift($matches); // Remove the first match (full string)
                return $this->executeCallback($callback, $matches);
            }
            echo "</div>";
        }
        
        // Route not found
        if ($this->notFoundCallback) {
            return call_user_func($this->notFoundCallback);
        }
        
        $this->response->setStatusCode(404);
        return $this->response->setContent('404 Page Not Found');
    }
    
    private function executeCallback($callback, $params = [])
    {
        if (is_string($callback)) {
            
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

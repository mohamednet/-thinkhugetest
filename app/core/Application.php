<?php
namespace App\Core;

class Application
{
    private static $instance;
    private $router;
    private $request;
    private $response;
    private $container;
    
    public function __construct()
    {
        self::$instance = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
        $this->container = new ServiceContainer();
        
        $this->registerServices();
    }
    
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getRouter()
    {
        return $this->router;
    }
    
    public function getRequest()
    {
        return $this->request;
    }
    
    public function getResponse()
    {
        return $this->response;
    }
    
    public function getContainer()
    {
        return $this->container;
    }
    
    private function registerServices()
    {
        // Register core services
        $this->container->register('db', function() {
            return new Database();
        });
    }
    
    public function run()
    {
        // Process the request through the router
        $this->router->resolve();
    }
}

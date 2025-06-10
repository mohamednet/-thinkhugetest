<?php
namespace App\Core;

abstract class Controller
{
    protected $request;
    protected $response;
    protected $container;
    
    public function __construct()
    {
        $app = Application::getInstance();
        $this->request = $app->getRequest();
        $this->response = $app->getResponse();
        $this->container = $app->getContainer();
    }
    
    protected function render($view, $data = [])
    {
        $content = view($view, $data);
        $this->response->setContent($content);
        return $this->response->send();
    }
    
    protected function json($data)
    {
        return $this->response->json($data);
    }
    
    protected function redirect($url)
    {
        return $this->response->redirect($url);
    }
    
    protected function isAuthenticated()
    {
        return isset($_SESSION['user_id']);
    }
    
    protected function requireAuth()
    {
        if (!$this->isAuthenticated()) {
            flash('error', 'You must be logged in to access this page');
            $this->redirect(url('login'));
        }
    }
}

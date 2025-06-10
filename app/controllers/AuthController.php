<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Services\AuthService;

class AuthController extends Controller
{
    private $authService;
    
    public function __construct()
    {
        parent::__construct();
        $this->authService = new AuthService($this->container->get('db'));
    }
    
    public function showLogin()
    {
        // If already logged in, redirect to dashboard
        if ($this->isAuthenticated()) {
            return $this->redirect(url(''));
        }
        
        return $this->render('auth/login');
    }
    
    public function login()
    {
        // Validate CSRF token
        $token = $this->request->get('csrf_token');
        if (!verify_csrf_token($token)) {
            flash('error', 'Invalid CSRF token');
            return $this->redirect(url('login'));
        }
        
        $username = $this->request->get('username');
        $password = $this->request->get('password');
        
        // Store form data for repopulation on error
        $_SESSION['old'] = [
            'username' => $username
        ];
        
        // Validate input
        if (empty($username) || empty($password)) {
            flash('error', 'Username and password are required');
            return $this->redirect(url('login'));
        }
        
        // Attempt login
        $user = $this->authService->login($username, $password);
        
        if ($user) {
            // Clear old input
            unset($_SESSION['old']);
            
            // Set session data
            $_SESSION['user_id'] = $user->id;
            $_SESSION['admin_name'] = $user->name;
            
            // Redirect to dashboard
            return $this->redirect(url(''));
        } else {
            flash('error', 'Invalid username or password');
            return $this->redirect(url('login'));
        }
    }
    
    public function logout()
    {
        // Clear session data
        session_unset();
        session_destroy();
        
        // Redirect to login page
        return $this->redirect(url('login'));
    }
}

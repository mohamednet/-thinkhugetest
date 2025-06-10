<?php
/**
 * Helper functions for the application
 */

if (!function_exists('view')) {
    /**
     * Render a view
     * 
     * @param string $view View name
     * @param array $data Data to pass to the view
     * @return string
     */
    function view($view, $data = [])
    {
        // Extract data to make variables available in the view
        extract($data);
        
        // Start output buffering
        ob_start();
        
        // Include the view file
        $viewPath = APP_ROOT . "/app/views/$view.php";
        
        if (!file_exists($viewPath)) {
            throw new Exception("View $view not found");
        }
        
        include $viewPath;
        
        // Get the buffered content
        $content = ob_get_clean();
        
        return $content;
    }
}

if (!function_exists('url')) {
    /**
     * Generate a URL with the subdirectory prefix
     * 
     * @param string $path Path to generate URL for
     * @return string Full URL
     */
    function url($path = '')
    {
        // Remove leading slash if present
        $path = ltrim($path, '/');
        
        // Get base URL from environment or use hardcoded value as fallback
        $baseUrl = getenv('APP_URL') ?: '/testv1thinkhug';
        
        // Return URL with subdirectory prefix
        return rtrim($baseUrl, '/') . '/' . $path;
    }
}

if (!function_exists('redirect')) {
    /**
     * Redirect to a URL
     * 
     * @param string $url URL to redirect to
     * @return void
     */
    function redirect($url)
    {
        // Use the url helper to ensure subdirectory is included
        if (!preg_match('#^https?://#i', $url)) {
            $url = url($url);
        }
        
        header("Location: $url");
        exit;
    }
}

if (!function_exists('csrf_token')) {
    /**
     * Generate or get CSRF token
     * 
     * @return string
     */
    function csrf_token()
    {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('csrf_field')) {
    /**
     * Generate CSRF field
     * 
     * @return string
     */
    function csrf_field()
    {
        return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
    }
}

if (!function_exists('verify_csrf_token')) {
    /**
     * Verify CSRF token
     * 
     * @param string $token Token to verify
     * @return bool
     */
    function verify_csrf_token($token)
    {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
}

if (!function_exists('old')) {
    /**
     * Get old input value
     * 
     * @param string $key Input key
     * @param mixed $default Default value
     * @return mixed
     */
    function old($key, $default = '')
    {
        return $_SESSION['old'][$key] ?? $default;
    }
}

if (!function_exists('flash')) {
    /**
     * Flash a message
     * 
     * @param string $key Message key
     * @param mixed $value Message value
     * @return void
     */
    function flash($key, $value)
    {
        $_SESSION['flash'][$key] = $value;
    }
}

if (!function_exists('get_flash')) {
    /**
     * Get a flashed message
     * 
     * @param string $key Message key
     * @param mixed $default Default value
     * @return mixed
     */
    function get_flash($key, $default = '')
    {
        $value = $_SESSION['flash'][$key] ?? $default;
        unset($_SESSION['flash'][$key]);
        
        return $value;
    }
}

if (!function_exists('has_flash')) {
    /**
     * Check if a flashed message exists
     * 
     * @param string $key Message key
     * @return bool
     */
    function has_flash($key)
    {
        return isset($_SESSION['flash'][$key]);
    }
}

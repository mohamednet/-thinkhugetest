<?php
namespace App\Core;

class Response
{
    private $statusCode = 200;
    private $headers = [];
    private $content = '';
    
    public function setStatusCode($code)
    {
        $this->statusCode = $code;
        http_response_code($code);
        return $this;
    }
    
    public function redirect($url)
    {
        // If the URL doesn't already have the protocol (http:// or https://)
        // and doesn't already use the url() helper, use the url() helper to generate the correct URL
        if (!preg_match('#^https?://#i', $url) && strpos($url, url('')) === false) {
            // Remove leading slash if present since url() will add it
            if (substr($url, 0, 1) === '/') {
                $url = substr($url, 1);
            }
            
            // Use the url() helper to generate the correct URL with subdirectory
            $url = url($url);
        }
        
        // Debug output (can be removed in production)
        echo "<div style='background:#f0f8ff;padding:5px;margin:5px;border:1px solid #ccc;'>"; 
        echo "<strong>Redirect Debug:</strong> Redirecting to {$url}<br>"; 
        echo "</div>";
        
        header("Location: $url");
        exit;
    }
    
    public function setHeader($name, $value)
    {
        $this->headers[$name] = $value;
        header("$name: $value");
        return $this;
    }
    
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }
    
    public function json($data)
    {
        // Clear any previous output
        if (ob_get_length() > 0) {
            ob_clean();
        }
        
        // Set proper JSON headers
        header('Content-Type: application/json');
        header('Cache-Control: no-cache, must-revalidate');
        
        // Encode and output the JSON data
        echo json_encode($data, JSON_PRETTY_PRINT);
        exit; // Terminate execution to prevent any additional output
    }
    
    public function send()
    {
        echo $this->content;
        return $this;
    }
}

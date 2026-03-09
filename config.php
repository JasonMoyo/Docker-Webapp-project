<?php
// Enable error reporting for debugging (disable in production)
if (getenv('APP_ENV') === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Load environment variables from .env file
$envFile = __DIR__ . '/.env';

if (file_exists($envFile)) {
    $dotenv = file_get_contents($envFile);
    $lines = explode("\n", $dotenv);
    
    foreach ($lines as $line) {
        $line = trim($line);
        // Skip empty lines and comments
        if (empty($line) || strpos($line, '#') === 0) {
            continue;
        }
        
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            
            // Remove any quotes if present
            $value = trim($value, '"\'');
            
            // Set environment variable
            putenv("$key=$value");
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }
    }
} else {
    // Log that .env file is missing (optional)
    error_log("Warning: .env file not found at " . $envFile);
}

// Database configuration - Use environment variables
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASSWORD') ?: '');
define('DB_NAME', getenv('DB_NAME') ?: 'nqobileq_db');

// Owner contact information
define('OWNER_PHONE', '+27782280408');
define('OWNER_EMAIL', getenv('OWNER_EMAIL') ?: 'thabani070801@gmail.com');

// Create connection
function getDB() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Check connection
    if ($conn->connect_error) {
        // For local WampServer, don't try Docker connection
        // Just show the error
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Set charset to UTF-8
    $conn->set_charset("utf8");
    
    return $conn;
}

// Start session if not started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Optional: Add debug code temporarily to verify .env is loading
// Uncomment the lines below to debug
/*
echo "<!-- DEBUG INFO -->\n";
echo "<!-- SMTP_USERNAME: " . (getenv('SMTP_USERNAME') ? 'SET' : 'NOT SET') . " -->\n";
echo "<!-- SMTP_PASSWORD: " . (getenv('SMTP_PASSWORD') ? 'SET' : 'NOT SET') . " -->\n";
echo "<!-- DB_HOST: " . DB_HOST . " -->\n";
*/
?>
<?php
// =====================================================
// Database Configuration
// Academic Performance Evaluation System
// =====================================================

// Database Connection Parameters
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');  // Change to your database password
define('DB_NAME', 'academic_db');
define('DB_PORT', 3306);

// Error Handling
define('DISPLAY_ERRORS', true);
error_reporting(E_ALL);
ini_set('display_errors', DISPLAY_ERRORS);

// Application Settings
define('APP_NAME', 'Academic Performance Evaluation System');
define('APP_VERSION', '1.0.0');
define('BASE_URL', 'http://localhost/SC Project/');

// Database Connection Function
function getDBConnection() {
    try {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
        
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }
        
        // Set charset to UTF-8
        $conn->set_charset("utf8mb4");
        
        return $conn;
    } catch (Exception $e) {
        die("Database Error: " . $e->getMessage());
    }
}

// Close Database Connection
function closeDBConnection($conn) {
    if ($conn) {
        $conn->close();
    }
}

// CORS Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

// Set timezone
date_default_timezone_set('UTC');

// Session Configuration
session_start();

?>

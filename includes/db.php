<?php
// includes/db.php
$host = 'localhost';
$dbname = 'cowry_demo'; 
$username = 'root';    
$password = '';        

try {
  $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  die("Database connection failed: " . $e->getMessage());
}

// includes/db.php
$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); // Security

// Handle database errors globally
set_exception_handler(function($e) {
    error_log("Database Error: " . $e->getMessage());
    http_response_code(500);
    die("A database error occurred. Please try again later.");
});

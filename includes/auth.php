<?php
// PROJECT_COWRY/includes/auth.php


if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 86400,
        'path' => '/Project_Cowry/',
        'domain' => 'localhost',
        'secure' => false, // Changed for local dev
        'httponly' => true,
        'samesite' => 'Lax' // More flexible than Strict
    ]);
    session_start();
}

// Set security headers (must be before any output)
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Referrer-Policy: strict-origin-when-cross-origin");

// Authentication check with improved validation
function require_auth() {
    if (!isset($_SESSION['user_id']) || 
        !isset($_SESSION['ip_address']) || 
        $_SESSION['ip_address'] !== $_SERVER['REMOTE_ADDR']) {
        
        // Destroy session if invalid
        session_unset();
        session_destroy();
        
        header('Location: /Project_Cowry/auth/login.php');
        exit;
    }
}

// Regenerate session ID periodically
if (!isset($_SESSION['created'])) {
    $_SESSION['created'] = time();
} elseif (time() - $_SESSION['created'] > 1800) {
    session_regenerate_id(true);
    $_SESSION['created'] = time();
}

// Validate user agent
if (!isset($_SESSION['user_agent'])) {
    $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
} elseif ($_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
    session_unset();
    session_destroy();
    header('Location: /Project_Cowry/auth/login.php');
    exit;
}

// Finally perform authentication check
require_auth();
<?php
// PROJECT_COWRY/auth/logout.php
require_once __DIR__ . '/../includes/config.php';

// Clear all session variables
$_SESSION = array();

// Destroy the session
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

session_destroy();

// Redirect to login page
header("Location: login.php");
exit();
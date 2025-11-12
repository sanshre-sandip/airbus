<?php
// Simple logout entry point for convenience
session_start();
// Unset all session variables
$_SESSION = array();
// Destroy session cookie if exists
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params['path'], $params['domain'],
        $params['secure'], $params['httponly']
    );
}
// Destroy the session
session_destroy();
// Redirect to homepage
// Use absolute path so web server redirects correctly (adjust if your project is mounted at a different URL)
header('Location: /advanced/index.php');
exit();
?>
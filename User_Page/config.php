<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    // Secure session configuration
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', 1);
    ini_set('session.cookie_samesite', 'Strict');
    session_start();
}
$host = getenv('DB_HOST') ?: '127.0.0.1';
$port = getenv('DB_PORT') ?: '5432';
$dbname = getenv('DB_NAME') ?: 'kp_planshop';
$username = getenv('DB_USER') ?: 'kp_user';
$password = getenv('DB_PASS') ?: '';

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    logSecurityEvent('database_connection_failed', ['error' => 'Connection error']);
    die("Database connection failed. Please try again later.");
}

if (!defined('SITE_URL')) {
    define('SITE_URL', getenv('SITE_URL') ?: '/');
}

if (!defined('UPLOAD_PATH')) {
    define('UPLOAD_PATH', __DIR__ . '/uploads/');
}

// Include security helper functions
require_once __DIR__ . '/security.php';

// Set security headers
setSecurityHeaders();


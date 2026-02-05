<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) {
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
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Database connection failed");
}

if (!defined('SITE_URL')) {
    define('SITE_URL', getenv('SITE_URL') ?: 'http://localhost/plant-admin/');
}

if (!defined('UPLOAD_PATH')) {
    define('UPLOAD_PATH', __DIR__ . '/uploads/');
}


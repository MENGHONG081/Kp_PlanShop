<?php
if (session_status() === PHP_SESSION_NONE) session_start();

/**
 * Read from Environment Variables
 * (Local: .env / Server: Render Environment)
 */
$host = getenv('DB_HOST') ?: '127.0.0.1';
$port = getenv('DB_PORT') ?: '3307';
$dbname = getenv('DB_NAME') ?: 'plantshop';
$username = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASS') ?: '';

try {
    // PostgreSQL (Render)
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Database connection failed");
}

/**
 * Site URL
 */
if (!defined('SITE_URL')) {
    define(
        'SITE_URL',
        getenv('SITE_URL') ?: 'http://localhost/plant-admin/'
    );
}

/**
 * Upload path (Render: NOT persistent)
 */
if (!defined('UPLOAD_PATH')) {
    define('UPLOAD_PATH', __DIR__ . '/uploads/');
}

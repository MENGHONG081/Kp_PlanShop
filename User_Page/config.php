<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$host = "127.0.0.1";
$port = "3307";
$dbname = "plantshop";
$username = "root";
$password = "";

// DEEPSEEK API Configuration
//define('DEEPSEEK_API_KEY', 'sk-460e42a1e96a4b4ca0ebe559b982495b');
//define('DEEPSEEK_API_URL', 'https://api.deepseek.com/v1/chat/completions');

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Enable CORS if needed
//header("Access-Control-Allow-Origin: *");
//header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
//header("Access-Control-Allow-Headers: Content-Type");

// Set timezone
//date_default_timezone_set('Asia/Phnom_Penh');

if (!defined('SITE_URL')) define('SITE_URL','http://localhost/plant-admin/');
if (!defined('UPLOAD_PATH')) define('UPLOAD_PATH',__DIR__.'/uploads/');


?>
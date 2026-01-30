<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$host = "127.0.0.1";
$port = "3307";
$dbname = "plantshop";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

if (!defined('SITE_URL')) define('SITE_URL','http://localhost/plant-admin/');
if (!defined('UPLOAD_PATH')) define('UPLOAD_PATH',__DIR__.'/uploads/');
?>
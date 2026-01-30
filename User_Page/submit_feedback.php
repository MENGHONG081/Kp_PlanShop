<?php
session_start();

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    die("You must be logged in to submit feedback.");
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    die("Method not allowed.");
}

$user_id = (int)$_SESSION['user_id'];
$comments = trim($_POST['comments'] ?? '');
$rating   = (int)($_POST['rating'] ?? 0);

if (empty($comments)) {
    die("Comments are required.");
}

// DB config
$host = "127.0.0.1";
$port = "3307";
$dbname = "plantshop";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("INSERT INTO customer_feedback (user_id, comments, rating) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $comments, $rating]);

    // ✅ Set success message AFTER insert
    $_SESSION['success'] = "Feedback submitted successfully.";

    // ✅ Redirect AFTER insert
    header("Location: index1.php");
    exit;

} catch (PDOException $e) {
    error_log("Feedback DB error: " . $e->getMessage());
    die("DEBUG: Database error – " . htmlspecialchars($e->getMessage()));
}
?>
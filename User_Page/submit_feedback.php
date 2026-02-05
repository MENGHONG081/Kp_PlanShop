<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit("You must be logged in to submit feedback.");
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    exit("Method not allowed.");
}

$user_id  = (int) $_SESSION['user_id'];
$comments = trim($_POST['comments'] ?? '');
$rating   = (int) ($_POST['rating'] ?? 0);

if ($comments === '') {
    http_response_code(400);
    exit("Comments are required.");
}

// Validate rating (1–5)
if ($rating < 1 || $rating > 5) {
    http_response_code(400);
    exit("Rating must be between 1 and 5.");
}

/**
 * DB from ENV (Render)
 */
$host = getenv('DB_HOST');
$port = getenv('DB_PORT') ?: '5432';
$db   = getenv('DB_NAME');
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');

if (!$host || !$db || !$user || !$pass) {
    http_response_code(500);
    exit("Server DB config missing.");
}

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$db";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    $stmt = $pdo->prepare(
        "INSERT INTO customer_feedback (user_id, comments, rating) VALUES (:uid, :c, :r)"
    );
    $stmt->execute([
        ':uid' => $user_id,
        ':c'   => $comments,
        ':r'   => $rating
    ]);

    $_SESSION['success'] = "Feedback submitted successfully.";

    // ✅ Use absolute path (works reliably on Render)
    header("Location: /User_Page/index1.php");
    exit;

} catch (PDOException $e) {
    error_log("Feedback DB error: " . $e->getMessage());
    http_response_code(500);
    exit("Database error.");
}

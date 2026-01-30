<?php
session_start();
require 'config.php';
require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);  // or adjust path if needed
$dotenv->load();

/* ================= SAFE INPUT ================= */
$orderId        = $_POST['order_id']   ?? '';
$expectedAmount = $_POST['amount']     ?? '';
$orderDate      = $_POST['order_date'] ?? '';

if ($orderId === '' || $expectedAmount === '' || $orderDate === '') {
    die("Missing required data.");
}

/* ================= CONFIG ================= */
$gemini_api_key = $_ENV['GEMINI_API_KEY'] ?? null;
if (empty($geminiApiKey)) {
    die('Missing GEMINI_API_KEY in .env file');
}
$expectedName   = 'YAUN MENGHONG';
$maxMinutes     = 60;

// Update to the latest supported stable/preview model
$model_name     = 'gemini-2.5-flash'; 

/* ================= IMAGE UPLOAD ================= */
$dir = "uploads/";
if (!is_dir($dir)) mkdir($dir, 0777, true);

if (!isset($_FILES['image']) || $_FILES['image']['error'] !== 0) {
    die("Image upload failed.");
}

$imagePath = $dir . time() . '_' . basename($_FILES['image']['name']);
move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);

// Detect MIME type dynamically
$finfo = new finfo(FILEINFO_MIME_TYPE);
$mimeType = $finfo->file($imagePath);
$base64Image = base64_encode(file_get_contents($imagePath));

/* ================= GEMINI VISION API ================= */
// Use the v1beta endpoint to support response_mime_type
$apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/{$model_name}:generateContent?key=" . $gemini_api_key;

$postData = [
    "contents" => [
        [
            "parts" => [
                ["text" => "You are an OCR specialized in Cambodian KHQR screenshots. Extract payment info. Return JSON ONLY: {\"name\": string|null, \"amount\": number|null, \"date_time\": string|null, \"transaction_id\": string|null}"],
                [
                    "inline_data" => [
                        "mime_type" => $mimeType,
                        "data" => $base64Image
                    ]
                ]
            ]
        ]
    ],
    "generationConfig" => [
        "response_mime_type" => "application/json", // This REQUIRES v1beta
        "temperature" => 0.1
    ]
];

$ch = curl_init($apiUrl);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($postData),
    CURLOPT_HTTPHEADER => ["Content-Type: application/json"]
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$result = json_decode($response, true);

/* ================= ERROR LOGGING (gemini_tell.json) ================= */
if (isset($result['error']) || !isset($result['candidates'][0]['content'])) {
    $errorLog = [
        'timestamp' => date('Y-m-d H:i:s'),
        'http_status' => $httpCode,
        'error' => $result['error'] ?? 'No content generated (Safety Block)',
        'raw_response' => $result
    ];
    file_put_contents('gemini_tell.json', json_encode($errorLog, JSON_PRETTY_PRINT));
    
    $_SESSION['fail_reasons'] = ["AI System Error: Check gemini_tell.json"];
    $_SESSION['ai_result'] = $result;
    header("Location: failed.php");
    exit;
}

// Extract JSON text
$responseText = $result['candidates'][0]['content']['parts'][0]['text'] ?? '{}';
$ai = json_decode($responseText, true);

/* ================= VERIFICATION LOGIC ================= */
$valid = true;
$failReasons = [];

// Name Check
if (!isset($ai['name']) || stripos($ai['name'], $expectedName) === false) {
    $valid = false;
    $failReasons[] = "Name mismatch (AI found: '" . ($ai['name'] ?? 'null') . "')";
}

// Amount Check
$aiAmount = (float)($ai['amount'] ?? 0);
if (abs($aiAmount - (float)$expectedAmount) > 0.01) {
    $valid = false;
    $failReasons[] = "Amount mismatch (Found: {$aiAmount}, Expected: {$expectedAmount})";
}

// Date Check
if (empty($ai['date_time'])) {
    $valid = false;
    $failReasons[] = "Date not detected";
} else {
    try {
        $payDT = new DateTime($ai['date_time']);
        $orderDT = new DateTime($orderDate);
        $diff = ($payDT->getTimestamp() - $orderDT->getTimestamp()) / 60;
        if ($diff < -15 || $diff > $maxMinutes) {
            $valid = false;
            $failReasons[] = "Time difference too large ({$diff} mins)";
        }
    } catch (Exception $e) {
        $valid = false;
        $failReasons[] = "AI provided invalid date format";
    }
}

/* ================= DB & REDIRECT ================= */
$status  = $valid ? 'SUCCESS' : 'FAILED';
$txn_ref = $ai['transaction_id'] ?? uniqid('TXN');
$failTxt = $valid ? null : implode(" | ", $failReasons);

$stmt = $pdo->prepare("INSERT INTO payments (payment_id, order_id, amount, payment_method, payment_status, transaction_ref, payment_date, failure_reason) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->execute([uniqid('PAY'), $orderId, $expectedAmount, 'GEMINI_AI By KHQR', $status, $txn_ref,date('Y-m-d H:i:s'), $failTxt]);

if ($valid) {
    header("Location: success.php?id=$orderId");
} else {
    $_SESSION['fail_reasons'] = $failReasons;
    $_SESSION['ai_result'] = $ai;
    header("Location: failed.php");
}
exit;
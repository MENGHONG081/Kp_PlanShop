<?php
header('Content-Type: application/json');

// --- 1. ROBUST ENV LOADER ---
function loadEnv($path) {
    if (!file_exists($path)) return false;
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0 || !strpos($line, '=')) continue;
        list($name, $value) = explode('=', $line, 2);
        putenv(trim($name) . "=" . trim($value, " \"'"));
    }
    return true;
}
//loadEnv(__DIR__ . '/.env');

// --- 2. CONFIGURATION ---
$apiKey = 'AIzaSyAVykKgc4nxMme9UUNZkezRh6ddmCHX3qM';
// In 2026, use gemini-2.5-flash for the best balance of speed and logic
$model = "gemini-2.5-flash"; 
// Use the STABLE v1 endpoint for 1.5 models, or v1beta for 2.5/3.0
$apiVersion = "v1beta"; 

if (!$apiKey) {
    echo json_encode(["error" => "CONFIG_ERROR", "detail" => "API Key missing in .env"]);
    exit;
}

// --- 3. INPUT HANDLING ---
$input = json_decode(file_get_contents('php://input'), true);
$message = $input['message'] ?? '';

if (!$message) {
    echo json_encode(["error" => "EMPTY_INPUT", "detail" => "No message received."]);
    exit;
}

// --- 4. API CALL ---
$url = "https://generativelanguage.googleapis.com/{$apiVersion}/models/{$model}:generateContent?key=" . $apiKey;

$payload = [
    "contents" => [["parts" => [["text" => $message]]]],
    "generationConfig" => [
        "temperature" => 0.7,
        "maxOutputTokens" => 800
    ]
];

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($payload),
    CURLOPT_HTTPHEADER => ['Content-Type: application/json']
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

// --- 5. SMART ERROR LOGGING ---
if ($curlError) {
    echo json_encode(["error" => "CONNECTION_FAILED", "detail" => $curlError]);
    exit;
}

$data = json_decode($response, true);

if ($httpCode !== 200) {
    echo json_encode([
        "error" => "GOOGLE_API_ERROR",
        "detail" => $data['error']['message'] ?? "Unknown Error",
        "code" => $httpCode,
        "suggested_fix" => "Check if the model '$model' is supported in version '$apiVersion'"
    ]);
} else {
    // Success! Send the AI text back
    $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? "AI processed but sent no text.";
    echo json_encode(["reply" => $reply]);
}
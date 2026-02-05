<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

/* =========================
   1) ENV LOADING
   ========================= */
$dotenv = Dotenv::createImmutable(__DIR__ . (file_exists(__DIR__ . '/.env') ? '' : '/..'));
$dotenv->load();

$apiKey = getenv('GEMINI_API_KEY');
$model = "gemini-2.5-flash";   // your choice
$apiVersion = "v1beta";        // your choice

if (!$apiKey) {
    echo json_encode(["error" => "CONFIG_ERROR", "detail" => "API Key missing in .env"]);
    exit;
}

/* =========================
   2) CSV DATASET MATCHER
   ========================= */
function findCsvReply(string $userMessage): ?string
{
    $csvFile = __DIR__ . '/dataset.csv';
    if (!file_exists($csvFile)) return null;

    $userMessage = strtolower(trim($userMessage));
    if ($userMessage === '') return null;

    if (($handle = fopen($csvFile, 'r')) === false) return null;

    // Read header row (intent,input,response)
    $header = fgetcsv($handle);

    while (($row = fgetcsv($handle)) !== false) {
        // Expect at least 3 columns: intent,input,response
        if (count($row) < 3) continue;

        $input = strtolower(trim($row[1]));
        $response = trim($row[2]);

        if ($input !== '' && str_contains($userMessage, $input)) {
            fclose($handle);
            return $response;
        }
    }

    fclose($handle);
    return null;
}

/* =========================
   3) INPUT HANDLING
   ========================= */
$input = json_decode(file_get_contents('php://input'), true);
$message = $input['message'] ?? '';

if (!$message) {
    echo json_encode(["error" => "EMPTY_INPUT", "detail" => "No message received."]);
    exit;
}

/* =========================
   4) CHECK CSV FIRST
   ========================= */
$csvReply = findCsvReply($message);
if ($csvReply !== null) {
    echo json_encode(["reply" => $csvReply], JSON_UNESCAPED_UNICODE);
    exit;
}

/* =========================
   5) GEMINI API CALL (FALLBACK)
   ========================= */
$url = "https://generativelanguage.googleapis.com/{$apiVersion}/models/{$model}:generateContent?key=" . $apiKey;

$payload = [
    "contents" => [
        ["parts" => [["text" => $message]]]
    ],
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
    exit;
}

/* =========================
   6) SUCCESS RESPONSE
   ========================= */
$reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? "AI processed but sent no text.";
echo json_encode(["reply" => $reply], JSON_UNESCAPED_UNICODE);
?>
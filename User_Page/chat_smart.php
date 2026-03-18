<?php
/**
 * Smart AI Chatbot Backend
 * Enhances the existing chatbot with a system prompt and better context handling.
 */

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/vendor/autoload.php';

// 1. Load Configuration
$dotenvPath = file_exists(__DIR__ . '/.env') ? __DIR__ : (file_exists(__DIR__ . '/../.env') ? __DIR__ . '/..' : null);
if ($dotenvPath) {
    $dotenv = Dotenv\Dotenv::createImmutable($dotenvPath);
    $dotenv->safeLoad();
}

$apiKey = getenv('GEMINI_API_KEY');
$model = "gemini-2.5-flash";
$apiVersion = "v1beta";

if (!$apiKey) {
    echo json_encode(["error" => "CONFIG_ERROR", "detail" => "API Key missing in .env"]);
    exit;
}

// 2. System Prompt - Defines the AI's personality and knowledge
$systemPrompt = "You are the official AI assistant for KP Plant Shop (KP Planshop). 
Your goal is to help customers with their plant-related questions, house plans, and shopping experience.

Key Information about KP Plant Shop:
- We sell high-quality indoor and outdoor plants, flowers, and gardening supplies.
- Creators: The website was created by Yaun Menghong and Tan Sophearoth.
- Payment Methods: We support Bakong KHQR and Visa/Mastercard (PayWay).
- Location: Based in Phnom Penh, Cambodia.
- Features: Users can browse products, add to cart, checkout securely, and track their orders.

Guidelines:
- Be professional, friendly, and helpful.
- Use emojis occasionally to be welcoming (🌿, 🌱, 😊).
- If you don't know something specific about an order, ask the user for their Order ID.
- For technical issues, suggest contacting support at knews4265@gmail.com.
- Keep responses concise but informative.
- You can speak both English and Khmer.";

// 3. CSV Dataset Matcher (Legacy support for fast local replies)
function findCsvReply(string $userMessage): ?string
{
    $csvFile = __DIR__ . '/dataset.csv';
    if (!file_exists($csvFile)) return null;

    $userMessage = strtolower(trim($userMessage));
    if ($userMessage === '') return null;

    if (($handle = fopen($csvFile, 'r')) === false) return null;

    // Read header row
    fgetcsv($handle);

    while (($row = fgetcsv($handle)) !== false) {
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

// 4. Input Handling
$input = json_decode(file_get_contents('php://input'), true);
$message = $input['message'] ?? '';

if (!$message) {
    echo json_encode(["error" => "EMPTY_INPUT", "detail" => "No message received."]);
    exit;
}

// 5. Check CSV First (Optional: can be disabled to always use AI)
$csvReply = findCsvReply($message);
if ($csvReply !== null) {
    // We still use AI for a more natural feel even if CSV matches, 
    // or we can return CSV reply directly for speed.
    // echo json_encode(["reply" => $csvReply], JSON_UNESCAPED_UNICODE);
    // exit;
}

// 6. Gemini API Call with System Prompt
$url = "https://generativelanguage.googleapis.com/{$apiVersion}/models/{$model}:generateContent?key=" . $apiKey;

// Combine system prompt with user message for better context
$fullPrompt = $systemPrompt . "\n\nUser: " . $message;

$payload = [
    "contents" => [
        ["parts" => [["text" => $fullPrompt]]]
    ],
    "generationConfig" => [
        "temperature" => 0.7,
        "maxOutputTokens" => 1000,
        "topP" => 0.95,
        "topK" => 40
    ]
];

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($payload),
    CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
    CURLOPT_TIMEOUT => 30
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
    $errorMessage = $data['error']['message'] ?? "Unknown Error";
    echo json_encode([
        "error" => "GOOGLE_API_ERROR",
        "detail" => $errorMessage,
        "code" => $httpCode
    ]);
    exit;
}

// 7. Success Response
$reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? "I'm sorry, I couldn't process that. How else can I help you? 🌿";

// Clean up the reply (remove "AI:" or "Assistant:" prefixes if generated)
$reply = preg_replace('/^(AI|Assistant|Bot):\s*/i', '', $reply);

echo json_encode(["reply" => $reply], JSON_UNESCAPED_UNICODE);
?>

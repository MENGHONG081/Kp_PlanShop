<?php
declare(strict_types=1);
require __DIR__ . '/config.php';
require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

/* ================== ENV ================== */
$dotenv = Dotenv::createImmutable(__DIR__ . (file_exists(__DIR__ . '/.env') ? '' : '/..'));
$dotenv->load();

$botToken       = $_ENV['TELEGRAM_BOT_TOKEN'] ?? null;
$gemini_api_key = $_ENV['GEMINI_API_KEY'] ?? null;

if (empty($botToken) || empty($gemini_api_key)) {
  http_response_code(500);
  echo "Missing TELEGRAM_BOT_TOKEN or GEMINI_API_KEY";
  exit;
}

$website    = "https://api.telegram.org/bot" . $botToken;
$model_name = "gemini-2.5-flash";

/* ================== YOUR CHECK RULES ================== */
$expectedName = "YAUN MENGHONG"; // receiver name you expect in screenshot
$maxMinutes   = 60;             // allowed time window after order time

/* ================== LOG (for testing) ================== */
$raw = file_get_contents("php://input");
file_put_contents(__DIR__ . "/telegram_log.txt", date('c') . "\n" . $raw . "\n\n", FILE_APPEND);

$update = json_decode($raw, true);
$chatId = $update["message"]["chat"]["id"] ?? null;
$text   = $update["message"]["text"] ?? null;

if (!$chatId) {
  echo "OK";
  exit;
}

/* ================== UI HELPERS ================== */
function mainMenuKeyboard(): array {
  return [
    "inline_keyboard" => [
      [
        ["text" => "✅ Verify Payment (Send Photo)", "callback_data" => "VERIFY_INFO"],
      ],
      [
        ["text" => "🧾 Set Expected Amount", "callback_data" => "SET_AMOUNT"],
        ["text" => "🆔 Set Order ID", "callback_data" => "SET_ORDER"],
      ],
      [
        ["text" => "ℹ️ Help", "callback_data" => "HELP"],
        ["text" => "📞 Contact", "callback_data" => "CONTACT"],
      ]
    ]
  ];
}

function sendMessage(int $chatId, string $text, ?array $replyMarkup = null): void {
  global $website;

  $payload = [
    "chat_id" => $chatId,
    "text"    => $text,
    "parse_mode" => "HTML"
  ];

  if ($replyMarkup) {
    $payload["reply_markup"] = json_encode($replyMarkup, JSON_UNESCAPED_UNICODE);
  }

  $ch = curl_init($website . "/sendMessage");
  curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $payload
  ]);
  curl_exec($ch);
  curl_close($ch);
}

function answerCallback(string $callbackQueryId, string $text = ""): void {
  global $website;
  $payload = [
    "callback_query_id" => $callbackQueryId,
    "text" => $text,
    "show_alert" => false
  ];
  $ch = curl_init($website . "/answerCallbackQuery");
  curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $payload
  ]);
  curl_exec($ch);
  curl_close($ch);
}

/* ================== STATE STORAGE (simple) ==================
   For real production, store per-user in DB.
*/
function setUserState(int $chatId, array $data): void {
  $dir = __DIR__ . "/state";
  if (!is_dir($dir)) mkdir($dir, 0777, true);
  file_put_contents($dir . "/{$chatId}.json", json_encode($data, JSON_UNESCAPED_UNICODE));
}
function getUserState(int $chatId): array {
  $file = __DIR__ . "/state/{$chatId}.json";
  if (!file_exists($file)) return [];
  $d = json_decode((string)file_get_contents($file), true);
  return is_array($d) ? $d : [];
}

/* ================== CALLBACK BUTTON HANDLER ================== */
if (isset($update["callback_query"])) {
  $cbId   = $update["callback_query"]["id"];
  $data   = $update["callback_query"]["data"] ?? "";
  $cbChat = $update["callback_query"]["message"]["chat"]["id"] ?? $chatId;

  $state = getUserState((int)$cbChat);

  if ($data === "VERIFY_INFO") {
    answerCallback($cbId, "Send KHQR screenshot photo now.");
    sendMessage((int)$cbChat,
      "📸 <b>How to verify:</b>\n1) Make payment\n2) Screenshot KHQR success page\n3) Send the photo here\n\nI will OCR it and validate:\n• Name contains <b>{$GLOBALS['expectedName']}</b>\n• Amount matches your set amount\n• Time within allowed range",
      mainMenuKeyboard()
    );
    echo "OK"; exit;
  }

  if ($data === "SET_AMOUNT") {
    $state["awaiting"] = "amount";
    setUserState((int)$cbChat, $state);
    answerCallback($cbId, "Type the amount now.");
    sendMessage((int)$cbChat, "🧾 Please type expected amount (example: 50.00)", mainMenuKeyboard());
    echo "OK"; exit;
  }

  if ($data === "SET_ORDER") {
    $state["awaiting"] = "order_id";
    setUserState((int)$cbChat, $state);
    answerCallback($cbId, "Type order id now.");
    sendMessage((int)$cbChat, "🆔 Please type Order ID (example: ORDER123)", mainMenuKeyboard());
    echo "OK"; exit;
  }

  if ($data === "HELP") {
    answerCallback($cbId, "Help opened");
    sendMessage((int)$cbChat,
      "ℹ️ <b>Help</b>\n\n• Use <b>Set Expected Amount</b> and <b>Set Order ID</b> first.\n• Then send a <b>KHQR payment screenshot</b>.\n• Bot verifies by OCR (Gemini).\n\nIf the screenshot is blurry, verification may fail.",
      mainMenuKeyboard()
    );
    echo "OK"; exit;
  }

  if ($data === "CONTACT") {
    answerCallback($cbId, "Contact shown");
    sendMessage((int)$cbChat,
      "📞 <b>Contact</b>\nEmail: knews4265@gmail.com\nPhone: +855 964324308",
      mainMenuKeyboard()
    );
    echo "OK"; exit;
  }

  answerCallback($cbId, "OK");
  echo "OK"; exit;
}

/* ================== TEXT COMMANDS (UI) ================== */
$state = getUserState((int)$chatId);

if (is_string($text) && strlen($text) > 0) {
  if ($text === "/start") {
    setUserState((int)$chatId, [
      "expected_amount" => "50.00",
      "order_id" => "ORDER123",
      "awaiting" => null,
      "last_order_time" => date('c')
    ]);

    sendMessage((int)$chatId,
      "👋 Hi! I’m <b>Kp_PlanshopAI</b>\n\nUse the buttons below to setup amount & order id, then send KHQR screenshot photo.",
      mainMenuKeyboard()
    );
    echo "OK"; exit;
  }

  // If waiting for amount/order input
  if (($state["awaiting"] ?? null) === "amount") {
    $clean = preg_replace('/[^0-9.]/', '', $text);
    if ($clean === "" || (float)$clean <= 0) {
      sendMessage((int)$chatId, "❌ Invalid amount. Example: 50.00", mainMenuKeyboard());
      echo "OK"; exit;
    }
    $state["expected_amount"] = number_format((float)$clean, 2, '.', '');
    $state["awaiting"] = null;
    $state["last_order_time"] = date('c');
    setUserState((int)$chatId, $state);

    sendMessage((int)$chatId, "✅ Expected amount set to <b>{$state['expected_amount']}</b>\nNow send KHQR screenshot photo.", mainMenuKeyboard());
    echo "OK"; exit;
  }

  if (($state["awaiting"] ?? null) === "order_id") {
    $oid = trim($text);
    if ($oid === "") {
      sendMessage((int)$chatId, "❌ Invalid order id. Example: ORDER123", mainMenuKeyboard());
      echo "OK"; exit;
    }
    $state["order_id"] = $oid;
    $state["awaiting"] = null;
    $state["last_order_time"] = date('c');
    setUserState((int)$chatId, $state);

    sendMessage((int)$chatId, "✅ Order ID set to <b>{$state['order_id']}</b>\nNow send KHQR screenshot photo.", mainMenuKeyboard());
    echo "OK"; exit;
  }

  // Normal text fallback
  sendMessage((int)$chatId, "Type /start to open menu.", mainMenuKeyboard());
  echo "OK"; exit;
}

/* ================== HANDLE PHOTO ================== */
if (isset($update["message"]["photo"])) {
  $state = getUserState((int)$chatId);

  $expectedAmount = $state["expected_amount"] ?? "50.00";
  $orderId        = $state["order_id"] ?? "ORDER123";
  $orderDateISO   = $state["last_order_time"] ?? date('c');

  $photos = $update["message"]["photo"];
  $fileId = end($photos)["file_id"];

  // Get file path
  $filePath = telegramApiGetFilePath($fileId, $website);
  if (!$filePath) {
    sendMessage((int)$chatId, "❌ Cannot read file. Try again.", mainMenuKeyboard());
    echo "OK"; exit;
  }

  // Download photo
  $fileUrl = "https://api.telegram.org/file/bot" . $GLOBALS['botToken'] . "/" . $filePath;
  $imageData = @file_get_contents($fileUrl);
  if ($imageData === false) {
    sendMessage((int)$chatId, "❌ Download failed (host may block URL fopen). Enable allow_url_fopen or switch to cURL download.", mainMenuKeyboard());
    echo "OK"; exit;
  }

  // Save local
  $dir = __DIR__ . "/uploads/";
  if (!is_dir($dir)) mkdir($dir, 0777, true);
  $imagePath = $dir . time() . "_" . $chatId . ".jpg";
  file_put_contents($imagePath, $imageData);

  // Analyze
  $analysis = analyzeWithGemini(
    $imagePath,
    $expectedAmount,
    $orderId,
    $orderDateISO,
    $gemini_api_key,
    $model_name,
    $expectedName,
    $maxMinutes
  );

  if ($analysis["status"] === "SUCCESS") {
    sendMessage((int)$chatId,
      "✅ <b>Payment Verified!</b>\n"
      . "Order: <b>{$orderId}</b>\n"
      . "Amount: <b>{$expectedAmount}</b>\n"
      . "Transaction Ref: <b>{$analysis['txn_ref']}</b>",
      mainMenuKeyboard()
    );
  } else {
    sendMessage((int)$chatId,
      "❌ <b>Payment Failed</b>\nReasons:\n• " . implode("\n• ", $analysis["reasons"]),
      mainMenuKeyboard()
    );
  }

  // Save to DB
  savePayment(
    $GLOBALS['pdo'],
    $orderId,
    $expectedAmount,
    $analysis["status"],
    $analysis["txn_ref"],
    $analysis["status"] === "SUCCESS" ? null : implode(" | ", $analysis["reasons"]),
    $analysis["raw_ai_json"] ?? null
  );

  echo "OK"; exit;
}

/* ================== FUNCTIONS ================== */

function telegramApiGetFilePath(string $fileId, string $website): ?string {
  $url = $website . "/getFile?file_id=" . urlencode($fileId);
  $resp = @file_get_contents($url);
  if ($resp === false) return null;
  $j = json_decode($resp, true);
  return $j["result"]["file_path"] ?? null;
}

function savePayment(PDO $pdo, string $orderId, string $amount, string $status, string $txnRef, ?string $failureReason, ?string $rawAiJson): void {
  $stmt = $pdo->prepare("
    INSERT INTO payments
      (payment_id, order_id, amount, payment_method, payment_status, transaction_ref, payment_date, failure_reason, raw_ai_json)
    VALUES
      (?, ?, ?, ?, ?, ?, ?, ?, ?)
  ");
  $stmt->execute([
    uniqid("PAY"),
    $orderId,
    $amount,
    "GEMINI_AI By KHQR",
    $status,
    $txnRef,
    date("Y-m-d H:i:s"),
    $failureReason,
    $rawAiJson
  ]);
}

function analyzeWithGemini(
  string $imagePath,
  string $expectedAmount,
  string $orderId,
  string $orderDateISO,
  string $gemini_api_key,
  string $model_name,
  string $expectedName,
  int $maxMinutes
): array {
  $mimeType = mime_content_type($imagePath) ?: "image/jpeg";
  $base64Image = base64_encode((string)file_get_contents($imagePath));

  $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/{$model_name}:generateContent?key=" . $gemini_api_key;

  // Force ISO 8601 Cambodia time for date_time output
  $prompt = "You are an OCR specialized in Cambodian KHQR payment screenshots.
Extract payment info from the image.
Return JSON ONLY:
{\"name\": string|null, \"amount\": number|null, \"date_time\": string|null, \"transaction_id\": string|null}
Rules:
- date_time MUST be ISO 8601 with +07:00 timezone, example: 2026-02-04T14:20:00+07:00
- amount must be numeric only (no currency text)
- transaction_id should be payment reference if visible.";

  $postData = [
    "contents" => [[
      "parts" => [
        ["text" => $prompt],
        ["inline_data" => [
          "mime_type" => $mimeType,
          "data" => $base64Image
        ]]
      ]
    ]],
    "generationConfig" => [
      "response_mime_type" => "application/json",
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

  file_put_contents(__DIR__ . "/gemini_log.txt", date('c') . "\nHTTP:$httpCode\n$response\n\n", FILE_APPEND);

  $result = json_decode((string)$response, true);
  $responseText = $result["candidates"][0]["content"]["parts"][0]["text"] ?? "{}";
  $ai = json_decode((string)$responseText, true);
  if (!is_array($ai)) $ai = [];

  $valid = true;
  $failReasons = [];

  // Name check
  $foundName = $ai["name"] ?? null;
  if (!is_string($foundName) || stripos($foundName, $expectedName) === false) {
    $valid = false;
    $failReasons[] = "Name mismatch (AI found: '" . ($foundName ?? "null") . "')";
  }

  // Amount check
  $aiAmount = (float)($ai["amount"] ?? 0);
  $exp = (float)$expectedAmount;
  if (abs($aiAmount - $exp) > 0.01) {
    $valid = false;
    $failReasons[] = "Amount mismatch (Found: {$aiAmount}, Expected: {$expectedAmount})";
  }

  // Date check
  $aiDate = $ai["date_time"] ?? null;
  if (!is_string($aiDate) || trim($aiDate) === "") {
    $valid = false;
    $failReasons[] = "Date not detected";
  } else {
    try {
      $payDT = new DateTime($aiDate);
      $orderDT = new DateTime($orderDateISO);
      $diffMin = ($payDT->getTimestamp() - $orderDT->getTimestamp()) / 60;

      if ($diffMin < -15 || $diffMin > $maxMinutes) {
        $valid = false;
        $failReasons[] = "Time difference too large (" . round($diffMin, 1) . " mins)";
      }
    } catch (Exception $e) {
      $valid = false;
      $failReasons[] = "AI provided invalid date format";
    }
  }

  $txn = $ai["transaction_id"] ?? null;
  $txnRef = is_string($txn) && trim($txn) !== "" ? $txn : uniqid("TXN");

  return [
    "status" => $valid ? "SUCCESS" : "FAILED",
    "txn_ref" => $txnRef,
    "reasons" => $failReasons,
    "raw_ai_json" => json_encode($ai, JSON_UNESCAPED_UNICODE)
  ];
}

echo "OK";
?>
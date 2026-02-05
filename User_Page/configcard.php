<?php
// 1. Load Composer autoloader (must be first)
require_once __DIR__ . '/../vendor/autoload.php';

// 2. Load .env from project root
$dotenv = \Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

// 3. Enforce required env variables
$dotenv->required([
    'PAYWAY_MERCHANT_ID',
    'PAYWAY_PUBLIC_KEY'
])->notEmpty();

$merchant_id = $_ENV['PAYWAY_MERCHANT_ID'];
$public_key  = $_ENV['PAYWAY_PUBLIC_KEY'];

// Optional: Add more env vars if needed (e.g. sandbox/production toggle)
// $endpoint = $_ENV['PAYWAY_SANDBOX'] === 'true' 
//     ? 'https://checkout-sandbox.payway.com.kh/api/payment-gateway/v1/payments/purchase'
//     : 'https://checkout.payway.com.kh/api/payment-gateway/v1/payments/purchase';


// 4. Prepare PayWay parameters (all must be strings)
$req_time = gmdate('YmdHis');  // UTC: 20260124120345 format

$tran_id          = (string) ($order['id'] ?? '');                  // must be unique per order
$amount_str       = number_format((float) $grand_total, 2, '.', ''); // "1234.56" – no comma, dot separator
$currency         = 'USD';  // Confirm with ABA if USD is enabled; many use 'KHR'
$type             = 'purchase';
$payment_option   = 'cards';  // 'cards' for Visa/MC; can be 'abapay', 'wing', etc.

// Optional fields – use empty string '' when not provided (do NOT omit from hash)
$items                = '';  // JSON or empty
$shipping             = '';  // amount as string or empty
$ctid                 = '';  // Chain Transaction ID or empty
$pwt                  = '';  // Payment Token or empty

$firstname            = htmlspecialchars(trim($customer['first_name'] ?? ''), ENT_QUOTES, 'UTF-8');
$lastname             = htmlspecialchars(trim($customer['last_name'] ?? ''), ENT_QUOTES, 'UTF-8');
$email                = filter_var($customer['email'] ?? '', FILTER_VALIDATE_EMAIL) ?: '';
$phone                = htmlspecialchars(trim($customer['phone'] ?? ''), ENT_QUOTES, 'UTF-8');

// Return URLs – base64 encoded (PayWay requirement for these fields)
$return_url           = base64_encode('https://your-site.com/payment/success?order=' . urlencode($tran_id));
$cancel_url           = base64_encode('https://your-site.com/payment/cancel?order=' . urlencode($tran_id));
$continue_success_url = '';  // optional deep link after success
$return_deeplink      = '';  // optional

$custom_fields        = '';  // JSON string if used
$return_params        = '';  // custom params returned on redirect (not hashed? but include anyway)

// 5. FIXED HASH CONCATENATION ORDER (critical – must match PayWay spec exactly)
$hash_string = $req_time .
               $merchant_id .
               $tran_id .
               $amount_str .
               $items .
               $shipping .
               $ctid .
               $pwt .
               $firstname .
               $lastname .
               $email .
               $phone .
               $type .
               $payment_option .
               $return_url .
               $cancel_url .
               $continue_success_url .
               $return_deeplink .
               $currency .
               $custom_fields .
               $return_params;

// 6. Generate HMAC-SHA512 hash (binary → base64)
$hash_binary = hash_hmac('sha512', $hash_string, $public_key, true);
$hash        = base64_encode($hash_binary);

// 7. Debug logging – remove or comment in production
// Use full path or ensure error_log is writable
error_log("=== PayWay Debug ===");
error_log("Request Time: $req_time");
error_log("Hash Input (length " . strlen($hash_string) . "): " . $hash_string);
error_log("Public Key (first 8 chars): " . substr($public_key, 0, 8) . "...");
error_log("Generated Hash: $hash");
error_log("Amount formatted: $amount_str");
error_log("====================");

// Optional: Early exit if critical data missing
if (empty($tran_id) || empty($amount_str) || $amount_str === '0.00') {
    error_log("Invalid order data for PayWay");
    die('Invalid order – cannot proceed to payment.');

    
}

error_log("=== PayWay Debug in configcard.php ===");
error_log("tran_id: " . ($tran_id ?? 'NOT SET'));
error_log("grand_total raw: " . ($grand_total ?? 'NOT SET'));
error_log("amount_str: " . ($amount_str ?? 'NOT SET'));
error_log("customer first_name: " . ($customer['first_name'] ?? 'NOT SET'));
error_log("Generated Hash: " . ($hash ?? 'NOT GENERATED'));
error_log("=====================================");

// 8. Validation AFTER attempting to use the variables
if (empty($tran_id) || !isset($grand_total) || !is_numeric($grand_total) || $grand_total <= 0) {
    error_log("Invalid or missing order data in configcard.php – tran_id: " . ($tran_id ?? 'missing') . ", grand_total: " . ($grand_total ?? 'missing'));
    die('Invalid order data – cannot proceed to payment. Please go back and try again.');
}

// All variables below are now available to the including file (e.g. payment.php)
?>
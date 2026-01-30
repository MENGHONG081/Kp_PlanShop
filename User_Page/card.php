<?php
// Your credentials (from sandbox email first!)
$public_key    = 'YOUR_SANDBOX_PUBLIC_KEY_HERE';  // <-- This is crucial! Get from ABA email
$merchant_id   = 'YOUR_SANDBOX_MERCHANT_ID_HERE';

// Collect ALL form parameters you're sending (as assoc array)
// Do NOT include 'hash' itself!
$params = [
    'amount'          => number_format($grand_total, 2),  // e.g. "50.00"
    'currency'        => 'USD',
    'email'           => htmlspecialchars($customer['email'] ?? 'test@example.com'),
    'firstname'       => htmlspecialchars($customer['first_name'] ?? 'Test'),
    'lastname'        => htmlspecialchars($customer['last_name'] ?? 'User'),
    'merchant_id'     => $merchant_id,
    'phone'           => htmlspecialchars($customer['phone'] ?? '+85512345678'),
    'req_time'        => gmdate('YmdHis'),  // UTC! Important
    'return_url'      => base64_encode('https://your-site.com/payment/success?order=' . $order['id']),
    'tran_id'         => (string) $order['id'],  // As string, no padding issues
    'type'            => 'purchase',
    // Add payment_option if used: 'cards'
    // Add any other fields you send, e.g. 'cancel_url' => base64_encode(...),
];

// Optional: Sort keys alphabetically to ensure order
ksort($params);

// Build the raw hash string: just concatenate values (no keys, no separators)
$hash_string = '';
foreach ($params as $key => $value) {
    $hash_string .= $value;
}

// Generate hash
$hash_binary = hash_hmac('sha512', $hash_string, $public_key, true);
$hash        = base64_encode($hash_binary);

// Debug: Print to check (remove in production!)
error_log("Hash string: " . $hash_string);
error_log("Generated hash: " . $hash);
?>
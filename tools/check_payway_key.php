<?php
// tools/check_payway_key.php
// Usage (CLI):
//   php tools/check_payway_key.php C:\path\to\private.pem
// Or set env vars: PAYWAY_PRIVATE_KEY_PATH or PAYWAY_PRIVATE_KEY
// WARNING: Do NOT commit private keys to source control.

$path = $argv[1] ?? null;
if (!$path) {
    $path = getenv('PAYWAY_PRIVATE_KEY_PATH') ?: null;
}

$keyContent = '';
if ($path && file_exists($path)) {
    $keyContent = @file_get_contents($path);
    echo "Loaded key from: $path\n";
} elseif (getenv('PAYWAY_PRIVATE_KEY')) {
    $env = getenv('PAYWAY_PRIVATE_KEY');
    $keyContent = str_replace('\\n', "\n", $env);
    echo "Loaded key from PAYWAY_PRIVATE_KEY environment variable\n";
} else {
    echo "Provide a PEM path as the first argument or set PAYWAY_PRIVATE_KEY_PATH / PAYWAY_PRIVATE_KEY.\n";
    exit(1);
}

if ($keyContent === false || trim($keyContent) === '') {
    echo "Failed to read key content. Check file permissions and path.\n";
    exit(2);
}

// Try to load the private key
$pkey = @openssl_pkey_get_private($keyContent);
if ($pkey !== false) {
    echo "OK: Private key loaded successfully.\n";
    openssl_free_key($pkey);
    exit(0);
}

echo "FAIL: openssl_pkey_get_private() returned false.\n";
// Print OpenSSL errors for diagnostics (safe to show)
while ($err = openssl_error_string()) {
    echo "OPENSSL: $err\n";
}

// Helpful hints
echo "\nHints:\n";
echo " - Ensure the PEM includes BEGIN/END header/footer and is unencrypted.\n";
echo " - If the key is encrypted, convert with: openssl pkcs8 -in encrypted.pem -out private_unencrypted.pem -nocrypt\n";
echo " - If the key is DER, convert: openssl rsa -inform DER -in key.der -out key.pem\n";

exit(3);

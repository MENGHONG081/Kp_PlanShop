<?php
session_start();
require 'config.php';
require_once __DIR__ . '/../vendor/autoload.php';

  // loads everything above

use KHQR\BakongKHQR;
use KHQR\Helpers\KHQRData;
use KHQR\Models\IndividualInfo;
use Dotenv\Dotenv;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

$dotenv = Dotenv::createImmutable(__DIR__ . (file_exists(__DIR__ . '/.env') ? '' : '/..'));
$dotenv->load();

// Example order data

/*$merchant_id = $_ENV['PAYWAY_MERCHANT_ID'];
$return_url  = $_ENV['PAYWAY_RETURN_URL'];
$cancel_url  = $_ENV['PAYWAY_CANCEL_URL'];
$env         = $_ENV['PAYWAY_ENV'];
$privateKey = str_replace('\n', "\n", $_ENV['PAYWAY_PRIVATE_KEY']);
$publicKey  = str_replace('\n', "\n", $_ENV['PAYWAY_PUBLIC_KEY']);

// Prepare data string
$data = $merchant_id . $order_id . ($grand_total * 100) . "USD";

// Sign with RSA private key
$privateKeyResource = openssl_pkey_get_private($private_key);
openssl_sign($data, $signature, $privateKeyResource, OPENSSL_ALGO_SHA256);
openssl_free_key($privateKeyResource);
$signatureBase64 = base64_encode($signature);

// Decide endpoint
$actionUrl = $env === 'sandbox'
    ? 'https://sandbox.payway.com.kh/'
    : 'https://checkout.payway.com.kh/';*/


// Credentials from .env
$token         = $_ENV['BAKONG_TOKEN']       ?? die('Missing BAKONG_TOKEN in .env');
$apiBase       = $_ENV['BAKONG_API_BASE']    ?? 'https://api-bakong.nbc.gov.kh';
$accountID = $_ENV['BAKONG_ACCOUNT']     ?? die('Missing BAKONG_ACCOUNT in .env');

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access");
}

$order_id = (int)($_GET['order'] ?? 0);
$user_id  = (int)$_SESSION['user_id'];

if ($order_id <= 0) {
    die("Invalid order ID");
}

// Fetch order
$stmt = $pdo->prepare("SELECT id, total, created_at FROM orders WHERE id = ? AND user_id = ?");
$stmt->execute([$order_id, $user_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    die("Order not found");
}

// Fetch order items for display
$stmt = $pdo->prepare("
    SELECT oi.qty, oi.unit_price, oi.qty * oi.unit_price AS item_total, p.name, p.image 
    FROM order_items oi 
    JOIN products p ON oi.product_id = p.id 
    WHERE oi.order_id = ?
");
$stmt->execute([$order_id]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate totals (assuming 'total' is stored in cents)
$order_total = $order['total']; // cents → dollars
$date= $order['created_at']; 
$tax = 0; // Adjust if needed
$grand_total = round($order_total + $tax, 2);

// PayWay / Card payment configuration
$merchant_id = $_ENV['PAYWAY_MERCHANT_ID'] ?? null;
$return_url  = $_ENV['PAYWAY_RETURN_URL']  ?? '';
$cancel_url  = $_ENV['PAYWAY_CANCEL_URL']  ?? '';
$payway_env  = $_ENV['PAYWAY_ENV']         ?? 'sandbox';
$private_key_env = $_ENV['PAYWAY_PRIVATE_KEY'] ?? null;
$private_key_path = $_ENV['PAYWAY_PRIVATE_KEY_PATH'] ?? null; // optional: path to PEM file on disk

$actionUrl = $payway_env === 'sandbox'
    ? 'https://sandbox.payway.com.kh/'
    : 'https://checkout.payway.com.kh/';

$signatureBase64 = '';
$cardError = null;

// If a key file path is provided, prefer loading that (safer than storing key in .env)
if ($private_key_path && file_exists($private_key_path)) {
    $fileContents = @file_get_contents($private_key_path);
    if ($fileContents !== false) {
        $private_key_env = $fileContents;
    }
}

if ($merchant_id && $private_key_env) {
    $amountCents = (int)round($grand_total * 100);
    $dataToSign = $merchant_id . $order_id . $amountCents . "USD";
    $privateKeyPem = str_replace('\\n', "\n", $private_key_env);

    $pkey = @openssl_pkey_get_private($privateKeyPem);
    if ($pkey !== false) {
        $ok = openssl_sign($dataToSign, $signature, $pkey, OPENSSL_ALGO_SHA256);
        openssl_free_key($pkey);
        if ($ok) {
            $signatureBase64 = base64_encode($signature);
        } else {
            $cardError = "Failed to generate payment signature.";
        }
    } else {
        $opensslErr = '';
        while ($err = openssl_error_string()) { $opensslErr .= $err . ' | '; }
        $cardError = "Invalid PayWay private key configuration.";
        if (!empty($opensslErr)) {
            // Append a short OpenSSL hint (do not expose key contents)
            $cardError .= " (OpenSSL: " . rtrim($opensslErr, ' | ') . ")";
        }
    }
} else {
    $cardError = "Card payment is not configured.";
}

// Bakong account details
$name      = "YAUN MENGHONG";
$city      = "Phnom Penh";
$currency  = KHQRData::CURRENCY_USD;

// Create IndividualInfo (only supported optional fields in constructor)
$individualInfo = new IndividualInfo(
    bakongAccountID: $accountID,
    merchantName:    $name,
    merchantCity:    $city,
    currency:        $currency,
    amount:          $grand_total,
    billNumber:      "ORDER" . str_pad($order['id'], 6, '0', STR_PAD_LEFT),
    mobileNumber:    "855964324308",
    storeLabel:      "Plant Shop",
    terminalLabel:   "Online"
);
// Optional fields can be set via properties

// Generate KHQR
$response = BakongKHQR::generateIndividual($individualInfo);

// FIXED: The library returns an object(KHQR\Models\KHQRResponse) with ->status (array) and ->data (array)
if ($response->status['code'] !== 0) {
    die("Error generating KHQR: " . ($response->status['message'] ?? 'Unknown error'));
}

$khqr_payload = $response->data['qr'];
$md5_hash     = $response->data['md5']; // Save this to orders table for later veri

$qrImages = [
    //'qr' => 'image/khqr.png',      // KHQR static QR
    'aba' => 'image/qr.jpg',   // ABA QR
    //'wing' => 'image/wing_qr.png'  // WING QR
];

// photo
// initialize upload variables for template
$uploaded_file = $uploaded_file ?? null;
$upload_message = $upload_message ?? null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle image posted from camara.php or direct upload
    $uploaded_file = null;
    $upload_message = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $uploadDir = "uploads/";
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $fileName = time() . "_" . preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($_FILES['image']['name']));
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            $upload_message = "Photo uploaded successfully!";
            // store relative path for preview later in the page
            $uploaded_file = $targetPath;
        } else {
            $upload_message = "Failed to upload photo.";
        }
    } else {
        $upload_message = "No photo selected.";
    }

    // Make $uploaded_file and $upload_message available to the template below
    // (these variables will be used in the HTML to show the upload section/preview)
}

?>
<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Payment - Plant Shop</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

        <!-- In your HTML file -->
    <script type="module" src="generate_khqr.js"></script>
    <link rel="stylesheet" href="/plant_project/assets/css/output.css">



    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#3A5B44",
                        secondary: "#F8F6F2",
                        accent: "#D4BBAA",
                        "text-main": "#333333", 
                        "background-light": "#F8F6F2",
                        "background-dark": "#1a1a1a",
                        "surface-light": "#FFFFFF",
                        "surface-dark": "#2a2a2a",
                        "border-light": "#e5e0da",
                        "border-dark": "#444444",
                    },
                    fontFamily: { display: ["Manrope", "sans-serif"] },
                },
            },
        };
    </script>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24 }
    </style>
</head>
<body class="font-display bg-background-light dark:bg-background-dark text-text-main dark:text-gray-200">
<div class="relative flex min-h-screen flex-col">
    <div class="layout-container flex grow flex-col">
        <header class="border-b border-border-light dark:border-border-dark">
            <div class="container mx-auto flex h-20 items-center justify-center px-4 md:px-6">
                <a class="flex items-center gap-2 text-2xl font-bold text-primary" href="index1.php">
                     <img src="icon/plant_cactus_flower_nature_flower_pot_garden_planter_icon_141184.png" alt="KP Plant_Shop Logo" width="40" height="40" class="img-colorful me-2" />
                    KP Plant_Shop
                </a>
            </div>
        </header>

        <main class="flex-1">
            <div class="container mx-auto grid grid-cols-1 gap-12 px-4 py-12 lg:grid-cols-12 lg:gap-16">
                <!-- Left: Payment Methods -->
                <div class="lg:col-span-7 xl:col-span-8">
                    <div class="flex items-center gap-2 pb-8">
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-primary text-sm font-bold text-white">1</span>
                        <span class="text-primary text-sm font-bold">Shipping</span>
                        <div class="h-px flex-1 bg-border-light dark:bg-border-dark"></div>
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-primary text-sm font-bold text-white">2</span>
                        <span class="text-primary text-sm font-bold">Payment</span>
                        <div class="h-px flex-1 bg-border-light dark:bg-border-dark"></div>
                        <span class="flex h-6 w-6 items-center justify-center rounded-full border-2 border-accent text-sm font-bold text-accent">3</span>
                        <span class="text-accent text-sm font-medium">Confirmation</span>
                    </div>

                    <div class="flex flex-col gap-8">
                        <div>
                            <p class="text-3xl font-bold tracking-tight">Choose Payment Method</p>
                            <p class="text-gray-500 dark:text-gray-400">Secure and fast options available in Cambodia.</p>
                        </div>

                        <div class="space-y-4">

                            <!-- Credit/Debit Card -->
                            <label class="block cursor-pointer" id="visalcard">
                                <input type="radio" name="payment" value="card" class="hidden peer/card">
                                <div class="rounded-xl border-2 border-transparent peer-checked/card:border-primary bg-surface-light dark:bg-surface-dark p-6 shadow-md transition hover:shadow-lg">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-5">
                                            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-primary/10">
                                                <span class="material-symbols-outlined text-primary text-2xl">credit_card</span>
                                            </div>
                                            <div>
                                                <p class="text-xl font-bold">Credit / Debit Card</p>
                                                <p class="text-sm text-gray-500">Visa, Mastercard, UnionPay</p>
                                            </div>
                                        </div>
                                        <div class="flex gap-3">
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5e/Visa_Inc._logo.svg/2560px-Visa_Inc._logo.svg.png" alt="Visa" class="h-8">
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b7/MasterCard_Logo.svg/2560px-MasterCard_Logo.svg.png" alt="Mastercard" class="h-8">
                                        </div>
                                    </div>
                                </div>
                            </label>



                            <!-- KHQR Scan -->
                            <label class="block cursor-pointer" id="khqr-option">
                                <input type="radio" name="payment" value="qr" class="hidden peer/qr">
                                <div class="rounded-xl border-2 border-transparent peer-checked/qr:border-primary bg-surface-light dark:bg-surface-dark p-6 shadow-md transition hover:shadow-lg">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-5">
                                            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-primary/10">
                                                <span class="material-symbols-outlined text-primary text-2xl">qr_code_scanner</span>
                                            </div>
                                            <div>
                                                <p class="text-xl font-bold">Scan QR Code (KHQR)</p>
                                                <p class="text-sm text-gray-500">Pay with any bank app: ABA, Wing, Bakong...</p>
                                            </div>
                                        </div>
                                        <img src="https://devithuotkeo.com/static/image/portfolio/khqr/khqr-5.png" alt="KHQR Logo" class="h-20 object-contain">
                                    </div>
                                </div>
                            </label>

                            <!-- ABA Bank -->
                            <label class="block cursor-pointer" id="aba-option">
                                <input type="radio" name="payment" value="aba" class="hidden peer/aba">
                                <div class="rounded-xl border-2 border-transparent peer-checked/aba:border-primary bg-surface-light dark:bg-surface-dark p-6 shadow-md transition hover:shadow-lg">
                                    <div class="flex items-center gap-5">
                                        <img src="https://financialit.net/sites/default/files/aba_mobile_logo_0.png" alt="ABA Bank Logo" class="h-14 object-contain">
                                        <div>
                                            <p class="text-xl font-bold">ABA Bank</p>
                                            <p class="text-sm text-gray-500">Pay via ABA Mobile App</p>
                                        </div>
                                    </div>
                                </div>
                            </label>

                            <!-- Wing Bank -->
                            <label class="block cursor-pointer" id="wing-option">
                                <input type="radio" name="payment" value="wing" class="hidden peer/wing">
                                <div class="rounded-xl border-2 border-transparent peer-checked/wing:border-primary bg-surface-light dark:bg-surface-dark p-6 shadow-md transition hover:shadow-lg">
                                    <div class="flex items-center gap-5">
                                        <img src="https://www.cma-network.org/uploads/EfYv6Z5tR4shJSCAITQwFe835o0HTf.png" alt="Wing Bank Logo" class="h-14 object-contain">
                                        <div>
                                            <p class="text-xl font-bold">Wing Bank</p>
                                            <p class="text-sm text-gray-500">Pay via Wing Money App</p>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <!-- Dynamic QR Section -->

                            <div id="qr-section" class="mt-8 hidden rounded-2xl bg-surface-light dark:bg-surface-dark p-10 text-center shadow-2xl " >
                            <img src="" alt="KHQR Logo" class="h-20 object-contain mx-auto mb-6" id ="qr-logo">
                            <h3 class="text-2xl font-bold mb-4" id="qr-title">Scan to Pay with KHQR</h3>
                            <p class="text-xl font-semibold mb-2">Amount: <span class="text-primary">$<?= number_format($grand_total, 2) ?></span></p>
                            <p class="text-sm text-gray-500 mb-8">Order #<?= str_pad($order['id'], 6, '0', STR_PAD_LEFT) ?> • Plant Shop, Phnom Penh</p>
                            <div id="qrcode" class="inline-block p-6 bg-white rounded-2xl shadow-inner"></div>
                            <p class="mt-8 text-gray-600">Open ABA, Wing, Bakong or any KHQR-supported app and scan</p>
                            <div id="qr-section">
                                    <div id="qrcode1"></div> 
                                    
                                    <p class="mt-4 text-sm font-semibold text-orange-600">
                                        Valid for: <span id="qr-time">Not Limited</span>
                                    </p>
                                    <p class="text-gray-700 leading-relaxed">
                                After completing your payment, please choose one of the verification methods below. 
                                Don’t forget to upload your payment screenshot so we can confirm it.  
                                </p>

                                <ul class="list-disc list-inside text-gray-700 mt-2 space-y-1">
                                <li>
                                    <span class="font-semibold text-green-600">Telegram Bot AI Verify:</span>  
                                    Click the button to open our bot and send your payment screenshot for instant AI verification.
                                </li>
                                <li>
                                    <span class="font-semibold text-purple-600">Telegram Admin:</span>  
                                    Click the button to contact our admin directly on Telegram for manual verification.
                                </li>
                                <li>
                                    <span class="font-semibold text-blue-600">Upload QR Verify:</span>  
                                    Click the button to upload your payment screenshot directly through our website for confirmation.
                                </li>
                                </ul>

                                <p class="mt-3 text-sm text-orange-600 font-semibold">
                                Note: This QR code is static. Please use the image QR provided above.
                                </p>
                                <h3 class="mt-3 text-sm text-orange-600 font-semibold">Choose Verification Method:</h3>
                                </div>
                                <div id="expired-message" style="display:none;" class="text-red-500 font-bold">
                                    <p>QR Code Expired. Please select the payment method again to generate a new QR code.</p>
                                    
                                </div>
                            <!-- Buttom Section -->
                          <div class="flex flex-col md:flex-row gap-2 mt-5 items-center justify-center " id="qr-buttons">
                            
                            <button onclick="window.open('https://t.me/Kp_PlanshopAI_bot', '_blank')" 
                            class="bg-green-600 text-white px-4 py-2 rounded shadow hover:bg-green-700 flex items-center gap-2">
                            <i class="fab fa-telegram"></i> Bot AI Verify
                            </button>
                            <button class="bg-purple-600 text-white px-4 py-2 rounded shadow hover:bg-purple-700 flex items-center gap-2" onclick="window.open('https://t.me/MENGHONGY081', '_blank')">
                                <i class="fab fa-telegram"></i> Telegram Admin
                            </button>
                            <button class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 flex items-center gap-2">
                                <i class="fas fa-upload"></i> Upload QR Verify
                            </button>
                        </div>
                        </div>

                        <!-- Card Payment Section -->
                            <div id="card-payment-section" class="mt-8 rounded-2xl bg-surface-light dark:bg-surface-dark p-10 text-center shadow-2xl">
                                <div class="flex gap-3">
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5e/Visa_Inc._logo.svg/2560px-Visa_Inc._logo.svg.png" alt="Visa" class="h-8">
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b7/MasterCard_Logo.svg/2560px-MasterCard_Logo.svg.png" alt="Mastercard" class="h-8">
                                        </div>
                                <p class="text-xl font-semibold mb-2">Amount: <span class="text-primary">$<?= number_format($grand_total, 2) ?></span></p>
                                <p class="text-sm text-gray-500 mb-8">Order #<?= str_pad($order['id'], 6, '0', STR_PAD_LEFT) ?> • Plant Shop, Phnom Penh</p>
                                
                                <!-- Simple button that triggers redirect to PayWay -->
                                <form action="<?= htmlspecialchars($actionUrl) ?>" method="POST">
                                    <?php if (!empty($cardError)): ?>
                                        <div class="mb-4 text-red-600 font-semibold"><?= htmlspecialchars($cardError) ?></div>
                                    <?php endif; ?>

                                    <input type="hidden" name="merchant_id" value="<?= htmlspecialchars($merchant_id ?? '') ?>">
                                    <input type="hidden" name="order_id" value="<?= htmlspecialchars($order_id) ?>">
                                    <input type="hidden" name="amount" value="<?= htmlspecialchars((int)round($grand_total * 100)) ?>">
                                    <input type="hidden" name="currency" value="USD">
                                    <input type="hidden" name="return_url" value="<?= htmlspecialchars($return_url) ?>?order=<?= htmlspecialchars($order_id) ?>">
                                    <input type="hidden" name="cancel_url" value="<?= htmlspecialchars($cancel_url) ?>">
                                    <input type="hidden" name="signature" value="<?= htmlspecialchars($signatureBase64) ?>">

                                    <button type="submit" <?php if (!empty($cardError)) echo 'disabled'; ?> class="w-full rounded-xl bg-pink-500 hover:bg-indigo-700 text-white font-semibold py-3 shadow-lg transition mt-6 flex items-center justify-center gap-2 <?php if (!empty($cardError)) echo 'opacity-50 cursor-not-allowed'; ?>">
                                        Pay Now with Card ($<?= number_format($grand_total, 2) ?>)
                                    </button>
                                </form>

                                
                                <p class="mt-8 text-gray-600">Secure payment processed by ABA PayWay. We accept Visa, Mastercard, and more.</p>
                                
                                <!-- Optional: Expired or error message -->
                                <div id="error-message" style="display:none;" class="mt-6 text-red-500 font-bold">
                                    Payment failed or session expired. Please try again.
                                </div>
                            </div>

                        <!-- Upload QR img Section -->
                    
                        <form action="upload.php" method="POST" enctype="multipart/form-data"
                            id="upload-form"
                            class="mt-8 <?= isset($uploaded_file) && $uploaded_file ? '' : 'hidden' ?> rounded-2xl bg-surface-light dark:bg-surface-dark p-10 text-center shadow-2xl  ">
                            <!-- Header -->
                            <div class="text-center">
                                <h2 class="text-2xl font-bold text-slate-800 dark:text-white">
                                    Payment Verification
                                </h2>
                                <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">
                                    Upload your payment screenshot for confirmation
                                </p>
                            </div>
                            <?php if (!empty($upload_message)): ?>
                                <div class="mt-4 mb-4 text-sm <?= (strpos($upload_message,'successfully')!==false)?'text-green-600':'text-red-600' ?> font-semibold"><?= htmlspecialchars($upload_message) ?></div>
                            <?php endif; ?>
                            <!-- Order ID -->
                            <div class="grid grid-cols-2 gap-4">
                                <!-- Hidden inputs for order data -->
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                    Order ID
                                </label>
                                <input type="text"
                                    class="w-full rounded-xl border border-slate-300 dark:border-slate-700
                                            bg-slate-100 dark:bg-slate-800
                                            px-4 py-3 text-slate-700 dark:text-white"
                                    value="<?= htmlspecialchars($order['id'] ?? '') ?>" readonly>
                                <input type="hidden" name="order_id"
                                    value="<?= htmlspecialchars($order['id'] ?? '') ?>">
                            </div>
                            <!-- Amount -->
                            <div >
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                    Amount
                                </label>
                                <input type="text"
                                    class="w-full rounded-xl border border-slate-300 dark:border-slate-700
                                            bg-slate-100 dark:bg-slate-800
                                            px-4 py-3 text-slate-700 dark:text-white"
                                    value="<?= htmlspecialchars($grand_total ?? '') ?>" readonly>
                                <input type="hidden" name="amount"
                                    value="<?= htmlspecialchars($grand_total ?? '') ?>">
                            </div>
                            <!-- Order Date -->
                            <div>   
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">
                                    Order Date
                                </label>
                                <input type="text"
                                    class="w-full rounded-xl border border-slate-300 dark:border-slate-700
                                            bg-slate-100 dark:bg-slate-800
                                            px-4 py-3 text-slate-700 dark:text-white"
                                    value="<?= htmlspecialchars($date ?? '') ?>" readonly>
                                <input type="hidden" name="order_date"
                                    value="<?= htmlspecialchars($date ?? '') ?>">
                            </div>
                            <!-- Upload Screenshot -->
                            <div>
                                <!-- Upload Section -->
                            <div class="mb-8">
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Upload Screenshot</label>

                                <!-- Hidden real file input -->
                                <input type="file" name="image" id="imgInput" accept="image/*" <?= isset($uploaded_file) && $uploaded_file ? '' : 'required' ?> class="hidden">

                                <!-- Styled label acts as button -->
                                <label for="imgInput"
                                    class="block w-full max-w-md mx-auto cursor-pointer rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-4 shadow-lg transition flex items-center justify-center gap-2">
                                    📤 Choose Payment Screenshot
                                </label>

                                <!-- Show selected file name or uploaded file -->
                                <p id="file-name" class="mt-3 text-sm text-slate-600 dark:text-slate-400"><?php if (!empty($uploaded_file)): ?><?= htmlspecialchars(basename($uploaded_file)) ?><?php else: ?>No file chosen yet.<?php endif; ?>
                                </p>

                                <!-- Optional preview -->
                                <div class="mt-4">
                                    <img id="preview" class="<?= isset($uploaded_file) && $uploaded_file ? '' : 'hidden' ?> max-w-full h-auto rounded-xl mx-auto shadow" alt="Preview" <?php if (!empty($uploaded_file)): ?>src="<?= htmlspecialchars($uploaded_file) ?>"<?php endif; ?>>
                                </div>

                                <?php if (!empty($uploaded_file)): ?>
                                    <input type="hidden" name="uploaded_file" value="<?= htmlspecialchars($uploaded_file) ?>">
                                <?php endif; ?>
                                    </div>
                                    </div>
                                    <div class="col-span-2 mb-5">
                                        <!-- Submit Button -->
                                       <button type="submit" id="verify-btn"
                                            class="w-full rounded-xl bg-pink-500
                                                hover:bg-indigo-700
                                                text-white font-semibold py-3
                                                shadow-lg transition mt-6 flex items-center justify-center  gap-2">
                                                <img src="image/ai.png" alt="AI" class="w-10 h-10">
                                                <span id="verify-btn-text">Verify Payment By AI</span>
                                        </button>
                                    </div>
                                    
                                                <!-- Uplaod Img to feild  by photo -->
                                        <button type="button" class="w-full rounded-xl bg-indigo-600
                                                    hover:bg-indigo-700
                                                    text-white font-semibold py-3
                                                    shadow-lg transition mt-6 flex items-center justify-center gap-2" onclick="location.href='camara.php?order=<?= htmlspecialchars($order_id) ?>'" > 📸 Photo transaction</button>
                                            

                            <!-- scan to AI Analysis -->
                            <button type="button" class="w-full rounded-xl bg-indigo-600
                                        hover:bg-indigo-700
                                        text-white font-semibold py-3
                                        shadow-lg transition mt-6 flex items-center justify-center gap-2" onclick="location.href='Scan.php'">
                                        <img src="image/ai.png" alt="AI" class="w-10 h-10">
 
                                        Capture & Verify By AI Scan</button>

                        </form>
                            </div>


                        <!-- Navigation Buttons --> 
                        <div class="flex justify-between items-center pt-6">
                            <a href="Order.php?order=<?= $order_id ?>" class="flex items-center gap-2 text-primary hover:underline">
                                <span class="material-symbols-outlined">arrow_back</span> Back
                            </a>
                            <button id="pay-btn" disabled class="flex items-center justify-center gap-2 rounded-lg bg-primary px-8 py-4 text-base font-bold text-white opacity-60 cursor-not-allowed transition hover:bg-primary/90 onclick="alert('Please select a payment method to proceed.')">
                                Complete Payment
                                <span class="material-symbols-outlined">arrow_forward</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Right: Order Summary -->
                <div class="lg:col-span-5 xl:col-span-4">
                    <div class="sticky top-24 rounded-xl bg-surface-light dark:bg-surface-dark p-6 shadow-lg border border-border-light dark:border-border-dark">
                        <h2 class="text-xl font-bold border-b border-border-light dark:border-border-dark pb-4 mb-4">Order Summary</h2>
                        <div class="space-y-4">
                            <?php foreach ($items as $item): ?>
                            <div class="flex items-center gap-4">
                                <div class="relative h-16 w-16 flex-shrink-0">
                                    <img class="h-full w-full rounded-md object-cover" src="../plant_admin/uploads/<?= htmlspecialchars($item['image'] ?? 'placeholder.png') ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                                    <span class="absolute -top-2 -right-2 flex h-6 w-6 items-center justify-center rounded-full bg-primary text-xs font-bold text-white"><?= $item['qty'] ?></span>
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold"><?= htmlspecialchars($item['name']) ?></p>
                                    <p class="text-sm text-gray-500">$<?= number_format($item['unit_price'], 2) ?> × <?= $item['qty'] ?></p>
                                </div>
                                <p class="font-semibold">$<?= number_format($item['item_total'], 2) ?></p>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="mt-6 space-y-2 border-t pt-4">
                            <div class="flex justify-between text-sm text-gray-500">
                                <span>Subtotal</span>
                                <span>$<?= number_format($order_total, 2) ?></span>
                            </div>
                            <div class="flex justify-between text-sm text-gray-500">
                                <span>Tax (0)</span>
                                <span>$<?= number_format($tax, 2) ?></span>
                            </div>
                            <div class="mt-4 flex justify-between border-t pt-4 font-bold text-lg">
                                <span>Total</span>
                                <span class="text-primary">$<?= number_format($grand_total, 2) ?></span>
                            </div>
                            <div class="mt-6 flex items-center justify-center gap-2 rounded-lg bg-gray-100 dark:bg-gray-800 p-3 text-sm">
                                <span class="material-symbols-outlined">lock</span>
                                Secure & Encrypted Payment
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
const qrSection = document.getElementById('qr-section');
const cardform = document.getElementById('card-payment-section')
const cardSection = document.getElementById('visalcard');
const qrcodeDiv = document.getElementById('qrcode');
const payBtn = document.getElementById('pay-btn');
const qrButtons = document.getElementById('qr-buttons');
const uploadForm = document.getElementById('upload-form');
const qrTitle = document.getElementById('qr-title');
const qrLogo = document.getElementById('qr-logo');
cardform.classList.add('hidden'); // hide for other options
document.querySelectorAll('input[name="payment"]').forEach(radio => {
    radio.addEventListener('change', function() {

        qrSection.classList.add('hidden');
        cardform.classList.add('hidden');
        uploadForm.classList.add('hidden');
        qrcodeDiv.innerHTML = ''; // clear previous QR/image
       // uploadForm.disabled =false // removes button from page;
        payBtn.disabled = false;
        payBtn.classList.remove('opacity-60','cursor-not-allowed');
 
        // KHQR QR code generation

        // 1. Move the timer function OUTSIDE the event listener
            function startTimer(durationInSeconds, displayElement, sectionToHide, pollingInterval) {
                let timer = durationInSeconds;
                let minutes, seconds;

                const countdown = setInterval(function () {
                    minutes = parseInt(timer / 60, 10);
                    seconds = parseInt(timer % 60, 10);

                    minutes = minutes < 10 ? "0" + minutes : minutes;
                    seconds = seconds < 10 ? "0" + seconds : seconds;

                    displayElement.textContent = minutes + ":" + seconds;

                    if (--timer < 0) {
                        clearInterval(countdown);
                        clearInterval(pollingInterval); // STOP checking payment if expired
                        
                        sectionToHide.style.display = "none";
                        const expiredMsg = document.getElementById('expired-message');
                        if(expiredMsg) expiredMsg.style.display = "block";
                    }
                }, 1000);
                
                return countdown; // Return so we can clear it if payment succeeds early
            }

            // 2. Inside your selection change listener
            if (this.value === 'qr') {
                qrSection.classList.remove('hidden');
                qrButtons.style.visibility = "hidden"; 

                // Initialize QR
                qrInstance = new QRCode(qrcodeDiv, {
                    text: <?= json_encode($khqr_payload) ?>,
                    width: 280,
                    height: 280,
                    colorDark: "#000000",
                    colorLight: "#ffffff",
                    correctLevel: QRCode.CorrectLevel.H
                });
                qrLogo.src = "https://devithuotkeo.com/static/image/portfolio/khqr/khqr-5.png";

                // Start Polling
                // Polling Interval
                            const checkStatus = setInterval(() => {
                                fetch('verify_payment.php', {
                                    method: 'POST',
                                    headers: { 'Content-Type': 'application/json' },
                                    body: JSON.stringify({
                                        md5: '<?= $md5_hash ?>',
                                        order_id: '<?= $order['id'] ?>',
                                        amount: '<?= $grand_total ?>' // CRITICAL: This was missing!
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success === true) {
                                        clearInterval(checkStatus);
                                        alert("Payment Successful!");
                                        window.location.href = 'successgr.php?order_id=<?= $order['id'] ?>';
                                    }
                                })
                                .catch(err => console.error('Error:', err));
                            }, 3000);

                                                        // START TIMER HERE (When QR is shown, not on window load)
                                                        const display = document.querySelector('#qr-time');
                                                        const paymentTimer = startTimer(600, display, qrSection, checkStatus);
                                                    }

        // WING - just display an image
        if (this.value === 'wing') {
            qrSection.classList.remove('hidden');
            qrButtons.style.visibility = "visible"; // Show buttons in QR section
            qrTitle.innerHTML = "Scan to Pay with WING QR";
            qrTitle.style.color = "#07b82bff";
            qrTitle.style.fontWeight = "bold";
            qrButtons.addEventListener('click', function() {
                uploadForm.classList.remove('hidden');
                qrButtons.style.visibility = "hidden";

            });
            qrcodeDiv.innerHTML = '<img src="image/qr.jpg" alt="WING QR" class="mx-auto rounded-2xl shadow-inner" style="width:280px; height:280px;">';
            qrLogo.src = "https://www.cma-network.org/uploads/EfYv6Z5tR4shJSCAITQwFe835o0HTf.png";
        }

        // ABA - just display an image
        if (this.value === 'aba') {
            qrSection.classList.remove('hidden');
            //uploadForm.classList.remove('hidden');
            qrTitle.innerHTML = "Scan to Pay with ABA QR";
             qrTitle.style.color = "#046491ff";
            qrTitle.style.fontWeight = "bold";
            qrButtons.addEventListener('click', function() {
                uploadForm.classList.remove('hidden');
                 qrButtons.style.visibility = "hidden";
                   
            });
            qrButtons.style.visibility = "visible"; // Show buttons in QR section
            qrcodeDiv.innerHTML = '<img src="image/qr.jpg" alt="ABA QR" class="mx-auto rounded-2xl shadow-inner" style="width:280px; height:280px;">';
            qrLogo.src = "https://financialit.net/sites/default/files/aba_mobile_logo_0.png";
        }

        // Visalcard - just display an image
        if (this.value === 'card') {
            cardform.classList.remove('hidden'); // show
        }
        
    });
});
    
</script>
<script>
// Show AI loading state on upload form submit
(function(){
    const uploadForm = document.getElementById('upload-form');
    const verifyBtn = document.getElementById('verify-btn');
    if (!uploadForm || !verifyBtn) return;

    uploadForm.addEventListener('submit', function (e) {
        // Prevent multiple triggers
        verifyBtn.disabled = true;
        verifyBtn.classList.add('opacity-60', 'cursor-not-allowed');

        // Replace content with spinner + message
        verifyBtn.innerHTML = `
            <svg class="animate-spin h-6 w-6 mr-3 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
            </svg>
            <span>Please wait — AI processing...</span>
        `;

        // Allow form to submit normally
    });
})();
</script>
</body>
</html>
<?php
require 'vendor/autoload.php';

use KHQR\BakongKHQR;
use KHQR\Helpers\KHQRData;
use KHQR\Models\IndividualInfo;

// Your details (replace with exact)
$yourName = "YAUN MENGHONG";           // As shown in your screenshot
$accountID = "004448060@aba";           // ABA format: accountnumber@aba (common for personal)
$city = "Phnom Penh";                  // Or your city
$currency = KHQRData::CURRENCY_USD;     // USD = 840
$amount = 0.00;                        // 0 for static (sender enters amount)

// Optional: mobile number, store label, etc.
$mobile = "855xxxxxxxxx";              // Your phone if linked

$individualInfo = new IndividualInfo(
    bakongAccountID: $accountID,
    merchantName: $yourName,
    merchantCity: $city,
    currency: $currency,
    amount: $amount,
    mobileNumber: $mobile ?? null
    // storeLabel: "Personal", terminalLabel: null, billNumber: null
);

$response = BakongKHQR::generateIndividual($individualInfo);

if ($response->getStatus()->getCode() === 0) {
    $qrString = $response->getData()->getQr();        // The raw KHQR string
    $md5 = $response->getData()->getMd5();            // MD5 hash (for checking payment later)

    echo "KHQR String: " . $qrString . "<br><br>";
    echo "MD5: " . $md5 . "<br><br>";

    // Display QR code in browser (using Google Charts API for quick view)
    echo '<img src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=' . urlencode($qrString) . '" alt="Your KHQR">';

    // For branded ABA-style image (if you installed chamroeuntam/bakong-khqr-image)
    // $qrImage = BakongKHQR::createQrImage($individualInfo);
    // file_put_contents('my_khqr.png', $qrImage);
    // echo '<img src="data:image/png;base64,' . base64_encode($qrImage) . '">';

} else {
    echo "Error: " . $response->getStatus()->getMessage();
}
?>
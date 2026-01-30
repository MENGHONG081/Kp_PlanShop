<?php
session_start();
require 'config.php';

// 1. Get the Order ID from URL
$orderId = $_GET['id'] ?? '';

if (empty($orderId)) {
    die("Invalid Order ID.");
}

// 2. Fetch payment details from database
$stmt = $pdo->prepare("SELECT * FROM payments WHERE order_id = ? AND payment_status = 'SUCCESS' LIMIT 1");
$stmt->execute([$orderId]);
$payment = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$payment) {
    // If no successful payment found, redirect to status check or fail page
    header("Location: failed.php");
    exit;
}

// 3. Cleanup: Delete debug log if payment was successful
if (file_exists('gemini_tell.json')) {
    unlink('gemini_tell.json');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f0f2f5; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; }
        .success-card { background: #fff; padding: 40px; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); text-align: center; max-width: 450px; width: 90%; }
        .icon-circle { width: 80px; height: 80px; background: #4BB543; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 40px; margin: 0 auto 20px; }
        h1 { color: #333; margin-bottom: 10px; font-size: 24px; }
        p { color: #666; margin-bottom: 30px; }
        .details-box { background: #f8f9fa; border-radius: 12px; padding: 20px; text-align: left; margin-bottom: 30px; border: 1px solid #eee; }
        .detail-row { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 14px; }
        .detail-label { color: #888; }
        .detail-value { color: #333; font-weight: 600; }
        .btn-home { display: block; background: #007bff; color: white; padding: 12px; text-decoration: none; border-radius: 8px; font-weight: 600; transition: background 0.2s; }
        .btn-home:hover { background: #0056b3; }
    </style>
</head>
<body>

<div class="success-card">
    <div class="icon-circle">✓</div>
    <h1>Payment Received!</h1>
    <p>Your transaction has been verified by our AI system and processed successfully.</p>

    <div class="details-box">
        <div class="detail-row">
            <span class="detail-label">Order ID</span>
            <span class="detail-value">#<?php echo htmlspecialchars($payment['order_id']); ?></span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Amount Paid</span>
            <span class="detail-value">$<?php echo number_format($payment['amount'], 2); ?></span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Transaction Ref</span>
            <span class="detail-value"><?php echo htmlspecialchars($payment['transaction_ref']); ?></span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Date</span>
            <span class="detail-value"><?php echo date('M d, Y H:i', strtotime($payment['payment_date'])); ?></span>
        </div>
    </div>

    <a href="index1.php" class="btn-home">Back to Dashboard</a>
</div>

</body>
</html>
<?php
require 'config.php';
require 'vendor/autoload.php';

header('Content-Type: application/json');

$md5 = $_GET['md5'] ?? '';
$order_id = $_GET['order_id'] ?? '';
$amount = $_GET['amount'] ?? 0;

if (empty($md5) || empty($order_id)) {
    echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
    exit;
}

try {
    // 1. Check Bakong API
    $response = \KHQR\BakongKHQR::checkTransaction($md5);

    // 2. If transaction is successful
    if (isset($response->data['accepted']) && $response->data['accepted'] === true) {
        
        // Start transaction to ensure data integrity
        $pdo->beginTransaction();

        // 3. Prevent duplicate processing
        $check = $pdo->prepare("SELECT id FROM payments WHERE transaction_ref = ?");
        $check->execute([$md5]);
        
        if (!$check->fetch()) {
            // 4. Record the payment
            $stmt = $pdo->prepare("INSERT INTO payments (order_id, amount, payment_method, payment_status, transaction_ref, payment_date) 
                                   VALUES (?, ?, 'Bakong KHQR', 'SUCCESS', ?, NOW())");
            $stmt->execute([$order_id, $amount, $md5]);

            // 5. CRITICAL: Update the order status so you know to ship the plants!
            $updateOrder = $pdo->prepare("UPDATE orders SET status = 'paid', payment_status = 'completed' WHERE id = ?");
            $updateOrder->execute([$order_id]);
        }

        $pdo->commit();
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Payment not found or pending']);
    }

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
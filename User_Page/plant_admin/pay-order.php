<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require 'auth.php'; // must define $pdo
header('Content-Type: application/json');
// Optional: check if current session is admin
$is_admin = $_SESSION['admin_id'] ?? false;
if (!$is_admin) {
    echo json_encode(['success' => false, 'message' => 'Not authorized']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$order_id = $input['order_id'] ?? null;
$amount   = $input['amount'] ?? null;

if (!$order_id || $amount === null) {
    echo json_encode(['success' => false, 'message' => 'Missing data']);
    exit;
}

// Verify order exists
$stmt = $pdo->prepare("SELECT total, user_id FROM orders WHERE id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch();

if (!$order) {
    echo json_encode(['success' => false, 'message' => 'Order not found']);
    exit;
}

if ((float)$order['total'] !== (float)$amount) {
    echo json_encode(['success' => false, 'message' => 'Amount mismatch']);
    exit;
}

// Prevent double payment
$stmt = $pdo->prepare("SELECT 1 FROM payments WHERE order_id = ? AND payment_status = 'success'");
$stmt->execute([$order_id]);
if ($stmt->fetch()) {
    echo json_encode(['success' => true, 'message' => 'Already paid']);
    exit;
}

// Insert payment record
$ref = 'TXN_' . strtoupper(bin2hex(random_bytes(6)));
$stmt = $pdo->prepare("
    INSERT INTO payments 
    (order_id, amount, payment_method, payment_status, transaction_ref, payment_date)
    VALUES (?, ?, 'admin_credit', 'success', ?, NOW())
");
$stmt->execute([$order_id, $amount, $ref]);

// Update order status
$pdo->prepare("UPDATE orders SET status = 'paid' WHERE id = ?")->execute([$order_id]);

echo json_encode(['success' => true, 'message' => 'Payment recorded by admin']);
?>
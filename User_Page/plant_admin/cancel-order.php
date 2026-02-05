<?php
ob_start(); 
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require 'auth.php'; // defines $pdo
header('Content-Type: application/json');
$input = json_decode(file_get_contents('php://input'), true);
$order_id = $input['order_id'] ?? null;

if (!$order_id) {
    echo json_encode(['success' => false, 'message' => 'Missing order ID']);
    exit;
}

// Optional: check if admin or owner
$is_admin = $_SESSION['admin_id'] ?? false;

if (!$is_admin) {
    // If not admin, ensure user owns the order
    $stmt = $pdo->prepare("SELECT user_id FROM orders WHERE id = ?");
    $stmt->execute([$order_id]);
    $order = $stmt->fetch();
    if (!$order || $order['user_id'] != $_SESSION['user_id']) {
        echo json_encode(['success' => false, 'message' => 'Not authorized']);
        exit;
    }
}

// Update order status
$stmt = $pdo->prepare("UPDATE orders SET status = 'cancelled' WHERE id = ?");
$stmt->execute([$order_id]);

echo json_encode(['success' => true, 'message' => 'Order cancelled']);
?>
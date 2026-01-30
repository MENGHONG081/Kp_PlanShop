<?php
session_start();
require 'auth.php'; // defines $pdo

header('Content-Type: application/json');

try {
    $input = json_decode(file_get_contents('php://input'), true);
    $order_id = $input['order_id'] ?? null;

    if (!$order_id) {
        echo json_encode(['success' => false, 'message' => 'Missing order ID']);
        exit;
    }

    $stmt = $pdo->prepare("UPDATE orders SET status = 'Done' WHERE id = ?");
    $stmt->execute([$order_id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Order marked as Done']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No order updated — check order_id']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

// Production Error Handling
ini_set('display_errors', '0');
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/config.php';

use KHQR\BakongKHQR;

try {
    $input = file_get_contents('php://input');
    $data  = json_decode($input, true);

    if (!is_array($data)) {
        throw new Exception('Invalid JSON input');
    }

    $md5      = trim((string)($data['md5'] ?? ''));
    $order_id = trim((string)($data['order_id'] ?? ''));
    $amount   = (float)($data['amount'] ?? 0);

    if ($md5 === '' || $order_id === '' || $amount <= 0) {
        throw new Exception('Missing or invalid required parameters');
    }

    // 1) Bakong API Verification
    $token = (string)(getenv('BAKONG_TOKEN') ?: '');
    if ($token === '') {
        throw new Exception('Missing BAKONG_TOKEN in environment variables');
    }

    $bakong   = new BakongKHQR($token);
    $response = $bakong->checkTransactionByMD5($md5);

    $code = isset($response['responseCode']) ? (int)$response['responseCode'] : null;
    if ($code !== 0) {
        echo json_encode([
            'success'     => false,
            'message'     => 'Payment not confirmed',
            'bakong_code' => $response['responseCode'] ?? 'unknown'
        ]);
        exit;
    }

    // 2) Database Operations (idempotent)
    $pdo->beginTransaction();

    // already processed?
    $stmt = $pdo->prepare("SELECT payment_id FROM payments WHERE transaction_ref = ? LIMIT 1");
    $stmt->execute([$md5]);

    if ($stmt->fetch()) {
        $pdo->commit(); // no changes, but close txn cleanly
        echo json_encode(['success' => true, 'message' => 'Already processed']);
        exit;
    }

    // insert payment
    $stmt = $pdo->prepare("
        INSERT INTO payments (
            order_id,
            amount,
            payment_method,
            payment_status,
            transaction_ref,
            payment_date
        ) VALUES (?, ?, 'Bakong KHQR', 'SUCCESS', ?, CURRENT_TIMESTAMP)
    ");
    $stmt->execute([$order_id, $amount, $md5]);

    // update order status (must match your CHECK constraint)
    $stmt = $pdo->prepare("UPDATE orders SET status = 'Done' WHERE id = ?");
    $stmt->execute([$order_id]);

    $pdo->commit();

    echo json_encode(['success' => true, 'message' => 'Payment verified and saved']);
} catch (Exception $e) {
    if (isset($pdo) && $pdo instanceof PDO && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

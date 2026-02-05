<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');

// Production Error Handling
ini_set('display_errors', '0');
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/config.php'; 

use KHQR\BakongKHQR;
use Dotenv\Dotenv;

try {
    $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();

    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if (!$data) throw new Exception('Invalid JSON input');

    $md5      = trim($data['md5'] ?? '');
    $order_id = trim($data['order_id'] ?? '');
    $amount   = (float)($data['amount'] ?? 0);

    if (empty($md5) || empty($order_id) || $amount <= 0) {
        throw new Exception('Missing or invalid required parameters');
    }

    // 1. Bakong API Verification
    $token = getenv('BAKONG_TOKEN') ?? '';
    $bakong = new BakongKHQR($token);
    $response = $bakong->checkTransactionByMD5($md5);

    if (!isset($response['responseCode']) || $response['responseCode'] !== 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Payment not confirmed',
            'bakong_code' => $response['responseCode'] ?? 'unknown'
        ]);
        exit;
    }

    // 2. Database Operations
    $pdo->beginTransaction();

    // Check for existing payment using your column names
    // Changed 'id' to 'payment_id' here
    $stmt = $pdo->prepare("SELECT payment_id FROM payments WHERE transaction_ref = ? LIMIT 1");
    $stmt->execute([$md5]);
    
    if ($stmt->fetch()) {
        $pdo->rollBack();
        echo json_encode(['success' => true, 'message' => 'Already processed']);
        exit;
    }

    // Insert using your specific columns
    $stmt = $pdo->prepare("
        INSERT INTO payments (
            order_id, 
            amount, 
            payment_method, 
            payment_status, 
            transaction_ref, 
            payment_date
        ) VALUES (?, ?, 'Bakong KHQR', 'SUCCESS', ?, NOW())
    ");
    
    $stmt->execute([
        $order_id, 
        $amount, 
        $md5
    ]);

    // Update the orders table
    $stmt = $pdo->prepare("UPDATE orders SET status = 'paid' WHERE id = ?");
    $stmt->execute([$order_id]);

    $pdo->commit();

    echo json_encode(['success' => true, 'message' => 'Payment verified and saved']);

} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
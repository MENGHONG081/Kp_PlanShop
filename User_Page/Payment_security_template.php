<?php
/**
 * Payment Security Template
 * This template shows how to implement security best practices in Payment.php
 * 
 * Key improvements:
 * - CSRF token protection
 * - Input validation
 * - File upload validation
 * - Rate limiting
 * - Security logging
 */

include 'config.php';

// Require authentication
requireAuth();

// Initialize variables
$errors = [];
$success = false;
$paymentMethod = '';

// Handle payment form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
        logSecurityEvent('csrf_token_mismatch', ['action' => 'payment_attempt', 'user_id' => $_SESSION['user_id']]);
        $errors[] = 'Security validation failed. Please try again.';
    } else {
        // Check rate limiting (max 10 payment attempts per 5 minutes)
        if (!checkRateLimit('payment_' . $_SESSION['user_id'], 10, 300)) {
            logSecurityEvent('rate_limit_exceeded', ['action' => 'payment_attempt', 'user_id' => $_SESSION['user_id']]);
            $errors[] = 'Too many payment attempts. Please try again later.';
        } else {
            // Sanitize and validate inputs
            $orderId = sanitizeInput($_POST['order_id'] ?? '');
            $amount = sanitizeInput($_POST['amount'] ?? '');
            $paymentMethod = sanitizeInput($_POST['payment_method'] ?? '');
            
            // Validate order ID
            if (empty($orderId) || !is_numeric($orderId)) {
                $errors[] = 'Invalid order ID.';
            }
            
            // Validate amount
            if (empty($amount) || !is_numeric($amount) || floatval($amount) <= 0) {
                $errors[] = 'Invalid payment amount.';
            }
            
            // Validate payment method
            $validMethods = ['card', 'khqr', 'aba', 'wing'];
            if (empty($paymentMethod) || !in_array($paymentMethod, $validMethods)) {
                $errors[] = 'Invalid payment method.';
            }
            
            // Verify order belongs to current user
            if (empty($errors)) {
                $stmt = $pdo->prepare("SELECT id, user_id, total FROM orders WHERE id = ?");
                $stmt->execute([$orderId]);
                $order = $stmt->fetch();
                
                if (!$order) {
                    $errors[] = 'Order not found.';
                } elseif ($order['user_id'] != $_SESSION['user_id']) {
                    logSecurityEvent('unauthorized_payment_attempt', ['order_id' => $orderId, 'user_id' => $_SESSION['user_id']]);
                    $errors[] = 'You do not have permission to pay for this order.';
                } elseif (floatval($amount) != floatval($order['total'])) {
                    logSecurityEvent('payment_amount_mismatch', ['order_id' => $orderId, 'expected' => $order['total'], 'submitted' => $amount]);
                    $errors[] = 'Payment amount does not match order total.';
                }
            }
            
            // Handle file upload if present
            if (empty($errors) && isset($_FILES['payment_proof'])) {
                $fileErrors = validateFileUpload($_FILES['payment_proof'], ['image/jpeg', 'image/png'], 5242880);
                
                if (!empty($fileErrors)) {
                    $errors = array_merge($errors, $fileErrors);
                } else {
                    // Generate safe filename
                    $safeFilename = generateSafeFilename($_FILES['payment_proof']['name']);
                    $uploadPath = UPLOAD_PATH . 'payments/';
                    
                    // Create directory if it doesn't exist
                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0755, true);
                    }
                    
                    // Move uploaded file
                    if (!move_uploaded_file($_FILES['payment_proof']['tmp_name'], $uploadPath . $safeFilename)) {
                        logSecurityEvent('file_upload_failed', ['order_id' => $orderId, 'filename' => $safeFilename]);
                        $errors[] = 'Failed to upload payment proof.';
                    }
                }
            }
            
            // Process payment if no errors
            if (empty($errors)) {
                try {
                    // Begin transaction
                    $pdo->beginTransaction();
                    
                    // Create payment record
                    $stmt = $pdo->prepare("
                        INSERT INTO payments (order_id, user_id, amount, payment_method, status, created_at)
                        VALUES (?, ?, ?, ?, 'pending', NOW())
                    ");
                    $stmt->execute([$orderId, $_SESSION['user_id'], $amount, $paymentMethod]);
                    
                    // Log successful payment initiation
                    logSecurityEvent('payment_initiated', [
                        'order_id' => $orderId,
                        'user_id' => $_SESSION['user_id'],
                        'amount' => $amount,
                        'method' => $paymentMethod
                    ]);
                    
                    // Commit transaction
                    $pdo->commit();
                    
                    $success = true;
                    
                    // Redirect to payment verification page
                    header("Location: verify_payment.php?order_id=" . urlencode($orderId));
                    exit;
                    
                } catch (PDOException $e) {
                    // Rollback transaction
                    $pdo->rollBack();
                    
                    logSecurityEvent('payment_database_error', [
                        'order_id' => $orderId,
                        'error' => 'Database error'
                    ]);
                    
                    $errors[] = 'An error occurred processing your payment. Please try again later.';
                }
            }
        }
    }
}

// Fetch order details for current user
$userOrders = [];
if (isAuthenticated()) {
    $stmt = $pdo->prepare("
        SELECT id, order_number, total, status, created_at
        FROM orders
        WHERE user_id = ?
        ORDER BY created_at DESC
        LIMIT 10
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $userOrders = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Payment - KP Plant Shop</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <h1 class="text-3xl font-bold mb-8">Payment</h1>
            
            <!-- Error Messages -->
            <?php if (!empty($errors)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6" role="alert">
                <strong>Error:</strong>
                <ul class="list-disc list-inside mt-2">
                    <?php foreach ($errors as $error): ?>
                    <li><?php echo escapeHTML($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
            
            <!-- Success Message -->
            <?php if ($success): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6" role="alert">
                <strong>Success!</strong> Your payment has been initiated. Redirecting...
            </div>
            <?php endif; ?>
            
            <!-- Payment Form -->
            <form method="POST" action="Payment_security_template.php" enctype="multipart/form-data" class="bg-white rounded-lg shadow-md p-6 space-y-6">
                <!-- CSRF Token -->
                <input type="hidden" name="csrf_token" value="<?php echo escapeHTML(generateCSRFToken()); ?>">
                
                <!-- Order Selection -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Select Order</label>
                    <select name="order_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="">-- Select an order --</option>
                        <?php foreach ($userOrders as $order): ?>
                        <option value="<?php echo escapeHTML($order['id']); ?>">
                            Order #<?php echo escapeHTML($order['order_number']); ?> - 
                            $<?php echo escapeHTML(number_format($order['total'], 2)); ?> 
                            (<?php echo escapeHTML($order['status']); ?>)
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <!-- Amount -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Amount</label>
                    <input type="number" name="amount" step="0.01" min="0" placeholder="0.00" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"/>
                </div>
                
                <!-- Payment Method -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Payment Method</label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" name="payment_method" value="card" required class="mr-2"/>
                            <span>Credit/Debit Card</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="payment_method" value="khqr" required class="mr-2"/>
                            <span>Bakong KHQR</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="payment_method" value="aba" required class="mr-2"/>
                            <span>ABA Transfer</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="payment_method" value="wing" required class="mr-2"/>
                            <span>Wing Money</span>
                        </label>
                    </div>
                </div>
                
                <!-- Payment Proof Upload -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Payment Proof (Optional)</label>
                    <input type="file" name="payment_proof" accept="image/jpeg,image/png" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"/>
                    <p class="text-xs text-gray-500">Accepted formats: JPEG, PNG. Max size: 5MB</p>
                </div>
                
                <!-- Submit Button -->
                <button type="submit" class="w-full bg-green-600 text-white font-bold py-3 rounded-lg hover:bg-green-700 transition-colors">
                    Proceed to Payment
                </button>
            </form>
            
            <!-- Security Notice -->
            <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <p class="text-sm text-blue-800">
                    <strong>Security Notice:</strong> This form is protected with CSRF tokens and SSL encryption. 
                    Your payment information is secure and will never be stored in plain text.
                </p>
            </div>
        </div>
    </div>
</body>
</html>

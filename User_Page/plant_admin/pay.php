<?php
require __DIR__ . '/auth.php';
// Make sure $pdo is available
//$user_id = $_SESSION['user_id'];

// Fetch all payments for the user's orders
$stmt = $pdo->prepare("
    SELECT 
        p.payment_id,
        p.order_id,
        p.amount,
        p.payment_method,
        p.payment_status,
        p.transaction_ref,
        p.payment_date,
        o.total AS order_total
    FROM payments p
    JOIN orders o ON p.order_id = o.id
    ORDER BY p.payment_date DESC
");
$stmt->execute();
$payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Payments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        .table thead {
            background-color: #0d6efd;
            color: white;
        }
    </style>
</head>
<body class="bg-light">
<?php include 'plant_admin/nav.php'; ?>

<div class="container py-5">
    <div class="d-flex align-items-center mb-4">
        <i class="fa fa-credit-card fa-3x text-primary me-3"></i>
        <h1 class="h3 fw-bold mb-0">User Payment History</h1>
    </div>

    <?php if (empty($payments)): ?>
        <div class="text-center py-5 bg-white rounded-4 shadow-sm">
            <i class="fa fa-receipt fa-5x text-muted mb-4"></i>
            <p class="lead text-muted">No payments recorded yet.</p>
            <a href="my-orders.php" class="btn btn-primary">View My Orders</a>
        </div>
    <?php else: ?>
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>Payment ID</th>
                                <th>Order #</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Status</th>
                                <th>Transaction Ref</th>
                                <th>Date & Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($payments as $payment): ?>
                                <tr>
                                    <td><strong>#<?= htmlspecialchars($payment['payment_id']) ?></strong></td>
                                    <td>
                                        <a href="pay.php?id=<?= $payment['order_id'] ?>" class="text-decoration-none">
                                            #<?= htmlspecialchars($payment['order_id']) ?>
                                        </a>
                                    </td>
                                    <td class="fw-bold text-success">
                                        $<?= number_format($payment['amount'], 2) ?>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars(ucfirst(str_replace('_', ' ', $payment['payment_method']))) ?>
                                    </td>
                                    <td>
                                        <?php
                                        $status = strtolower($payment['payment_status']);
                                        $badgeClass = $status === 'success' ? 'bg-success' :
                                                     ($status === 'paid' ? 'bg-warning text-dark' :
                                                     ($status === 'failed' ? 'bg-danger' : 'bg-secondary'));
                                        ?>
                                        <span class="badge <?= $badgeClass ?> py-2 px-3">
                                            <?= htmlspecialchars(ucfirst($payment['payment_status'])) ?>
                                        </span>
                                    </td>
                                    <td class="small text-muted">
                                        <?= htmlspecialchars($payment['transaction_ref']) ?>
                                    </td>
                                    <td>
                                        <?= date('M j, Y <br> h:i A', strtotime($payment['payment_date'])) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
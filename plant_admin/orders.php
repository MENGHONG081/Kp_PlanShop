<?php
require 'auth.php';
//require 'config.php'; // Make sure $pdo is available
//$user_id = $_SESSION['user_id']; who to get user id

// Join orders with payments to check payment status
$stmt = $pdo->prepare("
    SELECT 
        o.id AS order_id,
        o.user_id,
        o.total,
        o.status AS order_status,
        o.created_at,
        p.payment_id,
        p.amount,
        p.payment_method,
        p.payment_status,
        p.transaction_ref,
        p.payment_date
    FROM orders o
    LEFT JOIN payments p 
        ON o.id = p.order_id AND p.payment_status = 'success'
    ORDER BY o.id DESC
");
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        .btn-pay { min-width: 110px; }
        .status-paid { font-weight: bold; }
    </style>
</head>
<body class="bg-light">
<?php include 'nav.php'; ?>

<div class="container py-5">
    <div class="d-flex align-items-center mb-4">
        <i class="fa fa-box-open fa-3x text-primary me-3"></i>
        <h1 class="h3 fw-bold mb-0">User Orders</h1>
        <button class="btn btn-outline-primary btn-sm ms-auto" onclick="location.href='pay.php'">
            <i class="fa fa-credit-card me-1"></i> Payment Status
    </div>

    <?php if (empty($orders)): ?>
        <div class="text-center py-5">
            <i class="fa fa-shopping-bag fa-5x text-muted mb-4"></i>
            <p class="lead text-muted">You haven't placed any orders yet.</p>
            <a href="shop.php" class="btn btn-primary btn-lg">Start Shopping</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle bg-white shadow-sm rounded-4 overflow-hidden">
                <thead class="table-primary">
                    <tr>
                        <th>Order #</th>
                        <th>Total</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th class="text-center">Payment</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <?php $isPaid = !empty($order['payment_status']) && $order['payment_status'] === 'success'; ?>
                        <tr>
                            <td><strong>#<?= htmlspecialchars($order['order_id']) ?></strong></td>
                            <td>$<?= number_format($order['total'], 2) ?></td>
                            <td><?= date('M j, Y', strtotime($order['created_at'])) ?></td>
                            <td>
                                <?php
                                        $status = strtolower($order['order_status'] ?? ''); 

                                    $badgeClass1 = $status === 'cancelled' ? 'bg-danger' :
                                                ($status === 'pending'   ? 'bg-warning text-dark' :
                                                ($status === 'done'      ? 'bg-success text-light' :
                                                ($status === ''          ? 'bg-info text-dark' : 'bg-secondary text-light')));
                                       ?>
                                <span class="badge <?= $badgeClass1 ?> py-2 px-3">
                                    <?= htmlspecialchars(ucfirst($order['order_status'])) ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <?php if ($isPaid): ?>
                                    <span class="text-success fw-bold status-paid">
                                        <i class="fa fa-check-circle me-2"></i> Paid Successfully
                                    </span>
                                <?php else: ?>
                                    <button class="btn btn-success btn-sm btn-pay me-2"
                                            data-order-id="<?= $order['order_id'] ?>"
                                            data-amount="<?= $order['total'] ?>">
                                        <i class="fa fa-credit-card me-1"></i> Pay Now
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm"
                                            onclick="cancelOrder(<?= $order['order_id'] ?>)">
                                        <i class="fa fa-times me-1"></i> Cancel
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Success Toast -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="payToast" class="toast align-items-center text-white bg-success border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body">
                <i class="fa fa-check me-2"></i> Payment recorded successfully!
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Use the correct path - change if your file is in a different location
const PAYMENT_URL = 'pay-order.php';  // ← Make sure this matches your file name/location

document.querySelectorAll('.btn-pay').forEach(btn => {
    btn.addEventListener('click', function() {
        const orderId = this.dataset.orderId;
        const amount = this.dataset.amount;
        const button = this;
        const row = button.closest('tr');

        button.disabled = true;
        button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';

        fetch(PAYMENT_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ order_id: orderId, amount: amount })
        })
        .then(response => {
            if (!response.ok) throw new Error('HTTP ' + response.status);
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Update status column
                row.cells[3].innerHTML = '<span class="badge bg-info py-2 px-3">Processing</span>'; // or keep original
                // Update payment column
                row.cells[4].innerHTML = 
                    '<span class="text-success fw-bold status-paid">' +
                    '<i class="fa fa-check-circle me-2"></i> Paid Successfully</span>';

                const toast = new bootstrap.Toast(document.getElementById('payToast'));
                toast.show();
            } else {
                alert('Error: ' + (data.message || 'Unknown error'));
                button.disabled = false;
                button.innerHTML = '<i class="fa fa-credit-card me-1"></i> Pay Now';
            }
        })
        .catch(err => {
            console.error('Fetch error:', err);
            alert('Connection error. Check if process-payment.php exists and is in the correct folder.');
            button.disabled = false;
            button.innerHTML = '<i class="fa fa-credit-card me-1"></i> Pay Now';
        });
    });
});
// Cancel order function
function cancelOrder(orderId) {
    if (!confirm('Cancel this order?')) return;

    fetch('cancel-order.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ order_id: orderId })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert('Order cancelled');
            location.reload(); // refresh page to see updated status
        } else {
            alert('Error: ' + data.message);
        }
    });
}
// edit oder status wnen cliick pay now is stored in the database Done
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-pay').forEach(btn => {
        btn.addEventListener('click', function () {
            const orderId = this.dataset.orderId;
            const amount  = this.dataset.amount;

            if (!confirm('Mark this order as Done?')) return;

            fetch('ClickPay.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ order_id: orderId, amount: amount })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload(); // refresh to show updated status
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(err => alert('Request failed: ' + err));
        });
    });
});

</script>
</body>
</html>
<?php
ob_start(); 
if (session_status() === PHP_SESSION_NONE) { session_start(); } 
require 'auth.php';
// If not logged in, redirect to login page
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();

}
$fullname = $_SESSION['admin_name'] ?? 'Admin';
$successMessage = ''; // For any future flashes
$errorMessage = '';

// Fetch stats in one query for efficiency
try {
    $statsStmt = $pdo->query("
        SELECT
            (SELECT COUNT(*) FROM products) AS total_products,
            (SELECT COUNT(*) FROM categories) AS total_categories,
            (SELECT COUNT(*) FROM orders) AS total_orders,
            (SELECT COUNT(*) FROM users) AS total_users  -- Assuming 'userdata' table from earlier
    ");
    $stats = $statsStmt->fetch(PDO::FETCH_ASSOC);

    // Low stock products (<5)
    $lowStockStmt = $pdo->prepare("SELECT id, name, stock FROM products WHERE stock < 5 ORDER BY stock ASC");
    $lowStockStmt->execute();
    $lowStock = $lowStockStmt->fetchAll(PDO::FETCH_ASSOC);

    // Recent orders (last 5, with user join)
    $recentOrdersStmt = $pdo->prepare("
        SELECT o.id, o.total, o.status, o.created_at, u.fullname AS customer_name
        FROM orders o
        LEFT JOIN users u ON u.id = o.user_id
        ORDER BY o.id DESC
        LIMIT 5
    ");
    $recentOrdersStmt->execute();
    $recentOrders = $recentOrdersStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $errorMessage = 'Error loading dashboard data. Please refresh.';
    error_log("Dashboard query error: " . $e->getMessage());
    $stats = ['total_products' => 0, 'total_categories' => 0, 'total_orders' => 0, 'total_users' => 0];
    $lowStock = [];
    $recentOrders = [];
}

// Check for flash messages
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $successMessage = 'Dashboard updated successfully!';
}
// Prepare data for daily revenue chart
$sql = "
    SELECT 
        d.day_num,
        CONCAT(d.day_num, ' ', MONTHNAME(CURDATE())) AS label,
        COALESCE(SUM(p.amount), 0) AS total_amount
    FROM (
        SELECT 1 AS day_num UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION
        SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION SELECT 10 UNION
        SELECT 11 UNION SELECT 12 UNION SELECT 13 UNION SELECT 14 UNION SELECT 15 UNION
        SELECT 16 UNION SELECT 17 UNION SELECT 18 UNION SELECT 19 UNION SELECT 20 UNION
        SELECT 21 UNION SELECT 22 UNION SELECT 23 UNION SELECT 24 UNION SELECT 25 UNION
        SELECT 26 UNION SELECT 27 UNION SELECT 28 UNION SELECT 29 UNION SELECT 30 UNION
        SELECT 31
    ) AS d
    LEFT JOIN payments p 
        ON DAY(p.payment_date) = d.day_num
       AND YEAR(p.payment_date) = YEAR(CURDATE())
       AND MONTH(p.payment_date) = MONTH(CURDATE())
     AND (
    p.payment_status = 'success'
    OR p.payment_status = 'SUCCESS'
    OR p.payment_status = 'PAID'
)
    GROUP BY d.day_num
    ORDER BY d.day_num
";

$stmt = $pdo->query($sql);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Prepare arrays for Plotly
$labels = [];
$amounts = [];
foreach ($rows as $row) {
    $labels[] = $row['label'];          // "1 January", "2 January", ...
    $amounts[] = (float)$row['total_amount']; // daily totals
}
// Convert to JSON for JavaScript
$labels_json = json_encode($labels);
$amounts_json = json_encode($amounts);
$hasData = array_sum($amounts) > 0;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Plant Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../User_Page/Home.css"> <!-- Retain if custom -->
    <link rel="stylesheet" href="assets/style.css"> <!-- Retain if custom -->
    <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
    <style>
        body {
            background: url('uploads/bnghome.png') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .dashboard-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            overflow: hidden;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
        }
        .stat-icon {
            font-size: 2.5rem;
            opacity: 0.8;
        }
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #198754;
        }
        .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .low-stock-badge {
            background: #dc3545;
            color: white;
        }
        .order-status {
            text-transform: capitalize;
        }
        .order-status.pending { color: #ffc107; }
        .order-status.processing { color: #17a2b8; }
        .order-status.completed { color: #198754; }
        .order-status.cancelled { color: #dc3545; }
        .animated-welcome {
            font-size: 1.6rem;
            font-weight: 700;
            background: linear-gradient(90deg, #198754, #20c997, #198754);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: shimmer 2s ease-in-out infinite alternate;
            display: inline-block;
        }
        @keyframes shimmer {
            0% { background-position: -200% center; }
            100% { background-position: 200% center; }
        }
        .welcome-desc {
            font-size: 1.1rem;
            color: #495057;
            margin-top: 0.5rem;
        }
        .shop-link {
            background: linear-gradient(135deg, #198754, #20c997);
            border: none;
            border-radius: 0.5rem;
            padding: 0.75rem 1.5rem;
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s;
        }
        .shop-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(25, 135, 84, 0.3);
            color: white;
        }

    .glass {
    background: rgba(242, 81, 41, 0.15);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(228, 32, 6, 0.2);
    border-radius: 16px;
    overflow: hidden;
  }
  .table-hover tbody tr:hover {
    background-color: rgba(216, 22, 22, 0.05);
  }
  .icon-wrap {
    transition: transform 0.3s ease;
  }
  .icon-wrap:hover {
    transform: scale(1.1);
  }
    </style>
</head>
<body>
    <?php include 'nav.php'; ?>

    <div class="container mt-4">
        <!-- Success/Error Alerts -->
        <?php if ($successMessage): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i><?php echo htmlspecialchars($successMessage); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if ($errorMessage): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i><?php echo htmlspecialchars($errorMessage); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Welcome Section -->
         <div class="d-flex flex-row-reverse align-items-center  mb-4">
             <a href="../User_Page/index1.php" class="shop-link mb-4 d-inline-block ms-4">
              <i class="fas fa-store me-1"></i>Go to My Shop
              </a>
              <h3 class="mb-3" style="font-weight: bold; color: #198754;">
        <i class="fas fa-user-circle me-1"></i> Hi  
        <strong style="color: #f8fbfaff;"><?php echo htmlspecialchars($fullname ?? 'N/A'); ?></strong>
    </h3>
</div>
        <div class="row mb-4">
            <div class="col-12 text-center">
              
                <h1 class="mb-3" style="color: #198754; font-weight: bold;">Dashboard Overview</h1>
                <div class="animated-welcome">Welcome back! <strong style="color: #f8fbfaff;"><?php echo htmlspecialchars($fullname ?? 'N/A'); ?></strong> How are you today?</div>
                <p class="welcome-desc">Monitor your plant shop's performance at a glance. 🌿</p>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="dashboard-card text-center p-3" onclick="location.href='/PLANT_PROJECT/plant_admin/products.php'">
                    <i class="fas fa-boxes stat-icon text-primary onclick"></i>
                    <div class="stat-number"><?php echo $stats['total_products']; ?></div>
                    <div class="stat-label">Total Products</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="dashboard-card text-center p-3" onclick="location.href='/PLANT_PROJECT/plant_admin/categories.php'">
                    <i class="fas fa-tags stat-icon text-success"></i>
                    <div class="stat-number"><?php echo $stats['total_categories']; ?></div>
                    <div class="stat-label">Total Categories</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="dashboard-card text-center p-3" onclick="location.href='/PLANT_PROJECT/plant_admin/orders.php'">
                    <i class="fas fa-shopping-cart stat-icon text-info"></i>
                    <div class="stat-number"><?php echo $stats['total_orders']; ?></div>
                    <div class="stat-label">Total Orders</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="dashboard-card text-center p-3" onclick="location.href='/PLANT_PROJECT/plant_admin/users.php'">
                    <i class="fas fa-users stat-icon text-warning"></i>
                    <div class="stat-number"><?php echo $stats['total_users']; ?></div>
                    <div class="stat-label">Total Users</div>
                </div>
            </div>
        </div>
        
         <section class="container my-5">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-11">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

                <!-- Header -->
                <div class="card-header bg-gradient bg-primary text-white py-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="mb-0 fw-semibold">
                            <i class="fas fa-chart-line me-2"></i>
                            Daily Revenue — <?= date('F Y') ?>
                        </h4>
                        <span class="badge bg-light text-primary fw-semibold px-3 py-2">
                            This Month
                        </span>
                    </div>
                </div>

                <!-- Body -->
                <div class="card-body p-4 position-relative" style="min-height: 420px;">

                    <!-- Chart -->
                    <div id="myPlot" class="w-100" style="height: 360px;"></div>

                    <!-- Empty State -->
                    <?php if (!$hasData): ?>
                        <div class="position-absolute top-50 start-50 translate-middle text-center text-muted px-4">
                            <i class="fas fa-chart-area fa-4x mb-4 opacity-25"></i>
                            <h5 class="fw-semibold mb-2">No Revenue Data Available</h5>
                            <p class="text-secondary mb-1">
                                Sales data will appear once orders are completed.
                            </p>
                            <small class="text-muted">
                                Today: <?= date('j F Y') ?>
                            </small>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</section>
        <div class="row">
        <!-- Low-Stock Card – Modern Table Version with Glassmorphism -->
                <div class="col-md-6 mb-4">
                <div class="card glass border-0 shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center bg-transparent border-0">
                    <h5 class="mb-0 text-warning fw-bold "><i class="fas fa-exclamation-triangle me-2 text-warning fw-bold   "></i>Low Stock Alert</h5>
                    <span class="badge bg-warning text-dark fs-6"><?= count($lowStock) ?></span>
                    </div>

                    <div class="card-body p-0">
                    <?php if (empty($lowStock)): ?>
                        <div class="text-center py-5">
                        <div class="icon-wrap bg-success-subtle rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                            <i class="fas fa-seedling fa-2x text-success"></i>
                        </div>
                        <p class="text-success fw-bold mb-0">All stocked up! 🌿</p>
                        <small class="text-muted">No products are running low right now.</small>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                            <tr>
                                <th class="border-0 ps-4">Product Name</th>
                                <th class="border-0 text-center">Stock Left</th>
                                <th class="border-0 text-end pe-4">Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($lowStock as $p): 
                                $stock = intval($p['stock']);
                                $isCritical = $stock <= 5; // Adjust threshold as needed
                            ?>
                                <tr class="border-0">
                                <td class="ps-4 fw-medium"><?= htmlspecialchars($p['name']) ?></td>
                                <td class="text-center">
                                    <span class="fw-bold <?= $isCritical ? 'text-danger' : 'text-warning' ?>">
                                    <?= $stock ?>
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <span class="badge <?= $isCritical ? 'bg-danger' : 'bg-warning' ?> text-dark">
                                    <?= $isCritical ? 'Critical' : 'Low' ?>
                                    </span>
                                </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                        </div>
                    <?php endif; ?>
                    </div>
                </div>
                </div>
            <!-- Recent Orders -->
            <div class="col-md-6 mb-4">
                <div class="dashboard-card">
                    <div class="card-header bg-info text-white  py-2 d-flex align-items-center justify-content-center fw-bold">
                        <i class="fas fa-clock me-2"></i>Recent Orders (Last 5)
                    </div>
                    <div class="card-body p-0">
                        <?php if (empty($recentOrders)): ?>
                            <div class="text-center text-muted py-3">
                                <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                                <p class="mb-0">No recent orders yet.</p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead class="table-light">
                                        <tr><th>#</th><th>Customer</th><th>Total</th><th>Status</th><th>Date</th></tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($recentOrders as $o): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($o['id']); ?></td>
                                                <td><?php echo htmlspecialchars($o['customer_name'] ?? 'Guest'); ?></td>
                                                <td>$<?php echo number_format($o['total'], 2); ?></td>
                                                <td><span class="order-status <?php echo strtolower($o['status']); ?>"><?php echo htmlspecialchars($o['status']); ?></span></td>
                                                <td><?php echo date('M j, Y', strtotime($o['created_at'])); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- Font Awesome for icons (optional but recommended) -->
    <script>
        // Auto-dismiss alerts after 5s
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Optional: Animate stats on load
        document.addEventListener('DOMContentLoaded', () => {
            const stats = document.querySelectorAll('.stat-number');
            stats.forEach(stat => {
                const target = parseInt(stat.textContent);
                let current = 0;
                const increment = target / 50; // Smooth animation
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        stat.textContent = target;
                        clearInterval(timer);
                    } else {
                        stat.textContent = Math.floor(current);
                    }
                }, 20);
            });
        });
        // Payment Line Chart
const labels = <?= $labels_json ?>;
const amounts = <?= $amounts_json ?>;

const data = [{
    x: labels,
    y: amounts,
    type: "scatter",
    mode: "lines+markers",
    line: {
        color: "#198754",
        width: 3,
        shape: "spline"
    },
    marker: {
        size: 7,
        color: "#198754",
        line: { width: 2, color: "#fff" }
    },
    hovertemplate: 
        "<b>Day %{x}</b><br>" +
        "Revenue: $%{y:.2f}<extra></extra>"
}];

const layout = {
    margin: { t: 50, r: 30, l: 60, b: 60 },
    xaxis: {
        title: "",
        showgrid: false,
        zeroline: false
    },
    yaxis: {
        title: "Revenue (USD)",
        rangemode: "tozero",
        gridcolor: "#e9ecef"
    },
    font: {
        family: "Inter, system-ui, -apple-system, sans-serif",
        size: 13
    },
    paper_bgcolor: "transparent",
    plot_bgcolor: "transparent",
    hoverlabel: {
        bgcolor: "#198754",
        bordercolor: "#198754",
        font: { color: "#fff" }
    },
    title: {
        text: "Daily Revenue Trend",
        font: { size: 18, weight: 600 }
    }
};

Plotly.newPlot("myPlot", data, layout, { responsive: true });
</script>
</body>
</html>
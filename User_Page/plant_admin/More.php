<?php
require __DIR__ . '/auth.php'; // Optional: remove if this page is public
// require 'db.php'; // Make sure $pdo is available

// 1. Discount Products
$stmt = $pdo->query("
    SELECT p.id, p.name, p.price, p.image, 
           d.discount_percent, d.price_after_discount, d.description AS discount_desc
    FROM products p
    JOIN discounts d ON p.id = d.product_id
    WHERE d.discount_percent > 0
    ORDER BY d.created_at DESC
    LIMIT 8
");
$discountProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 2. New Arrivals (last 30 days)
$stmt = $pdo->prepare("
    SELECT id, name, price, image, created_at
    FROM products
    WHERE created_at >= NOW() - INTERVAL '30 days'
    ORDER BY created_at DESC
    LIMIT 8
");
$stmt->execute();
$newArrivals = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt->execute();
$newArrivals = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 3. Best Sellers (total qty sold >= 10)
$stmt = $pdo->query("
    SELECT 
        p.id, 
        p.name, 
        p.price, 
        p.image,
        SUM(oi.qty) AS total_sold
    FROM products p
    JOIN order_items oi ON p.id = oi.product_id
    GROUP BY p.id
    HAVING total_sold >= 10
    ORDER BY total_sold DESC
    LIMIT 8
");
$bestSellers = $stmt->fetchAll(PDO::FETCH_ASSOC);
// insert db  connection Discount save
if (isset($_POST['save_discount'])) {

    $product_id = (int) $_POST['product_id'];
    $discount_percent = (float) $_POST['discount_percent'];
    $price_after_discount = (float) $_POST['price_after_discount'];
    $description = $_POST['description'];
    $discount_date = $_POST['discount_date'];

    $stmt = $pdo->prepare("
        INSERT INTO discounts
        (product_id, discount_percent, price_after_discount, description, discount_date)
        VALUES
        (:product_id, :discount_percent, :price_after_discount, :description, :discount_date)
    ");

    $success = $stmt->execute([
        ':product_id' => $product_id,
        ':discount_percent' => $discount_percent,
        ':price_after_discount' => $price_after_discount,
        ':description' => $description,
        ':discount_date' => $discount_date
    ]);

    if ($success) {
        header("Location: discount_list.php?success=1");
        exit;
    } else {
        echo "Error saving discount";
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Special Offers & More</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        .section-title {
            border-bottom: 3px solid #198754;
            padding-bottom: 8px;
            margin-bottom: 30px;
        }
        .product-card {
            transition: transform 0.3s, box-shadow 0.3s;
            border: none;
            border-radius: 15px;
            overflow: hidden;
        }
        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }
        .product-img {
            height: 220px;
            object-fit: cover;
        }
        .discount-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background: #dc3545;
            color: white;
            padding: 5px 12px;
            border-radius: 50px;
            font-weight: bold;
        }
        .price-old {
            text-decoration: line-through;
            color: #999;
        }
        html {
    scroll-behavior: smooth;
             }

    </style>
</head>
<body class="bg-light">
<?php include 'nav.php'; ?>

<div class="container py-5">

    <!-- Discount Products -->
    <div class="mb-5" id ="discount-products">
        <button class="btn btn-sm btn-outline-danger mb-3" onclick="window.location.href='products.php'">
            <i class="fa fa-arrow-left me-2"></i> Back
        </button>
        <h2 class="section-title text-danger fw-bold">
            <i class="fa fa-tags me-3"></i> Discount Products
        </h2>
        
        <?php if (empty($discountProducts)): ?>
            <p class="text-center text-muted">No discounted products at the moment.</p>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($discountProducts as $p): ?>
                    <?php
                    $finalPrice = $p['price_after_discount'] ?? ($p['price'] * (1 - $p['discount_percent']/100));
                    ?>
                    <div class="col-md-3 col-sm-6">
                        <div class="card product-card h-100 position-relative">
                            <span class="discount-badge">-<?= $p['discount_percent'] ?>%</span>
                            <img src="uploads/<?= htmlspecialchars($p['image']) ?>" class="card-img-top product-img" alt="<?= htmlspecialchars($p['name']) ?>">
                            <div class="card-body text-center">
                                <h5 class="card-title fw-bold"><?= htmlspecialchars($p['name']) ?></h5>
                                <p class="text-success fw-bold fs-4 mb-1">
                                    $<?= number_format($finalPrice, 2) ?>
                                </p>
                                <p class="price-old">$<?= number_format($p['price'], 2) ?></p>
                                <a href="plant_admin/login.php?redirect=product.php?id=<?= $p['id'] ?>" class="btn btn-danger btn-sm">
                                    <i class="fa fa-shopping-cart me-1"></i> View Deal
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- New Arrivals -->
    <div class="mb-5" id ="new-arrivals">
        <h2 class="section-title text-primary fw-bold">
            <i class="fa fa-truck-loading me-3"></i> New Arrivals
        </h2>
        <?php if (empty($newArrivals)): ?>
            <p class="text-center text-muted">No new products in the last 30 days.</p>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($newArrivals as $p): ?>
                    <div class="col-md-3 col-sm-6">
                        <div class="card product-card h-100">
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
                                NEW
                            </span>
                            <img src="uploads/<?= htmlspecialchars($p['image']) ?>" class="card-img-top product-img" alt="<?= htmlspecialchars($p['name']) ?>">
                            <div class="card-body text-center">
                                <h5 class="card-title fw-bold"><?= htmlspecialchars($p['name']) ?></h5>
                                <p class="text-primary fw-bold fs-4">$<?= number_format($p['price'], 2) ?></p>
                                <a href="plant_admin/product.php?id=<?= $p['id'] ?>" class="btn btn-outline-primary btn-sm">
                                    View Product
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Best Sellers -->
    <div class="mb-5" id ="best-sellers">
        <h2 class="section-title text-warning fw-bold">
            <i class="fa fa-trophy me-3"></i> Best Sellers
        </h2>
        <?php if (empty($bestSellers)): ?>
            <p class="text-center text-muted">No best-selling products yet.</p>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($bestSellers as $p): ?>
                    <div class="col-md-3 col-sm-6">
                        <div class="card product-card h-100 position-relative">
                            <span class="position-absolute top-0 end-0 badge bg-warning text-dark">
                                <i class="fa fa-star"></i> Hot
                            </span>
                            <img src="uploads/<?= htmlspecialchars($p['image']) ?>" class="card-img-top product-img" alt="<?= htmlspecialchars($p['name']) ?>">
                            <div class="card-body text-center">
                                <h5 class="card-title fw-bold"><?= htmlspecialchars($p['name']) ?></h5>
                                <p class="text-dark fw-bold fs-4">$<?= number_format($p['price'], 2) ?></p>
                                <small class="text-muted">Sold: <?= $p['total_sold'] ?> units</small><br>
                                <a href="plant_admin/product.php?id=<?= $p['id'] ?>" class="btn btn-warning btn-sm mt-2">
                                    Buy Now
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

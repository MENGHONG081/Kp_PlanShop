<?php
require 'config.php'; // Your PDO connection ($pdo)
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Load products
$stmt = $pdo->prepare("SELECT id, name, price, image, description FROM products");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* ==================== AJAX: Add to Cart ==================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = (int)$_POST['product_id'];
    $found = false;

    foreach ($products as $product) {
        if ($product['id'] === $product_id) {
            // Check if already in cart
            foreach ($_SESSION['cart'] as &$item) {
                if ($item['id'] === $product_id) {
                    $item['quantity']++;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $_SESSION['cart'][] = [
                    'id' => $product['id'],
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'image' => $product['image'],
                    'description' => $product['description'],
                    'quantity' => 1
                ];
            }
            break;
        }
    }

    $cart_count = array_sum(array_column($_SESSION['cart'], 'quantity'));

    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'cart_count' => $cart_count]);
    exit;
}

// Initial cart count for page load
$cart_count = array_sum(array_column($_SESSION['cart'], 'quantity'));
// upload slider and logo 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!empty($_FILES['image']['name'])) {
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/" . $_FILES['image']['name']);
    }

    if (!empty($_FILES['video']['name'])) {
        move_uploaded_file($_FILES['video']['tmp_name'], "uploads/" . $_FILES['video']['name']);
    }

    echo "Upload Successful";
}

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
    SELECT id, name, price, image, created_at, description
    FROM products
    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
    ORDER BY created_at DESC
    LIMIT 4
");
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
    LIMIT 4
");
$bestSellers = $stmt->fetchAll(PDO::FETCH_ASSOC);
// show feedback form submission success message
$stmt = $pdo->prepare("
    SELECT 
    f.id,
    f.comments,
    f.rating,
    f.visible,
    f.submitted_at,
    u.email,
    u.fullname
FROM customer_feedback f
LEFT JOIN users u ON f.user_id = u.id
ORDER BY f.submitted_at DESC
LIMIT 4;
");
$stmt->execute();
$feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// silde page user
if (isset($_GET['imgUrl']) && isset($_GET['title']) && isset($_GET['desc'])) {
    $imgUrl = $_GET['imgUrl'];
    $title  = $_GET['title'];
    $desc   = $_GET['desc'];
}


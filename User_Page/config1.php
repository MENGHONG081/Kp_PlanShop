<?php
// config1.php (or whatever file you require from index1.php)
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'config.php'; // must create $pdo (pgsql)

// ==================== CART INIT ====================
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// ==================== LOAD PRODUCTS (for page + add_to_cart) ====================
$stmt = $pdo->prepare("SELECT id, name, price, image, description FROM products ORDER BY id DESC");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ==================== AJAX: ADD TO CART ====================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    header('Content-Type: application/json; charset=utf-8');

    $product_id = (int)($_POST['product_id'] ?? 0);
    if ($product_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid product id']);
        exit;
    }

    // Find product from $products
    $productFound = null;
    foreach ($products as $p) {
        if ((int)$p['id'] === $product_id) {
            $productFound = $p;
            break;
        }
    }

    if (!$productFound) {
        echo json_encode(['success' => false, 'message' => 'Product not found']);
        exit;
    }

    // Update cart
    $foundInCart = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ((int)$item['id'] === $product_id) {
            $item['quantity'] = (int)$item['quantity'] + 1;
            $foundInCart = true;
            break;
        }
    }
    unset($item);

    if (!$foundInCart) {
        $_SESSION['cart'][] = [
            'id'          => (int)$productFound['id'],
            'name'        => (string)$productFound['name'],
            'price'       => (float)$productFound['price'],
            'image'       => (string)$productFound['image'],
            'description' => (string)$productFound['description'],
            'quantity'    => 1
        ];
    }

    $cart_count = 0;
    foreach ($_SESSION['cart'] as $it) {
        $cart_count += (int)($it['quantity'] ?? 0);
    }

    echo json_encode(['success' => true, 'cart_count' => $cart_count]);
    exit;
}

// ==================== UPLOAD: SLIDER / LOGO ====================
// IMPORTANT: avoid "if POST then upload" because it breaks other POST requests.
// Use a hidden flag <input type="hidden" name="upload_slider" value="1">
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_slider'])) {

    // Ensure upload folder exists
    $uploadDir = __DIR__ . "/uploads/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $saved = [];

    // Image
    if (!empty($_FILES['image']['name']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
        $imgName = basename($_FILES['image']['name']);
        $imgPath = $uploadDir . $imgName;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $imgPath)) {
            $saved['image'] = "uploads/" . $imgName;
        }
    }

    // Video
    if (!empty($_FILES['video']['name']) && is_uploaded_file($_FILES['video']['tmp_name'])) {
        $vidName = basename($_FILES['video']['name']);
        $vidPath = $uploadDir . $vidName;
        if (move_uploaded_file($_FILES['video']['tmp_name'], $vidPath)) {
            $saved['video'] = "uploads/" . $vidName;
        }
    }

    // You can redirect or return JSON
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'success' => true,
        'message' => 'Upload Successful',
        'saved'   => $saved
    ]);
    exit;
}

// ==================== CART COUNT (page load) ====================
$cart_count = 0;
foreach ($_SESSION['cart'] as $it) {
    $cart_count += (int)($it['quantity'] ?? 0);
}

// ==================== 1) DISCOUNT PRODUCTS ====================
// PostgreSQL-safe query
$stmt = $pdo->query("
    SELECT
        p.id, p.name, p.price, p.image,
        d.discount_percent, d.price_after_discount, d.description AS discount_desc
    FROM products p
    JOIN discounts d ON p.id = d.product_id
    WHERE d.discount_percent > 0
    ORDER BY d.created_at DESC
    LIMIT 8
");
$discountProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ==================== 2) NEW ARRIVALS (last 30 days) ====================
// FIXED: PostgreSQL uses NOW() - INTERVAL '30 days'
$stmt = $pdo->prepare("
    SELECT id, name, price, image, created_at, description
    FROM products
    WHERE created_at >= (NOW() - INTERVAL '30 days')
    ORDER BY created_at DESC
    LIMIT 4
");
$stmt->execute();
$newArrivals = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ==================== 3) BEST SELLERS ====================
// If your order_items table uses qty, this is OK for PostgreSQL.
// If it uses quantity instead, change oi.qty -> oi.quantity
$stmt = $pdo->query("
    SELECT
        p.id,
        p.name,
        p.price,
        p.image,
        SUM(oi.qty) AS total_sold
    FROM products p
    JOIN order_items oi ON p.id = oi.product_id
    GROUP BY p.id, p.name, p.price, p.image
    HAVING SUM(oi.qty) >= 10
    ORDER BY total_sold DESC
    LIMIT 4
");
$bestSellers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ==================== 4) FEEDBACK (last 4) ====================
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
    LIMIT 4
");
$stmt->execute();
$feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ==================== SLIDE PAGE USER (GET) ====================
$imgUrl = null;
$title  = null;
$desc   = null;

if (isset($_GET['imgUrl'], $_GET['title'], $_GET['desc'])) {
    // Basic sanitization (output-escape later in HTML too)
    $imgUrl = (string)$_GET['imgUrl'];
    $title  = (string)$_GET['title'];
    $desc   = (string)$_GET['desc'];
}
?>

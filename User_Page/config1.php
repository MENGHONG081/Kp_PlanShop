<?php
session_start();
require 'config.php'; // Your PDO connection ($pdo)

// Initialize cart
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
?>
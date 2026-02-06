<?php
include 'config.php'; // Your PDO connection ($pdo)
// Initialize cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
$cart = $_SESSION['cart'];
$user_id = $_SESSION['user_id'] ?? 0;
// Calculate initial totals
$subtotal = 0;
$cart_count = 0;
foreach ($cart as $item) {
    $subtotal += $item['price'] * $item['quantity'];
    $cart_count += $item['quantity'];
}
/* ==================== AJAX: Update Quantity / Remove Item ==================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $productId = (int)($_POST['product_id'] ?? 0);

    if ($productId > 0) {
        if ($action === 'update_quantity') {
            $change = (int)($_POST['change'] ?? 0);
            foreach ($cart as &$item) {
                if ($item['id'] == $productId) {
                    $newQty = $item['quantity'] + $change;
                    if ($newQty <= 0) {
                        // Remove if quantity becomes 0
                        $cart = array_filter($cart, fn($i) => $i['id'] != $productId);
                    } else {
                        $item['quantity'] = $newQty;
                    }
                    break;
                }
            }
        } elseif ($action === 'remove_item') {
            $cart = array_filter($cart, fn($i) => $i['id'] != $productId);
        }
        $cart = array_values($cart); // Re-index
        $_SESSION['cart'] = $cart;
    }
    // Recalculate
    $subtotal = 0;
    $cart_count = 0;
    foreach ($cart as $item) {
        $subtotal += $item['price'] * $item['quantity'];
        $cart_count += $item['quantity'];
    }
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'cart_count' => $cart_count,
        'subtotal' => number_format($subtotal, 2),
        'cart' => $cart
    ]);
    exit;
}
/* ==================== AJAX: Place Order ==================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    if (empty($cart)) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Your cart is empty.']);
        exit;
    }
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    try {
        $pdo->beginTransaction();

        // Insert order
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, total, status, created_at) 
                               VALUES (?, ?, 'pending', NOW())");
        $stmt->execute([$user_id, $total]);
        $order_id = $pdo->lastInsertId();

        // Insert order items
        $itemStmt = $pdo->prepare("INSERT INTO order_items 
                                   (order_id, product_id, qty, unit_price) 
                                   VALUES (?, ?, ?, ?)");

        foreach ($cart as $item) {
            $itemStmt->execute([
                $order_id,
                $item['id'],
                $item['quantity'],
                $item['price']
            ]);
        }
        $pdo->commit();

        // Clear cart
        $_SESSION['cart'] = [];

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'message' => 'Order placed successfully!',
            'order_id' => $order_id,
            'cart_count' => 0
        ]);
    } catch (Exception $e) {
        $pdo->rollBack();
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Order failed. Please try again.']);
    }
    exit;
}
?>
<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>KP Plant Shop - Shopping Cart</title>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            colors: {
              "primary": "#13ec37",
              "background-light": "#f6f8f6",
              "background-dark": "#102213",
              "muted-light": "#198754",
            },
            fontFamily: { "display": ["Manrope", "sans-serif"] },
          },
        },
      }
    </script>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 20 }
        .cart-item { transition: opacity 0.3s ease-in-out; }
        .cart-item.removing { opacity: 0; }
    </style>
</head>
<body class="h-full bg-background-light dark:bg-background-dark font-display text-gray-800 dark:text-gray-200">
    <div class="flex min-h-screen w-full flex-col">
        <!-- Header -->
        <header class="sticky top-0 z-50 w-full bg-muted-light">
            <div class="container mx-auto px-4">
                <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-800 py-4">
                    <div class="flex items-center gap-4">
                        <img src="icon/plant_cactus_flower_nature_flower_pot_garden_planter_icon_141184.png" alt="Logo" class="w-10 h-10"/>
                        <h2 class="text-primary text-lg font-bold">KP Plant Shop</h2>
                    </div>
                    <nav class="hidden md:flex items-center space-x-8">
                        <a href="index1.php" class="text-gray-600 hover:text-primary">Home</a>
                        <a href="About.php" class="text-gray-600 hover:text-primary">About Us</a>
                        <a href="Products.php" class="text-gray-600 hover:text-primary">Products</a>
                        <a href="Contact.php" class="text-gray-600 hover:text-primary">Contact</a>
                    </nav>
                    <div class="flex items-center gap-4">
                        <button class="relative" onclick="window.location.href='Order.php'">
                            <span class="material-symbols-outlined text-2xl">shopping_bag</span>
                            <span class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-xs text-white" id="cartCount"><?= $cart_count ?></span>
                        </button>
                        <button onclick="window.location.href='ac_user.php'">
                            <span class="material-symbols-outlined text-2xl">person</span>
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 py-12 bg-gray-50 dark:bg-gray-950">
            <div class="container mx-auto px-4 max-w-5xl">
                <div class="mb-10 text-center md:text-left">
                    <h1 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white tracking-tight">
                        Your Shopping Cart
                    </h1>
                    <p class="mt-3 text-lg text-green-700 dark:text-green-400 font-medium">
                        Review your selected plants before proceeding to checkout
                    </p>
                </div>

                <div class="grid lg:grid-cols-3 gap-8">
                    <!-- Cart Items -->
                    <div class="lg:col-span-2">
                        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-lg overflow-hidden">
                            <?php if (empty($cart)): ?>
                                <div class="text-center py-20 px-6">
                                    <span class="material-symbols-outlined text-6xl text-gray-300 dark:text-gray-700 mb-4">shopping_bag</span>
                                    <p class="text-xl font-medium text-gray-500 dark:text-gray-400">Your cart is empty</p>
                                    <a href="/PLANT_PROJECT/User_Page/Products.php" class="mt-6 inline-block bg-primary text-white px-6 py-3 rounded-xl font-semibold hover:bg-green-600 transition">
                                        Continue Shopping
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="divide-y divide-gray-200 dark:divide-gray-800">
                                    <?php foreach ($cart as $item): ?>
                                        <div class="cart-item p-6 flex flex-col sm:flex-row gap-6 items-center hover:bg-gray-50 dark:hover:bg-gray-800/50 transition" data-product-id="<?= $item['id'] ?>">
                                            <div class="flex-shrink-0">
                                                <img src="../plant_admin/uploads/<?= htmlspecialchars($item['image'] ?? 'placeholder.png') ?>" 
                                                     alt="<?= htmlspecialchars($item['name']) ?>"
                                                     class="w-28 h-28 object-cover rounded-xl shadow-sm"/>
                                            </div>
                                            <div class="flex-1 text-center sm:text-left">
                                                <h3 class="text-xl font-bold text-gray-900 dark:text-white"><?= htmlspecialchars($item['name']) ?></h3>
                                                <p class="mt-1 text-lg font-semibold text-primary">$<?= number_format($item['price'] , 2) ?></p>
                                                <div class="flex items-center justify-center sm:justify-start gap-3 mt-5">
                                                    <button onclick="updateQuantity(<?= $item['id'] ?>, -1)" class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 transition flex items-center justify-center text-lg font-bold">−</button>
                                                    <input type="text" value="<?= $item['quantity'] ?>" readonly class="quantity-input w-16 text-center text-lg font-semibold bg-transparent border-b-2 border-gray-300 dark:border-gray-600 outline-none"/>
                                                    <button onclick="updateQuantity(<?= $item['id'] ?>, 1)" class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 transition flex items-center justify-center text-lg font-bold">+</button>
                                                </div>
                                            </div>
                                            <div class="text-center sm:text-right">
                                                <p class="text-2xl font-bold text-gray-900 dark:text-white item-total">
                                                    $<?= number_format(($item['price'] * $item['quantity']), 2) ?>
                                                </p>
                                                <button onclick="removeItem(<?= $item['id'] ?>)" class="mt-4 text-red-600 hover:text-red-700 font-medium text-sm flex items-center gap-1 justify-center sm:justify-end">
                                                    <span class="material-symbols-outlined text-lg">delete</span> Remove
                                                </button>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <?php if (!empty($cart)): ?>
                    <div class="mt-12 lg:mt-0">
                        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-lg p-6">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Order Summary</h2>
                            <div class="flex justify-between items-center py-4 border-b border-gray-200 dark:border-gray-700">
                                <span class="text-lg text-gray-600 dark:text-gray-400">
                                    Subtotal (<?= $cart_count ?> items)
                                </span>
                                <span class="text-2xl font-bold" id="subtotal">$<?= number_format($subtotal, 2) ?></span>
                            </div>
                            <div class="space-y-3 py-5 text-gray-600 dark:text-gray-400">
                                <div class="flex justify-between"><span>Shipping</span><span class="font-medium">Calculated at checkout</span></div>
                                <div class="flex justify-between"><span>Tax</span><span class="font-medium">Calculated at checkout</span></div>
                            </div>
                            <div class="flex justify-between items-baseline pt-6 border-t-2 border-gray-300 dark:border-gray-600">
                                <span class="text-xl font-semibold">Estimated Total</span>
                                <span class="text-3xl font-black text-primary" id="total-display">$<?= number_format($subtotal , 2) ?></span>
                            </div>

                            <!-- Checkout Button -->
                            <button id="place-order-btn" class="w-full mt-6 bg-primary hover:bg-green-600 active:bg-green-700 text-white font-bold py-4 rounded-xl shadow-md hover:shadow-lg transition">
                                Proceed to Checkout
                            </button>

                            <a href="Products.php" class="block text-center mt-4 text-primary hover:underline font-medium">
                                ← Continue Shopping
                            </a>

                            <p class="mt-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                <span class="material-symbols-outlined text-base align-middle">lock</span>
                                Secure checkout • Free shipping on orders over $50
                            </p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>

        <footer class="mt-auto border-t border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900/50 py-8">
            <div class="container mx-auto px-4 text-center text-sm text-gray-500">
                © 2025 KP Plant Shop. All rights reserved.
            </div>
        </footer>
    </div>

    <script>
        async function updateCart(action, productId, change = 0) {
            const formData = new FormData();
            formData.append('action', action);
            formData.append('product_id', productId);
            if (change !== 0) formData.append('change', change);

            try {
                const response = await fetch('', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();

                if (data.success) {
                    document.getElementById('cartCount').textContent = data.cart_count;
                    document.getElementById('subtotal').textContent = '$' + data.subtotal;
                    document.getElementById('total-display').textContent = '$' + data.subtotal;

                    const itemRow = document.querySelector(`.cart-item[data-product-id="${productId}"]`);
                    if (action === 'remove_item' && itemRow) {
                        itemRow.classList.add('removing');
                        setTimeout(() => {
                            itemRow.remove();
                            if (data.cart_count === 0) location.reload();
                        }, 300);
                    } else if (action === 'update_quantity' && itemRow) {
                        const product = data.cart.find(i => i.id == productId);
                        if (product) {
                            itemRow.querySelector('.quantity-input').value = product.quantity;
                            itemRow.querySelector('.item-total').textContent = '$' + ((product.price) * product.quantity).toFixed(2);
                        }
                    }
                }
            } catch (err) {
                alert('Failed to update cart.');
            }
        }

        function updateQuantity(id, change) { updateCart('update_quantity', id, change); }
        function removeItem(id) { if (confirm('Remove this item?')) updateCart('remove_item', id); }

        document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('place-order-btn')?.addEventListener('click', function() {
    if (!confirm('Confirm your order? This will save it permanently.')) {
        return; // User cancelled
    }

    // Disable button and show loading state
    this.disabled = true;
    this.textContent = 'Processing Order...';

    fetch('', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'place_order=1'
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network error: ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert(`Order placed successfully!\nOrder ID: #${data.order_id}`);
            // Redirect to your processing/thank you page
            window.location.href = 'Order_pros.php?order=' + data.order_id;
            // Optional: pass order_id so Order_pros.php can show details
        } else {
            // Show the exact error from server
            alert('Order failed: ' + (data.message || 'Please try again.'));
            // Re-enable button
            this.disabled = false;
            this.textContent = 'Proceed to Checkout';
        }
    })
    .catch(error => {
        console.error('Checkout error:', error);
        alert('Connection error or order failed. Please check your internet and try again.');
        this.disabled = false;
        this.textContent = 'Proceed to Checkout';
    });
});
});
    </script>
</body>
</html>

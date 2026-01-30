<?php
session_start();
require 'config.php'; // Your PDO connection ($pdo)

// Get order_id from URL
$order_id = $_GET['order'] ?? null;
if (!$order_id || !is_numeric($order_id)) {
    // No valid order ID - redirect back to cart or show error
    header('Location: Order.php');
    exit;
}

$order_id = (int)$order_id;
$user_id = $_SESSION['user_id'] ?? 0;
$user = $_SESSION['user'] ?? 'Valued Customer';

// Fetch order details
try {
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
    $stmt->execute([$order_id, $user_id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        // Order not found or doesn't belong to user
        die('<div class="text-center py-20"><h1 class="text-3xl font-bold">Order not found</h1><a href="Order.php" class="text-green-600">Back to Cart</a></div>');
    }

    // Fetch order items
    $stmt = $pdo->prepare("
    SELECT 
        oi.qty,
        oi.unit_price,
        p.name,
        p.image
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = ?
");
$stmt->execute([$order_id]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    die("Error loading order: " . $e->getMessage());
}

// Format data for display
$order_number = str_pad($order['id'], 6, '0', STR_PAD_LEFT);
$order_total = $order['total'] / 100; // Convert cents to dollars
$tax = $order_total * 0.08;
$grand_total = $order_total + $tax;

// Fake some data for display (you can enhance this later)
//$customer_name = $_SESSION['user'] ?? 'Valued Customer';
$estimated_delivery = date('F j, Y', strtotime('+5 days'));
$shipping_address = "123 Green Street, Plant City, CA 90210"; // Replace with real address later
?>

<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Order Confirmed - KP Plant Shop</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
    
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            font-size: 20px;
        }
        
        @keyframes plantGrow {
            0% { transform: scale(0.8) rotate(-5deg); opacity: 0; }
            50% { transform: scale(1.1) rotate(2deg); }
            100% { transform: scale(1) rotate(0deg); opacity: 1; }
        }
        
        @keyframes leafFloat {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            25% { transform: translateY(-10px) rotate(-5deg); }
            75% { transform: translateY(-5px) rotate(5deg); }
        }
        
        @keyframes checkmarkBounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-15px); }
            60% { transform: translateY(-7px); }
        }
        
        .plant-grow { animation: plantGrow 0.8s ease-out; }
        .leaf-float { animation: leafFloat 3s ease-in-out infinite; }
        .checkmark-bounce { animation: checkmarkBounce 1s ease-in-out; }
        
        .floating-plant {
            position: absolute;
            opacity: 0.1;
            animation: leafFloat 4s ease-in-out infinite;
        }
        
        .floating-plant:nth-child(1) { top: 10%; left: 5%; animation-delay: 0s; }
        .floating-plant:nth-child(2) { top: 20%; right: 10%; animation-delay: 1s; }
        .floating-plant:nth-child(3) { bottom: 20%; left: 15%; animation-delay: 2s; }
        .floating-plant:nth-child(4) { bottom: 10%; right: 5%; animation-delay: 3s; }
    </style>
    
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#13ec37",
                        "secondary": "#F4F6F3",
                        "accent": "#D98C7A",
                        "background-light": "#FFFFFF",
                        "background-dark": "#102213",
                        "text-light": "#333333",
                        "text-dark": "#F4F6F3",
                        "border-light": "#E0E0E0",
                        "border-dark": "#3A5F4B",
                        "muted-light": "#198754",
                    },
                    fontFamily: { "display": ["Manrope", "sans-serif"] },
                },
            },
        }
    </script>
</head>

<body class="bg-secondary dark:bg-background-dark font-display text-text-light dark:text-text-dark">
    <!-- Floating background plants -->
    <div class="floating-plant"><svg class="w-16 h-16 text-primary/20" fill="currentColor" viewBox="0 0 48 48"><path d="M24 6c-8 0-12 8-12 16s4 16 12 16 12-8 12-16-4-16-12-16zm0 4c4 0 8 4 8 12s-4 12-8 12-8-4-8-12 4-12 8-12z"/></svg></div>
    <div class="floating-plant"><svg class="w-12 h-12 text-primary/15" fill="currentColor" viewBox="0 0 48 48"><path d="M24 4c-6 0-10 6-10 12s4 12 10 12 10-6 10-12-4-12-10-12zm0 3c3 0 7 3 7 9s-4 9-7 9-7-3-7-9 4-9 7-9z"/></svg></div>
    <div class="floating-plant"><svg class="w-20 h-20 text-primary/10" fill="currentColor" viewBox="0 0 48 48"><path d="M24 8c-5 0-9 5-9 10s4 10 9 10 9-5 9-10-4-10-9-10zm0 2c3 0 7 3 7 8s-4 8-7 8-7-3-7-8 4-8 7-8z"/></svg></div>
    <div class="floating-plant"><svg class="w-14 h-14 text-primary/15" fill="currentColor" viewBox="0 0 48 48"><path d="M24 5c-7 0-11 7-11 14s4 14 11 14 11-7 11-14-4-14-11-14zm0 3c4 0 8 4 8 11s-4 11-8 11-8-4-8-11 4-11 8-11z"/></svg></div>

    <!-- Header -->
    <header class="sticky top-0 z-10 border-b border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark backdrop-blur-sm">
        <div class="container mx-auto flex items-center justify-between px-4 py-4">
            <div class="flex items-center gap-4">
                <img src="icon/plant_cactus_flower_nature_flower_pot_garden_planter_icon_141184.png" alt="KP Plant Shop Logo" class="w-10 h-10"/>
                <h2 class="text-primary text-lg font-bold">KP Plant Shop</h2>
            </div>
            <nav class="hidden items-center gap-9 md:flex">
                <a class="text-sm font-medium hover:text-primary" href="Products.php">Shop</a>
                <a class="text-sm font-medium hover:text-primary" href="About.php">About Us</a>
            </nav>
        </div>
    </header>

    <main class="container mx-auto flex-grow px-4 py-8 md:py-12">
        <!-- Breadcrumbs -->
        <div class="mb-6 flex flex-wrap items-center gap-2 text-sm">
            <a class="font-medium text-primary hover:underline" href="Index1.php">Home</a>
            <span class="text-text-light/50 dark:text-text-dark/50">/</span>
            <a class="font-medium text-primary hover:underline" href="Products.php">Shop</a>
            <span class="text-text-light/50 dark:text-text-dark/50">/</span>
            <a class="font-medium text-primary hover:underline" href="Order.php">Cart</a>
            <span class="text-text-light/50 dark:text-text-dark/50">/</span>
            <span class="font-medium">Order Confirmed</span>
        </div>

        <!-- Success Header -->
        <div class="text-center mb-12">
            <div class="plant-grow inline-block mb-6">
                <div class="checkmark-bounce w-24 h-24 bg-primary rounded-full flex items-center justify-center text-white text-4xl shadow-lg">
                    <span class="material-symbols-outlined" style="font-size: 48px;">check_circle</span>
                </div>
            </div>
            <h2 class="text-4xl font-extrabold tracking-tight mb-4">
                Thank You, <?= htmlspecialchars($user) ?>! 🌱
            </h2>
            <p class="text-lg text-text-light/70 dark:text-text-dark/70 max-w-2xl mx-auto">
                Your plant order has been successfully confirmed and is being carefully prepared for shipment. 
                Get ready to welcome new green friends to your home!
            </p>
        </div>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
            <!-- Order Details & Items -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Order Info -->
                <div class="rounded-lg bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 p-6">
                    <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">local_shipping</span>
                        Order Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-primary/20 rounded-full flex items-center justify-center">
                                <span class="material-symbols-outlined text-primary">receipt_long</span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Order Number</p>
                                <p class="font-semibold">#<?= $order_number ?></p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-primary/20 rounded-full flex items-center justify-center">
                                <span class="material-symbols-outlined text-primary">calendar_today</span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Estimated Delivery</p>
                                <p class="font-semibold"><?= $estimated_delivery ?></p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-primary/20 rounded-full flex items-center justify-center">
                                <span class="material-symbols-outlined text-primary">receipt_long</span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Order Number</p>
                                <p class="font-semibold">#<?= $order_number ?></p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-primary/20 rounded-full flex items-center justify-center">
                                <span class="material-symbols-outlined text-primary">receipt_long</span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Order Number</p>
                                <p class="font-semibold"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="rounded-lg bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 p-6">
                    <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">shopping_bag</span>
                        Your New Plant Friends
                    </h3>
                    <div class="space-y-4">
                        <?php foreach ($items as $item):?>
                        <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-800/50 rounded-lg">
                            <div class="w-16 h-16 bg-primary/20 rounded-lg flex items-center justify-center leaf-float">
                                <img src="../plant_admin/uploads/<?= htmlspecialchars($item['image'] ?? 'placeholder.png') ?>" 
                                                     alt="<?= htmlspecialchars($item['name']) ?>"
                                                     class="w-40 h-20 object-cover rounded-xl shadow-sm"/>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold"><?= htmlspecialchars($item['name']) ?></h4>
                                <p class="text-sm text-gray-600">Qty: <?= $item['qty'] ?></p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold">$<?= number_format(($item['price'] ?? $item['unit_price'] ?? 0) * $item['qty'], 2) ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Order Summary Sidebar -->
            <div class="lg:col-span-1">
                <div class="rounded-lg bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 p-6 sticky top-24">
                    <h3 class="text-lg font-semibold mb-4">Order Summary</h3>
                    
                    <div class="space-y-3 mb-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal</span>
                            <span>$<?= number_format($order_total, 2) ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Shipping</span>
                            <span class="text-primary font-medium">FREE</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tax</span>
                            <span>$<?= number_format($tax, 2) ?></span>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-300 dark:border-gray-700 pt-4 mb-6">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-semibold">Total</span>
                            <span class="text-2xl font-bold text-primary">$<?= number_format($grand_total, 2) ?></span>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <a href="Payment.php?order=<?= $order_id ?>" class="w-full flex items-center justify-center rounded-lg bg-primary px-6 py-3 text-base font-bold text-white shadow-sm hover:bg-primary/90 transition-colors">
                            <span class="material-symbols-outlined mr-2">Payments</span>
                            Continue Payments
                        </a>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-300 dark:border-gray-700">
                        <h4 class="font-semibold mb-3 flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">support</span>
                            Need Help?
                        </h4>
                        <p class="text-sm text-gray-600 mb-3">
                            Our plant experts are here to help!
                        </p>
                        <div class="space-y-2 text-sm">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary text-sm">email</span>
                                <span>support@kpplantshop.com</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.rounded-lg').forEach(el => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(30px)';
                el.style.transition = 'all 0.6s ease';
                
                const observer = new IntersectionObserver(entries => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.style.opacity = '1';
                            entry.target.style.transform = 'translateY(0)';
                        }
                    });
                }, { threshold: 0.1 });
                
                observer.observe(el);
            });
        });
    </script>
</body>
</html>
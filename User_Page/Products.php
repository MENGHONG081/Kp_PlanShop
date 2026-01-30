<?php
session_start();
require 'config.php'; // Your PDO connection ($pdo)

// Initialize cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Load products
$stmt = $pdo->prepare("SELECT id, name, price, image FROM products");
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
?>

<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>KP Plant Shop - Our Collection</title>
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
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 20
        }
    </style>
</head>
<body class="h-full bg-background-light dark:bg-background-dark font-display text-gray-800 dark:text-gray-200">
    <div class="flex min-h-screen w-full flex-col">

        <!-- Header -->
        <header class="sticky top-0 z-50 w-full bg-muted-light">
            <div class="container mx-auto px-4">
                <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-800 py-4">
                    <div class="flex items-center gap-4">
                        <img src="icon/plant_cactus_flower_nature_flower_pot_garden_planter_icon_141184.png" alt="KP Plant Shop Logo" class="w-10 h-10"/>
                        <h2 class="text-primary text-lg font-bold">KP Plant Shop</h2>
                    </div>
                    <nav class="hidden md:flex items-center space-x-8">
                        <a href="Index1.php" class="text-gray-600 hover:text-primary">Home</a>
                        <a href="About.php" class="text-gray-600 hover:text-primary">About Us</a>
                        <a href="Products.php" class="text-primary font-bold">Products</a>
                        <a href="Contact.php" class="text-gray-600 hover:text-primary">Contact</a>
                    </nav>
                    <div class="flex items-center gap-4">
                        <button id="searchToggle" class="flex h-10 w-10 items-center justify-center rounded-full bg-primary/20 hover:bg-primary/30 transition">
                            <span class="material-symbols-outlined">search</span>
                        </button>
                        <div id="searchBar" class="hidden absolute top-16 right-4 z-50">
                            <input class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 w-64 shadow-lg" type="search" placeholder="Search for plants..." aria-label="Search">
                        </div>

                        <button class="flex h-10 w-10 items-center justify-center rounded-full bg-primary/20 hover:bg-primary/30 transition" onclick="window.location.href='ac_user.php'">
                            <span class="material-symbols-outlined">person</span>
                        </button>
                        <button class="relative flex h-10 w-10 items-center justify-center rounded-full bg-primary/20 hover:bg-primary/30 transition" onclick="window.location.href='Order.php'">
                            <span class="material-symbols-outlined">shopping_bag</span>
                            <span class="absolute top-1 right-1 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-xs font-bold text-white <?= $cart_count > 0 ? '' : 'hidden' ?>" id="cartCount"><?= $cart_count ?></span>
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 bg-gray-50 dark:bg-gray-950">
            <div class="container mx-auto px-4 py-10 md:py-12 max-w-7xl">

                <div class="mb-10 md:mb-12">
                    <h1 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white tracking-tight">
                        Our Plant Collection
                    </h1>
                    <p class="mt-4 text-lg md:text-xl text-green-700 dark:text-green-400 font-medium">
                        Discover our wide variety of beautiful and healthy plants
                    </p>
                </div>

                <!-- Filters -->
                <div class="flex flex-wrap items-center gap-3 mb-10 pb-6 border-b border-gray-200 dark:border-gray-800">
                    <button class="flex items-center gap-2 h-10 px-5 rounded-full bg-green-100 dark:bg-green-950/50 text-green-800 dark:text-green-300 hover:bg-green-200 dark:hover:bg-green-900/60 transition font-medium text-sm">
                        Sort By: Featured
                        <span class="material-symbols-outlined text-lg">expand_more</span>
                    </button>
                    <button class="flex items-center gap-2 h-10 px-5 rounded-full bg-green-100 dark:bg-green-950/50 text-green-800 dark:text-green-300 hover:bg-green-200 dark:hover:bg-green-900/60 transition font-medium text-sm">
                        Plant Type: All
                        <span class="material-symbols-outlined text-lg">expand_more</span>
                    </button>
                    <button class="flex items-center gap-2 h-10 px-5 rounded-full bg-green-100 dark:bg-green-950/50 text-green-800 dark:text-green-300 hover:bg-green-200 dark:hover:bg-green-900/60 transition font-medium text-sm">
                        Size: All
                        <span class="material-symbols-outlined text-lg">expand_more</span>
                    </button>
                    <button class="flex items-center gap-2 h-10 px-5 rounded-full bg-green-100 dark:bg-green-950/50 text-green-800 dark:text-green-300 hover:bg-green-200 dark:hover:bg-green-900/60 transition font-medium text-sm">
                        Pet-Friendly
                        <span class="material-symbols-outlined text-lg">expand_more</span>
                    </button>
                    <button class="h-10 px-5 text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-green-700 dark:hover:text-green-300 transition">
                        Clear Filters
                    </button>
                </div>

               <!-- Product Grid -->
<?php if (!empty($products)): ?>
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 md:gap-8">
    <?php foreach ($products as $product): ?>
    <article class="group flex flex-col overflow-hidden rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900/60 shadow-sm hover:shadow-xl hover:border-green-200 transition-all duration-300">
        <div class="relative aspect-square overflow-hidden bg-gray-100 dark:bg-gray-800">
            <img src="../plant_admin/uploads/<?= htmlspecialchars($product['image'] ?? 'placeholder.png') ?>"
                 alt="<?= htmlspecialchars($product['name']) ?>" 
                 class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-500"
                 loading="lazy">
        </div>

        <div class="p-5 md:p-6 flex flex-col flex-grow">
            <h3 class="text-lg md:text-xl font-bold text-gray-900 dark:text-white line-clamp-2 mb-2">
                <?= htmlspecialchars($product['name']) ?>
            </h3>

            <div class="mt-auto space-y-4">
                <p class="text-2xl font-bold text-green-700 dark:text-green-400">
                    $<?= number_format($product['price'] / 100, 2) ?>
                </p>

                <!-- Buttons in a horizontal line -->
                <div class="flex gap-3">
                    <!-- Small Add to Cart Button -->
                    <button type="button"
                            class="add-to-cart-btn flex-1 min-w-0 bg-green-600 hover:bg-green-700 active:bg-green-800 text-white font-semibold py-2.5 px-4 rounded-lg transition shadow-sm hover:shadow flex items-center justify-center gap-1.5 text-sm"
                            data-product-id="<?= $product['id'] ?>">
                        <span class="material-symbols-outlined text-lg add-icon">add_shopping_cart</span>
                        <span class="btn-text">Add</span>
                        <span class="material-symbols-outlined text-lg hidden check-icon">check</span>
                    </button>

                    <!-- Details Button -->
                    <a href="product_detail.php?id=<?= $product['id'] ?>"
                       class="flex-1 min-w-0 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-semibold py-2.5 px-4 rounded-lg transition shadow-sm hover:shadow flex items-center justify-center gap-1.5 text-sm">
                        <span class="material-symbols-outlined text-lg">visibility</span>
                        <span>Details</span>
                    </a>
                </div>
            </div>
        </div>
    </article>
    <?php endforeach; ?>
</div>
<?php else: ?>
<div class="text-center py-20 text-gray-500 dark:text-gray-400">
    <p class="text-xl">No plants found.</p>
</div>
<?php endif; ?>

                <!-- Fixed Bottom Bar: Cart Summary + Place Order -->
                <?php if (!empty($_SESSION['cart'])): ?>
                <div class="fixed bottom-0 left-0 right-0 bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800 p-4 shadow-2xl z-40">
                    <div class="container mx-auto max-w-7xl flex flex-col sm:flex-row justify-between items-center gap-4">
                        <div>
                            <p class="text-lg font-semibold">
                                Total: $<span id="cart-total">
                                    <?= number_format(array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $_SESSION['cart'])) / 100, 2) ?>
                                </span>
                            </p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                <?= $cart_count ?> item<?= $cart_count > 1 ? 's' : '' ?> in cart
                            </p>
                        </div>
                        <button id="place-order-btn" class="bg-primary hover:bg-green-700 text-white font-bold py-3 px-8 rounded-xl transition shadow-lg">
                            Place Order
                        </button>
                    </div>
                </div>
                <?php endif; ?>

            </div>
        </main>

        <!-- Footer -->
        <footer class="mt-auto border-t border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900/50">
            <div class="mx-auto max-w-7xl px-6 py-12 md:flex md:items-center md:justify-between lg:px-8">
                <div class="flex justify-center space-x-6 md:order-2">
                    <a class="text-gray-500 hover:text-gray-600" href="#">Facebook</a>
                    <a class="text-gray-500 hover:text-gray-600" href="#">Instagram</a>
                    <a class="text-gray-500 hover:text-gray-600" href="#">Twitter</a>
                </div>
                <div class="mt-8 md:order-1 md:mt-0">
                    <p class="text-center text-sm text-gray-500">© 2025 KP Plant Shop. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>

    <!-- JavaScript -->
    <script>
        // Search toggle
        document.getElementById('searchToggle').addEventListener('click', () => {
            document.getElementById('searchBar').classList.toggle('hidden');
        });

        // Add to Cart - AJAX
        document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const productId = this.dataset.productId;
                const btnText = this.querySelector('.btn-text');
                const addIcon = this.querySelector('.add-icon');
                const checkIcon = this.querySelector('.check-icon');

                if (this.disabled) return;
                this.disabled = true;
                btnText.textContent = 'Adding...';
                addIcon.classList.add('hidden');

                fetch('', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'add_to_cart=1&product_id=' + encodeURIComponent(productId)
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        // Update cart count
                        const cartCountEl = document.getElementById('cartCount');
                        cartCountEl.textContent = data.cart_count;
                        cartCountEl.classList.toggle('hidden', data.cart_count === 0);

                        // Success feedback
                        btnText.textContent = 'Added!';
                        checkIcon.classList.remove('hidden');
                        addIcon.classList.add('hidden');

                        setTimeout(() => {
                            btnText.textContent = 'Add to Cart';
                            checkIcon.classList.add('hidden');
                            addIcon.classList.remove('hidden');
                            this.disabled = false;
                        }, 1500);
                    }
                })
                .catch(() => {
                    alert('Error adding to cart.');
                    btnText.textContent = 'Add to Cart';
                    this.disabled = false;
                });
            });
        });

        // Place Order
        const placeOrderBtn = document.getElementById('place-order-btn');
        if (placeOrderBtn) {
            placeOrderBtn.addEventListener('click', function() {
                if (!confirm('Confirm placing this order?')) return;

                fetch('', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'place_order=1'
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message + '\nOrder ID: ' + data.order_id);
                        document.getElementById('cartCount').classList.add('hidden');
                        location.reload(); // Or redirect to thank-you page
                    } else {
                        alert(data.message);
                    }
                });
            });
        }
    </script>
</body>
</html>
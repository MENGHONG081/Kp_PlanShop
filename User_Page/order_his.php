<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>KP Plant Shop - My Orders</title>
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
            fontFamily: {
              "display": ["Manrope", "sans-serif"]
            },
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
                        <a href="index1.php" class="text-gray-600 hover:text-primary">Home</a>
                        <a href="About.php" class="text-gray-600 hover:text-primary">About Us</a>
                        <a href="Products.php" class="text-gray-600 hover:text-primary">Products</a>
                        <a href="Contact.php" class="text-gray-600 hover:text-primary">Contact</a>
                    </nav>
                    <div class="flex items-center gap-4">
                        <button class="relative" onclick="window.location.href='Order.php'">
                            <span class="material-symbols-outlined text-2xl">shopping_bag</span>
                            <span class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-xs text-white">3</span>
                        </button>
                        <button onclick="window.location.href='ac_user.php'">
                            <span class="material-symbols-outlined text-2xl">person</span>
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 py-8">
            <div class="container mx-auto px-4 max-w-6xl">
                <h1 class="text-4xl font-black text-gray-900 dark:text-white mb-2">My Orders</h1>
                <p class="text-lg text-green-700 dark:text-green-400 mb-8">Track and view your purchase history</p>

                <!-- Orders List -->
                <div class="space-y-6">
                    <!-- Order 1 -->
                    <div class="bg-white dark:bg-gray-900/50 rounded-xl border border-gray-200 dark:border-gray-800 overflow-hidden">
                        <div class="bg-gray-50 dark:bg-gray-800 px-6 py-4 flex flex-wrap items-center justify-between gap-4">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Order Placed</p>
                                <p class="font-semibold">December 15, 2025</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Order ID</p>
                                <p class="font-semibold">#KP20251215-001</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Total</p>
                                <p class="font-semibold text-primary">$120.48</p>
                            </div>
                            <div class="text-right">
                                <span class="inline-block px-4 py-2 text-sm font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300">
                                    Delivered
                                </span>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <!-- Product in Order 1 -->
                                <div class="flex gap-4">
                                    <div class="w-24 h-24 bg-cover bg-center rounded-lg" style="background-image: url('https://media.istockphoto.com/id/1310577216/photo/large-leaf-house-plant-monstera-deliciosa-in-a-gray-pot-on-a-white-background-in-a-light.jpg?s=612x612&w=0&k=20&c=4UNi8TZ3LuluOQSpROKl0dvrekxEEz_PpfCml8fI5c0=');"></div>
                                    <div class="flex-1">
                                        <p class="font-semibold">Monstera Deliciosa</p>
                                        <p class="text-sm text-gray-600">Quantity: 1</p>
                                        <p class="text-sm font-medium mt-2">$29.99</p>
                                    </div>
                                </div>
                                <!-- Product in Order 2 -->
                                <div class="flex gap-4">
                                    <div class="w-24 h-24 bg-cover bg-center rounded-lg" style="background-image: url('https://www.thespruce.com/thmb/JToiCM2g8ssRFBOyIvvB_G5pMDY=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc()/snake-plant-care-overview-1902772-04-d3990a1d0e1d4202a824e929abb12fc1-349b52d646f04f31962707a703b94298.jpeg');"></div>
                                    <div class="flex-1">
                                        <p class="font-semibold">Snake Plant</p>
                                        <p class="text-sm text-gray-600">Quantity: 1</p>
                                        <p class="text-sm font-medium mt-2">$24.99</p>
                                    </div>
                                </div>
                                <!-- Product in Order 3 -->
                                <div class="flex gap-4">
                                    <div class="w-24 h-24 bg-cover bg-center rounded-lg" style="background-image: url('https://static.wixstatic.com/media/nsplsh_06e8c38a85084f1b843977437c925e85~mv2.jpg/v1/fill/w_1000,h_667,al_c,q_85,usm_0.66_1.00_0.01/nsplsh_06e8c38a85084f1b843977437c925e85~mv2.jpg');"></div>
                                    <div class="flex-1">
                                        <p class="font-semibold">Fiddle Leaf Fig</p>
                                        <p class="text-sm text-gray-600">Quantity: 1</p>
                                        <p class="text-sm font-medium mt-2">$45.00</p>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-6 flex flex-wrap gap-4">
                                <button class="px-6 py-3 bg-primary text-white font-medium rounded-lg hover:bg-primary/90 transition">
                                    View Invoice
                                </button>
                                <button class="px-6 py-3 border border-gray-300 dark:border-gray-700 font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                    Track Package
                                </button>
                                <button class="px-6 py-3 border border-gray-300 dark:border-gray-700 font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                    Contact Support
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Order 2 (Example of another order) -->
                    <div class="bg-white dark:bg-gray-900/50 rounded-xl border border-gray-200 dark:border-gray-800 overflow-hidden">
                        <div class="bg-gray-50 dark:bg-gray-800 px-6 py-4 flex flex-wrap items-center justify-between gap-4">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Order Placed</p>
                                <p class="font-semibold">November 28, 2025</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Order ID</p>
                                <p class="font-semibold">#KP20251128-003</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Total</p>
                                <p class="font-semibold text-primary">$47.49</p>
                            </div>
                            <div class="text-right">
                                <span class="inline-block px-4 py-2 text-sm font-medium rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300">
                                    Shipped
                                </span>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <div class="flex gap-4">
                                    <div class="w-24 h-24 bg-cover bg-center rounded-lg" style="background-image: url('https://mobileimages.lowes.com/productimages/b81dbd91-d922-4bc6-95ee-5731b1c66d77/60561750.jpg');"></div>
                                    <div class="flex-1">
                                        <p class="font-semibold">Pothos Plant</p>
                                        <p class="text-sm text-gray-600">Quantity: 2</p>
                                        <p class="text-sm font-medium mt-2">$39.98</p>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-6 flex flex-wrap gap-4">
                                <button class="px-6 py-3 bg-primary text-white font-medium rounded-lg hover:bg-primary/90 transition">
                                    View Invoice
                                </button>
                                <button class="px-6 py-3 border border-gray-300 dark:border-gray-700 font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                    Track Package
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State (uncomment if no orders) -->
                <!--
                <div class="text-center py-16">
                    <span class="material-symbols-outlined text-8xl text-gray-300 dark:text-gray-700">shopping_bag</span>
                    <p class="text-2xl font-semibold mt-6">No orders yet</p>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">Start shopping to see your order history here!</p>
                    <a href="Products.php" class="mt-6 inline-block px-8 py-4 bg-primary text-white font-bold rounded-lg hover:bg-primary/90 transition">
                        Browse Plants
                    </a>
                </div>
                -->
            </div>
        </main>

        <!-- Footer -->
        <footer class="mt-auto border-t border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900/50 py-8">
            <div class="container mx-auto px-4 text-center text-sm text-gray-500">
                © 2025 KP Plant Shop. All rights reserved.
            </div>
        </footer>
    </div>
</body>
</html>
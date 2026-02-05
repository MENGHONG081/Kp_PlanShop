<?php
// successgr.php - Payment success page for Plant Shop
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payment Success - Plant Shop</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Manrope', sans-serif; }

    /* Animated gradient background */
    body {
      background: linear-gradient(-45deg, #84fab0, #8fd3f4, #fbc2eb, #a6c0fe);
      background-size: 400% 400%;
      animation: gradientBG 12s ease infinite;
    }
    @keyframes gradientBG {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    /* Card fade-in */
    .fade-in {
      animation: fadeInUp 0.8s ease-out forwards;
    }
    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body class="min-h-screen flex items-center justify-center">
  <div class="lg:col-span-7 xl:col-span-8">
                    <div class="flex items-center gap-2 pb-8">
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-primary text-sm font-bold text-white">1</span>
                        <span class="text-primary text-sm font-bold">Shipping</span>
                        <div class="h-px flex-1 bg-border-light dark:bg-border-dark"></div>
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-primary text-sm font-bold text-white">2</span>
                        <span class="text-primary text-sm font-bold">Payment</span>
                        <div class="h-px flex-1 bg-border-light dark:bg-border-dark"></div>
                        <span class="flex h-6 w-6 items-center justify-center rounded-full border-2 border-accent text-sm font-bold text-accent">3</span>
                        <span class="h-px flex-1 bg-border-light dark:bg-border-dark">Confirmation</span>
    </div>
  <div class="bg-white rounded-3xl shadow-2xl p-10 max-w-md w-full text-center fade-in">
    <!-- Success Icon -->
     <div class="container mx-auto flex h-20 items-center justify-center px-4 md:px-6">
                <a class="flex items-center gap-2 text-2xl font-bold text-primary" href="index1.php">
                     <img src="icon/plant_cactus_flower_nature_flower_pot_garden_planter_icon_141184.png" alt="KP Plant_Shop Logo" width="40" height="40" class="img-colorful me-2" />
                    KP Plant_Shop
                </a>
      </div>
    <div class="flex justify-center mb-6">
      <div class="w-20 h-20 flex items-center justify-center rounded-full bg-green-100 shadow-inner">
        <span class="text-green-600 text-5xl">&#10003;</span>
      </div>
    </div>

    <!-- Title -->
    <h1 class="text-3xl font-extrabold text-green-700 mb-2">Payment Successful!</h1>
    <p class="text-lg text-gray-600 mb-4">Thank you for your payment 🌱</p>

    <!-- Order ID -->
    <?php if ($order_id): ?>
      <p class="text-md text-gray-700 mb-2">
        Order ID: <span class="font-semibold text-green-800"><?= htmlspecialchars($order_id) ?></span>
      </p>
    <?php endif; ?>

    <p class="text-md text-gray-600 mb-6">Your order has been confirmed and is being processed.</p>

    <!-- Button -->
    <a href="/PLANT_PROJECT/User_Page/Order.php?order=<?= urlencode($order_id) ?>"
       class="inline-block bg-gradient-to-r from-pink-500 via-purple-500 to-indigo-500 hover:from-pink-600 hover:to-indigo-600 text-white font-bold py-3 px-6 rounded-xl shadow-lg transform hover:scale-105 transition">
       View Order Details
    </a>

    <!-- Footer -->
    <div class="mt-8 text-sm text-gray-400">Plant Shop &copy; <?= date('Y') ?></div>
  </div>

</body>
</html>
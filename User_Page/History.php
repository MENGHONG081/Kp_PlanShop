<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Plant Shop - Order History</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
<script>
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            colors: {
              "primary": "#3A5F4B",
              "background-light": "#f8f8f8",
              "background-dark": "#102213",
              "text-light": "#333333",
              "text-dark": "#e0e0e0",
              "accent": "#E29B7F",
              "status-delivered": "#3A5F4B",
              "status-shipped": "#3b82f6",
              "status-processing": "#f59e0b",
              "status-cancelled": "#6b7280"
            },
            fontFamily: {
              "display": ["Manrope", "sans-serif"]
            },
            borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px"},
          },
        },
      }
    </script>
</head>
<body class="font-display bg-background-light dark:bg-background-dark text-text-light dark:text-text-dark">
<div class="relative flex min-h-screen w-full flex-col">
<!-- TopNavBar -->
<header class="sticky top-0 z-10 w-full bg-background-light/80 dark:bg-background-dark/80 backdrop-blur-sm">
<div class="container mx-auto px-4">
<div class="flex h-20 items-center justify-between border-b border-gray-200 dark:border-gray-800">
<div class="flex items-center gap-4">
<span class="material-symbols-outlined text-primary text-3xl">potted_plant</span>
<h2 class="text-xl font-bold tracking-tight">Plant Shop</h2>
</div>
<nav class="hidden items-center gap-8 md:flex">
<a class="text-sm font-medium hover:text-primary dark:hover:text-primary" href="#">Shop</a>
<a class="text-sm font-medium hover:text-primary dark:hover:text-primary" href="#">Account</a>
<a class="text-sm font-bold text-primary dark:text-primary" href="#">Past Orders</a>
</nav>
<div class="flex items-center gap-4">
<button class="flex h-10 w-10 items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700">
<span class="material-symbols-outlined text-xl">favorite_border</span>
</button>
<button class="flex h-10 w-10 items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700">
<span class="material-symbols-outlined text-xl">shopping_bag</span>
</button>
<div class="h-10 w-10 rounded-full bg-cover bg-center" data-alt="User avatar placeholder image" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuB0uvtQVcDXLWUyWDw-dHdMi0QaLwBoJvtc2ho5iJnUIxrtCUmEpOrcinA-nYot47Ak8THuT9tp0YILwWuiQ_DuYlds05LCX5jzS1iwshxM-ZKCiQpZSKdJGh9C9fG9JffapdX121pV_wMOgPKI61Hvr9TpYI-EhUi4-Ljhwqx5oae9aR40pxfDSB4z_fgKsY_aVGn1FeJyP9_3TNvJrNfEzYNQA0JIb90HYaucpu0hJ3t7BYPGGqmln8HGkfNqZiR3Sj3omAHZBH0')"></div>
</div>
</div>
</div>
</header>
<main class="flex-grow">
<div class="container mx-auto px-4 py-8 md:py-12">
<div class="mx-auto max-w-4xl">
<!-- PageHeading -->
<div class="mb-8 flex items-center justify-between">
<h1 class="text-3xl font-extrabold tracking-tight md:text-4xl">Order History</h1>
<div class="flex items-center gap-2">
<label class="text-sm font-medium" for="sort">Sort by:</label>
<select class="rounded-lg border-gray-300 dark:border-gray-700 bg-background-light dark:bg-background-dark focus:border-primary focus:ring-primary text-sm py-1.5" id="sort">
<option>Newest</option>
<option>Oldest</option>
</select>
</div>
</div>
<!-- Order List -->
<div class="flex flex-col gap-4">
<!-- Order Card 1: Delivered -->
<div class="transform transition-shadow duration-300 hover:shadow-xl rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900/50 p-4 md:p-6">
<div class="flex flex-wrap items-center justify-between gap-4">
<div class="grid flex-1 gap-1.5 sm:grid-cols-2 md:grid-cols-3">
<div>
<p class="text-xs text-gray-500 dark:text-gray-400">Order #</p>
<p class="font-bold">P12345</p>
</div>
<div>
<p class="text-xs text-gray-500 dark:text-gray-400">Placed on</p>
<p class="font-medium">October 26, 2023</p>
</div>
<div>
<p class="text-xs text-gray-500 dark:text-gray-400">Total</p>
<p class="font-bold text-primary">$75.50</p>
</div>
</div>
<div class="flex w-full items-center justify-between gap-4 sm:w-auto">
<span class="inline-flex items-center rounded-full bg-status-delivered/10 px-3 py-1 text-xs font-semibold text-status-delivered">Delivered</span>
<a class="inline-flex h-10 items-center justify-center rounded-lg bg-primary px-4 text-sm font-bold text-white transition-colors hover:bg-primary/90" href="#">View Details</a>
</div>
</div>
</div>
<!-- Order Card 2: Shipped -->
<div class="transform transition-shadow duration-300 hover:shadow-xl rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900/50 p-4 md:p-6">
<div class="flex flex-wrap items-center justify-between gap-4">
<div class="grid flex-1 gap-1.5 sm:grid-cols-2 md:grid-cols-3">
<div>
<p class="text-xs text-gray-500 dark:text-gray-400">Order #</p>
<p class="font-bold">P12309</p>
</div>
<div>
<p class="text-xs text-gray-500 dark:text-gray-400">Placed on</p>
<p class="font-medium">October 15, 2023</p>
</div>
<div>
<p class="text-xs text-gray-500 dark:text-gray-400">Total</p>
<p class="font-bold text-primary">$42.00</p>
</div>
</div>
<div class="flex w-full items-center justify-between gap-4 sm:w-auto">
<span class="inline-flex items-center rounded-full bg-status-shipped/10 px-3 py-1 text-xs font-semibold text-status-shipped">Shipped</span>
<a class="inline-flex h-10 items-center justify-center rounded-lg bg-primary px-4 text-sm font-bold text-white transition-colors hover:bg-primary/90" href="#">Track Order</a>
</div>
</div>
</div>
<!-- Order Card 3: Processing -->
<div class="transform transition-shadow duration-300 hover:shadow-xl rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900/50 p-4 md:p-6">
<div class="flex flex-wrap items-center justify-between gap-4">
<div class="grid flex-1 gap-1.5 sm:grid-cols-2 md:grid-cols-3">
<div>
<p class="text-xs text-gray-500 dark:text-gray-400">Order #</p>
<p class="font-bold">P12256</p>
</div>
<div>
<p class="text-xs text-gray-500 dark:text-gray-400">Placed on</p>
<p class="font-medium">September 30, 2023</p>
</div>
<div>
<p class="text-xs text-gray-500 dark:text-gray-400">Total</p>
<p class="font-bold text-primary">$110.25</p>
</div>
</div>
<div class="flex w-full items-center justify-between gap-4 sm:w-auto">
<span class="inline-flex items-center rounded-full bg-status-processing/10 px-3 py-1 text-xs font-semibold text-status-processing">Processing</span>
<a class="inline-flex h-10 items-center justify-center rounded-lg bg-primary px-4 text-sm font-bold text-white transition-colors hover:bg-primary/90" href="#">View Details</a>
</div>
</div>
</div>
<!-- Order Card 4: Cancelled -->
<div class="transform transition-shadow duration-300 hover:shadow-xl rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900/50 p-4 md:p-6">
<div class="flex flex-wrap items-center justify-between gap-4">
<div class="grid flex-1 gap-1.5 sm:grid-cols-2 md:grid-cols-3">
<div>
<p class="text-xs text-gray-500 dark:text-gray-400">Order #</p>
<p class="font-bold">P12113</p>
</div>
<div>
<p class="text-xs text-gray-500 dark:text-gray-400">Placed on</p>
<p class="font-medium">September 5, 2023</p>
</div>
<div>
<p class="text-xs text-gray-500 dark:text-gray-400">Total</p>
<p class="font-bold text-primary">$55.80</p>
</div>
</div>
<div class="flex w-full items-center justify-between gap-4 sm:w-auto">
<span class="inline-flex items-center rounded-full bg-status-cancelled/10 px-3 py-1 text-xs font-semibold text-status-cancelled">Cancelled</span>
<a class="inline-flex h-10 items-center justify-center rounded-lg bg-primary px-4 text-sm font-bold text-white transition-colors hover:bg-primary/90" href="#">View Details</a>
</div>
</div>
</div>
</div>
<!-- Empty State Message (example)
                <div class="mt-8 flex flex-col items-center justify-center rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-700 p-12 text-center">
                    <span class="material-symbols-outlined text-5xl text-gray-400 dark:text-gray-500">inventory_2</span>
                    <h3 class="mt-4 text-xl font-bold">No Orders Yet</h3>
                    <p class="mt-2 max-w-xs text-sm text-gray-500 dark:text-gray-400">You haven't placed any orders yet. Let's find your first plant!</p>
                    <a href="#" class="mt-6 inline-flex h-10 items-center justify-center rounded-lg bg-primary px-5 text-sm font-bold text-white transition-colors hover:bg-primary/90">Start Shopping</a>
                </div>
                -->
<!-- Pagination -->
<div class="mt-12 flex items-center justify-center">
<nav class="flex items-center gap-2">
<a class="flex h-10 w-10 items-center justify-center rounded-lg transition-colors hover:bg-gray-100 dark:hover:bg-gray-800" href="#">
<span class="material-symbols-outlined text-lg">chevron_left</span>
</a>
<a class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary text-sm font-bold text-white" href="#">1</a>
<a class="flex h-10 w-10 items-center justify-center rounded-lg text-sm font-medium transition-colors hover:bg-gray-100 dark:hover:bg-gray-800" href="#">2</a>
<a class="flex h-10 w-10 items-center justify-center rounded-lg text-sm font-medium transition-colors hover:bg-gray-100 dark:hover:bg-gray-800" href="#">3</a>
<span class="flex h-10 w-10 items-center justify-center text-sm">...</span>
<a class="flex h-10 w-10 items-center justify-center rounded-lg text-sm font-medium transition-colors hover:bg-gray-100 dark:hover:bg-gray-800" href="#">10</a>
<a class="flex h-10 w-10 items-center justify-center rounded-lg transition-colors hover:bg-gray-100 dark:hover:bg-gray-800" href="#">
<span class="material-symbols-outlined text-lg">chevron_right</span>
</a>
</nav>
</div>
</div>
</div>
</main>
</div>
</body></html>
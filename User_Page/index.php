<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<link rel="icon" type="image/png" href="icon/plant_cactus_flower_nature_flower_pot_garden_planter_icon_141184.png">
<title>KP Plant_Shop</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
<script>
    tailwind.config = {
  darkMode: "class",
  theme: {
    extend: {
      colors: {
        // Brand greens
        "primary": "#2E7D32",          // Deep professional green
        "secondary": "#4CAF50",        // Balanced medium green
        "accent": "#A5D6A7",           // Soft mint highlight

        // Backgrounds
        "background-light": "#FFFFFF", // Clean white
        "background-dark": "#0F172A",  // Dark navy for contrast

        // Text
        "text-light": "#2F4F4F",       // Slate gray for readability
        "text-dark": "#F5F5F5",        // Light text for dark mode
      },
      fontFamily: {
        "display": ["Manrope", "sans-serif"],
      },
      borderRadius: {
        "DEFAULT": "0.25rem",
        "lg": "0.5rem",
        "xl": "0.75rem",
        "full": "9999px",
      },
    },
  },
}
  </script>
<style>
    .material-symbols-outlined {
      font-variation-settings:
      'FILL' 0,
      'wght' 400,
      'GRAD' 0,
      'opsz' 24
    }
  </style>
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-text-light dark:text-text-dark">
<div class="relative flex h-auto min-h-screen w-full flex-col group/design-root overflow-x-hidden">
<div class="layout-container flex h-full grow flex-col">
<div class="flex flex-1 justify-center py-5">
<div class="layout-content-container flex flex-col w-full max-w-7xl flex-1">
<!-- TopNavBar -->
<header class="sticky top-0 z-50 w-full border-b border-primary/20 dark:border-primary/20 bg-background-light/50 dark:bg-background-dark/80 backdrop-blur-md shadow-sm transition-all duration-300">
  <div class="mx-auto flex max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8 py-4">
    <!-- Logo + Brand -->
    <div class="flex items-center gap-4">
      <div class="flex items-center gap-3 rounded-xl bg-primary/5 dark:bg-secondary/10 px-4 py-2 shadow-sm">
        <img 
          src="icon/plant_cactus_flower_nature_flower_pot_garden_planter_icon_141184.png" 
          alt="KP Plant_Shop Logo" 
          class="h-10 w-10 rounded-full border-2 border-primary/30 dark:border-secondary/30 object-cover"
        />
        <div class="flex flex-col">
          <h1 class="text-2xl sm:text-3xl font-extrabold text-primary dark:text-secondary tracking-tight leading-none">
            KP Plant_Shop
          </h1>
          <p class="text-xs sm:text-sm text-primary/70 dark:text-secondary/70 font-medium">
            Verdant • Fresh • Green
          </p>
        </div>
      </div>
    </div>

    <!-- Desktop Navigation + Search -->
    <div class="hidden lg:flex items-center gap-10">
      <!-- Nav Links -->
      <nav class="flex items-center gap-8">
        <a href="#" class="text-base font-medium text-text-light hover:text-primary dark:text-text-dark dark:hover:text-secondary transition-colors">Home</a>
        <a href="#" class="text-base font-medium text-text-light hover:text-primary dark:text-text-dark dark:hover:text-secondary transition-colors">About</a>
        <a href="#" class="text-base font-medium text-text-light hover:text-primary dark:text-text-dark dark:hover:text-secondary transition-colors">Products</a>
        <a href="#" class="text-base font-medium text-text-light hover:text-primary dark:text-text-dark dark:hover:text-secondary transition-colors">Details</a>
      </nav>

      <!-- Search Bar (Desktop) -->
      <label class="relative flex items-center min-w-[280px]">
        <span class="absolute left-3 text-primary/60 dark:text-secondary/60">
          <span class="material-symbols-outlined text-xl">search</span>
        </span>
        <input 
          type="text" 
          placeholder="Search plants, pots, care tips..." 
          class="w-full rounded-full bg-primary/10 dark:bg-secondary/10 pl-11 pr-4 py-2.5 text-sm text-text-light dark:text-text-dark placeholder:text-primary/50 dark:placeholder:text-secondary/50 border border-primary/20 dark:border-secondary/20 focus:border-primary/40 dark:focus:border-secondary/40 focus:outline-none transition-all"
        />
      </label>
    </div>

    <!-- Auth Buttons + Mobile Search Icon (right side) -->
    <div class="flex items-center gap-3 sm:gap-4">
      <!-- Mobile Search Icon (visible on < lg) -->
      <button class="lg:hidden text-primary dark:text-secondary">
        <span class="material-symbols-outlined text-2xl">search</span>
      </button>

      <!-- Login -->
      <a href="login.php" 
         class="hidden sm:flex items-center gap-2 rounded-full bg-primary/10 dark:bg-secondary/10 px-5 py-2.5 text-sm font-semibold text-primary dark:text-secondary hover:bg-primary/20 dark:hover:bg-secondary/20 transition-colors">
        <span class="material-symbols-outlined">person</span>
        Login
      </a>

      <!-- Sign Up (more prominent) -->
      <a href="Sige_Up.php" 
         class="flex items-center gap-2 rounded-full bg-primary text-white dark:bg-secondary dark:text-black px-6 py-2.5 text-sm font-bold hover:bg-primary/90 dark:hover:bg-secondary/90 transition-colors shadow-sm">
        <span class="material-symbols-outlined">person_add</span>
        Sign Up
      </a>
    </div>
  </div>

  <!-- Optional: Mobile Menu (you can toggle with JS) -->
  <!-- For now hidden; add hamburger + JS if needed -->
</header>
<main class="flex flex-col gap-10 md:gap-16 lg:gap-20">
<!-- HeroSection -->
<div class="w-full mt-4 px-4 sm:px-6 lg:px-10">
<div class="@[480px]:p-0">
<div class="flex min-h-[520px] w-full flex-col gap-6 bg-cover bg-center bg-no-repeat @[480px]:gap-8 @[480px]:rounded-xl items-center justify-center p-4 text-center" data-alt="A lush, green collection of various houseplants in a bright, modern living room." style='background-image: linear-gradient(rgba(0, 0, 0, 0.2) 0%, rgba(0, 0, 0, 0.5) 100%), url("https://lh3.googleusercontent.com/aida-public/AB6AXuCGBVlzpWPi0XpmC96Nlru6PA703vB0wnY77mpbLZAPQp0wEZF3IkPkzgX02GvNiDsYd_gpQ5kSGdf178Xx7m_fp_7RxBH3UCqEc38JUpJE4OkqHjW6LeSlsmZThGEZ4-rfo2WavJmShZkFvQ094kaR6EH0_yi3Fi6_TkAgCLr1d49wcSj_fyaqnFxI2L3eSfvjljAbK6CJ0Dj6Q5pIXoE29duBfWDPCImLeSKmpfFyeY3H4HoUtaUWhfPfx0G5zSaI-Fpg954-CpY");'>
<div class="flex flex-col gap-4 text-center max-w-2xl">
<h1 class="text-white text-4xl font-black leading-tight tracking-[-0.033em] @[480px]:text-6xl @[480px]:font-black @[480px]:leading-tight @[480px]:tracking-[-0.033em]">Bring Nature Indoors</h1>
<h2 class="text-white/90 text-base font-normal leading-normal @[480px]:text-lg @[480px]:font-normal @[480px]:leading-normal">Discover our collection of high-quality plants and pots to liven up your space. Hand-picked for beauty and health.</h2>
</div>
<button class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-6 @[480px]:h-14 @[480px]:px-8 bg-primary hover:bg-primary/90 text-white dark:bg-secondary dark:hover:bg-secondary/90 dark:text-primary text-base font-bold leading-normal tracking-[0.015em] @[480px]:text-lg @[480px]:font-bold @[480px]:leading-normal @[480px]:tracking-[0.015em] transition-colors">
<span class="truncate">Shop Now</span>
</button>
</div>
</div>
</div>
<!-- Value Proposition Section -->
<div class="px-4 sm:px-6 lg:px-10">
<div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
<div class="flex flex-col items-center gap-3">
<div class="flex items-center justify-center h-14 w-14 rounded-full bg-primary/10 dark:bg-secondary/10 text-primary dark:text-secondary">
<span class="material-symbols-outlined text-3xl">local_shipping</span>
</div>
<h3 class="font-bold text-lg">Fast Shipping</h3>
<p class="text-sm max-w-xs text-text-light/80 dark:text-text-dark/80">Carefully packaged and shipped to your door in just a few days.</p>
</div>
<div class="flex flex-col items-center gap-3">
<div class="flex items-center justify-center h-14 w-14 rounded-full bg-primary/10 dark:bg-secondary/10 text-primary dark:text-secondary">
<span class="material-symbols-outlined text-3xl">spa</span>
</div>
<h3 class="font-bold text-lg">Quality Guarantee</h3>
<p class="text-sm max-w-xs text-text-light/80 dark:text-text-dark/80">Every plant is hand-selected and nurtured for optimal health.</p>
</div>
<div class="flex flex-col items-center gap-3">
<div class="flex items-center justify-center h-14 w-14 rounded-full bg-primary/10 dark:bg-secondary/10 text-primary dark:text-secondary">
<span class="material-symbols-outlined text-3xl">support_agent</span>
</div>
<h3 class="font-bold text-lg">Expert Support</h3>
<p class="text-sm max-w-xs text-text-light/80 dark:text-text-dark/80">Our plant experts are here to help you with any questions.</p>
</div>
</div>
</div>
<!-- New Arrivals Section -->
<div>
<h2 class="text-primary dark:text-secondary text-[24px] font-bold leading-tight tracking-[-0.015em] px-4 sm:px-6 lg:px-10 pb-3 pt-5">New Arrivals</h2>
<div class="relative">
<div class="flex overflow-x-auto [-ms-scrollbar-style:none] [scrollbar-width:none] [&amp;::-webkit-scrollbar]:hidden pl-4 sm:pl-6 lg:pl-10">
<div class="flex items-stretch p-4 gap-6">
<!-- Carousel Item 1 -->
<div class="flex h-full flex-1 flex-col gap-4 rounded-xl bg-white/50 dark:bg-black/20 shadow-sm hover:shadow-lg transition-shadow min-w-64 w-64">
<div class="w-full bg-center bg-no-repeat aspect-square bg-cover rounded-t-xl" data-alt="A Calathea Medallion plant with its large, ornate leaves in a ceramic pot." style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBWZfrodV_puyG5k_9CrVQNejqHKsf80shzpWQoNYOZ_l-s3uFgACbpSQOnMjHDX3ONn_s_anW4Zf0uNyuV6vWT8Gaqgy2TW_b3ezjSPlHzupHZT598kwQcViPMKhFV08fLPU77dJSKlm3l3Mj4Xk56sePmmpJVLNTOhAtNYiw9tZo8F1RAkV7DcwTWiQ3RaZlzAhSVk78kmjJHCmTWblNMfYqNnd9vhCvseNOj3dOSnpFcfTaqRBw3tWBeKZLEdwV0661xf_uo3OA");'></div>
<div class="flex flex-col flex-1 justify-between p-4 pt-0 gap-4">
<div>
<p class="font-medium leading-normal">Calathea Medallion</p>
<p class="text-secondary dark:text-secondary/90 text-sm font-bold">$28.00</p>
</div>
<button class="flex min-w-[84px] w-full max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary/10 dark:bg-secondary/10 text-primary dark:text-secondary hover:bg-primary/20 dark:hover:bg-secondary/20 text-sm font-bold leading-normal tracking-[0.015em] transition-colors">
<span class="truncate">Add to Cart</span>
</button>
</div>
</div>
<!-- Carousel Item 2 -->
<div class="flex h-full flex-1 flex-col gap-4 rounded-xl bg-white/50 dark:bg-black/20 shadow-sm hover:shadow-lg transition-shadow min-w-64 w-64">
<div class="w-full bg-center bg-no-repeat aspect-square bg-cover rounded-t-xl" data-alt="A tall Sansevieria Laurentii snake plant in a minimalist white pot." style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCCkzN7Nzzp53LgeHEl9zlZ9VljXifd15wLr2FVk0gWo9CHAICRs3TG_HNMGrqJVJiEVVEDhV1TVDh1FN2o7UQhsAQ2uaUMizcMQAacBN5HZ4RzRNU0GfFDxy2YgjC_XY4GeNMU2Mw-hTnje7jmyQw2whDODNwkuv57TlINhEXJ-FaOWLrGqDktdKCwknqzN6WyzN9zXqBSnc0eoja3sJpOLHtRf5FOGlJM1OmqL1jY49lUEatGKcsRRGBmqOKaeON4FCAvq91fXic");'></div>
<div class="flex flex-col flex-1 justify-between p-4 pt-0 gap-4">
<div>
<p class="font-medium leading-normal">Sansevieria 'Laurentii'</p>
<p class="text-secondary dark:text-secondary/90 text-sm font-bold">$22.00</p>
</div>
<button class="flex min-w-[84px] w-full max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary/10 dark:bg-secondary/10 text-primary dark:text-secondary hover:bg-primary/20 dark:hover:bg-secondary/20 text-sm font-bold leading-normal tracking-[0.015em] transition-colors">
<span class="truncate">Add to Cart</span>
</button>
</div>
</div>
<!-- Carousel Item 3 -->
<div class="flex h-full flex-1 flex-col gap-4 rounded-xl bg-white/50 dark:bg-black/20 shadow-sm hover:shadow-lg transition-shadow min-w-64 w-64">
<div class="w-full bg-center bg-no-repeat aspect-square bg-cover rounded-t-xl" data-alt="A Pilea Peperomioides, or Chinese Money Plant, with its round, coin-shaped leaves." style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDeYrdo37jejiaMosi-xr03B-09O2eLKrgT6LQoCOiDFF2pyXzG0RAuk4sxfyj-5tCtHsIF8YxKZy-EZGXrDpKhHQX-m17wNWKyBAiJ-MVj5NG3iKZD74f1DiK-9O8YkxaNnVq5Chwe1K3EEYpBiEGHgOypxP7Lo1a2epwimNBEVjKnHzGHTHOScHvupo5WhlxBRAKatDuka4L91BSUZdaYNKx51cHNsLUVAtjPHOhHL4FV9Jo78myeBUSdJuRd2571TCnGg7rPHLs");'></div>
<div class="flex flex-col flex-1 justify-between p-4 pt-0 gap-4">
<div>
<p class="font-medium leading-normal">Pilea Peperomioides</p>
<p class="text-secondary dark:text-secondary/90 text-sm font-bold">$19.00</p>
</div>
<button class="flex min-w-[84px] w-full max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary/10 dark:bg-secondary/10 text-primary dark:text-secondary hover:bg-primary/20 dark:hover:bg-secondary/20 text-sm font-bold leading-normal tracking-[0.015em] transition-colors">
<span class="truncate">Add to Cart</span>
</button>
</div>
</div>
<!-- Carousel Item 4 -->
<div class="flex h-full flex-1 flex-col gap-4 rounded-xl bg-white/50 dark:bg-black/20 shadow-sm hover:shadow-lg transition-shadow min-w-64 w-64">
<div class="w-full bg-center bg-no-repeat aspect-square bg-cover rounded-t-xl" data-alt="A hanging String of Pearls plant with long, trailing stems of pearl-like leaves." style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBMuk6D3EGdXoUJ1--ayg7_8L3KREG9_VdDlOY6ZptvFUxAJfpZph-73Os3ts2R_tN_rKxbW2LShLqH0xRnxlC4gF5zwA8QPVbL-xFQ4kaXJEd1wYjvgp2qsDoOWfTUcV-UhsrRF61uKMEvmNfBmXWZ4slqjcHfh66lgULI9wBlvtTuX02nVytcR6l0YzkVwRWzt_8AL_c1j0ezEWDatk9VXU6cUvBltwEaag8IFwH6SmWNdkXUGLyNGYQtEFX8emxk7BFp8sVnzuE");'></div>
<div class="flex flex-col flex-1 justify-between p-4 pt-0 gap-4">
<div>
<p class="font-medium leading-normal">String of Pearls</p>
<p class="text-secondary dark:text-secondary/90 text-sm font-bold">$25.00</p>
</div>
<button class="flex min-w-[84px] w-full max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary/10 dark:bg-secondary/10 text-primary dark:text-secondary hover:bg-primary/20 dark:hover:bg-secondary/20 text-sm font-bold leading-normal tracking-[0.015em] transition-colors">
<span class="truncate">Add to Cart</span>
</button>
</div>
</div>
<!-- Carousel Item 5 -->
<div class="flex h-full flex-1 flex-col gap-4 rounded-xl bg-white/50 dark:bg-black/20 shadow-sm hover:shadow-lg transition-shadow min-w-64 w-64">
<div class="w-full bg-center bg-no-repeat aspect-square bg-cover rounded-t-xl" data-alt="A Fiddle Leaf Fig tree in a bright, modern interior." style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBFelCTPqxobtTvfiaoP51qCo3gRteppDN9ehjQ6ox5HQgos2jpEJb8LYNeAA6mxHNl8KKuJUwalGc_lE11RefIposWaeMV8pYNDNRM-MpVXSkb5OVTX8Rd_YWz4XvyUIZOp0gX4SUNUqyvrNwMRAjrCoQRo2UpzHeq94TOMfK4_NGW3-BNCTHQF9KJxvOAP_ahbAmqUFTQt6PGuloJQwEq7NUI9XIWaNNrzWRTVEsiwzuoZh545kj9lDZVWC0SU4WGG3AyrKT6ZM8");'></div>
<div class="flex flex-col flex-1 justify-between p-4 pt-0 gap-4">
<div>
<p class="font-medium leading-normal">Fiddle Leaf Fig</p>
<p class="text-secondary dark:text-secondary/90 text-sm font-bold">$55.00</p>
</div>
<button class="flex min-w-[84px] w-full max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary/10 dark:bg-secondary/10 text-primary dark:text-secondary hover:bg-primary/20 dark:hover:bg-secondary/20 text-sm font-bold leading-normal tracking-[0.015em] transition-colors">
<span class="truncate">Add to Cart</span>
</button>
</div>
</div>
</div>
</div>
</div>
</div>
<!-- Promotional Banner -->
<div class="px-4 sm:px-6 lg:px-10">
<div class="flex flex-col md:flex-row items-center justify-between gap-6 bg-primary/90 dark:bg-secondary/90 text-white dark:text-primary rounded-xl p-8 md:p-12">
<div class="text-center md:text-left">
<h3 class="text-2xl font-bold">Free Shipping on Orders Over $75!</h3>
<p class="text-white/80 dark:text-primary/80 mt-1">Stock up on your favorite greens and we'll handle the delivery fee.</p>
</div>
<button class="flex-shrink-0 min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-6 bg-white text-primary dark:bg-primary dark:text-white font-bold leading-normal tracking-[0.015em] transition-colors">
<span class="truncate">Start Shopping</span>
</button>
</div>
</div>
<!-- Popular Plants Section -->
<div class="px-4 sm:px-6 lg:px-10">
<h2 class="text-primary dark:text-secondary text-[24px] font-bold leading-tight tracking-[-0.015em] pb-3 pt-5">Popular Plants</h2>
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 pt-4">
<!-- Product Card 1 -->
<div class="flex flex-col gap-4 rounded-xl bg-white/50 dark:bg-black/20 shadow-sm hover:shadow-lg transition-shadow">
<div class="w-full bg-center bg-no-repeat aspect-square bg-cover rounded-t-xl" data-alt="A Monstera Deliciosa plant showing its iconic split leaves." style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCDhb25uDkATZMGysnV84oDN8YegGa8EmoOs42lj0r2HRy6mATveuFBftCZJDj5FmYkc-q8OLp-AHqiQtY3PNXPDur09IzWURFjvkVeD-XcyWsuCb56bCYqkNCWuXt2ovOvLdniGxi3n0x1AUFHT5CwPCHooGYJwGrVrfEcJtnhX9YkaLsOP1O-Wri81WT_0QMfDvHyuIRbLCitUAxoQ98HipMSdGAcqvXZjWa4A9ErKhGShJb4bTBGvsH4pQRSFyWtYw4dardNBMM");'></div>
<div class="flex flex-col flex-1 justify-between p-4 pt-0 gap-4">
<div>
<p class="font-medium leading-normal">Monstera Deliciosa</p>
<p class="text-secondary dark:text-secondary/90 text-sm font-bold">$35.00</p>
</div>
<button class="flex min-w-[84px] w-full max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary/10 dark:bg-secondary/10 text-primary dark:text-secondary hover:bg-primary/20 dark:hover:bg-secondary/20 text-sm font-bold leading-normal tracking-[0.015em] transition-colors">
<span class="truncate">Add to Cart</span>
</button>
</div>
</div>
<!-- Product Card 2 -->
<div class="flex flex-col gap-4 rounded-xl bg-white/50 dark:bg-black/20 shadow-sm hover:shadow-lg transition-shadow">
<div class="w-full bg-center bg-no-repeat aspect-square bg-cover rounded-t-xl" data-alt="A ZZ Plant with glossy, dark green leaves." style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCPxKVhUv1A6R8ZGpVcXEUFd8Rma-MF1ddUC6Wix6kZdF8wq5Blj6CqRs0xxZMpDgf993akAvVqVZdGp0NV-IUShzpAzlBBc71MIdf26HgutPFmrBakfw86NSHtL2tpt8xqqFjUzLYtQEvOhX1QSnOSv0DOEidad0s_eIsKZa3FIDE8dQdGmtpD13-Ib46E8fycfDU8Omqptv7zPo_-ewJ6MKkrvEp5Adb7PX7WVilxFpK5OTmwyNGcrIxby62NtpsBeGhjLoy4Tx4");'></div>
<div class="flex flex-col flex-1 justify-between p-4 pt-0 gap-4">
<div>
<p class="font-medium leading-normal">ZZ Plant</p>
<p class="text-secondary dark:text-secondary/90 text-sm font-bold">$26.00</p>
</div>
<button class="flex min-w-[84px] w-full max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary/10 dark:bg-secondary/10 text-primary dark:text-secondary hover:bg-primary/20 dark:hover:bg-secondary/20 text-sm font-bold leading-normal tracking-[0.015em] transition-colors">
<span class="truncate">Add to Cart</span>
</button>
</div>
</div>
<!-- Product Card 3 -->
<div class="flex flex-col gap-4 rounded-xl bg-white/50 dark:bg-black/20 shadow-sm hover:shadow-lg transition-shadow">
<div class="w-full bg-center bg-no-repeat aspect-square bg-cover rounded-t-xl" data-alt="A Pothos plant with variegated leaves trailing from a shelf." style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCIkmEd0MHzyg0HHJWNErhZ-D-suRe9EY6tailqtGcUXfmZi8aZE08dffQGKbJnOKyFaGnxIIW8e0kqABVBKKUd2Kl8M8HovzRaMljTtUMK42Zu6dNH7mOoVSnGH_hl0YsJfDBu4MkJIoxZ5qToPws_IbUmnEQTZxe_SajRuboix8dPGbhYJy5qmrcwL9If31Nlm-ylqhfRt8TZ8Y4HpkKMfH1Z0lTN-P0Psubntqw1WOO2EDvIpsUmVNK7PxXstMN_FMIFpn00hB4");'></div>
<div class="flex flex-col flex-1 justify-between p-4 pt-0 gap-4">
<div>
<p class="font-medium leading-normal">Golden Pothos</p>
<p class="text-secondary dark:text-secondary/90 text-sm font-bold">$18.00</p>
</div>
<button class="flex min-w-[84px] w-full max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary/10 dark:bg-secondary/10 text-primary dark:text-secondary hover:bg-primary/20 dark:hover:bg-secondary/20 text-sm font-bold leading-normal tracking-[0.015em] transition-colors">
<span class="truncate">Add to Cart</span>
</button>
</div>
</div>
<!-- Product Card 4 -->
<div class="flex flex-col gap-4 rounded-xl bg-white/50 dark:bg-black/20 shadow-sm hover:shadow-lg transition-shadow">
<div class="w-full bg-center bg-no-repeat aspect-square bg-cover rounded-t-xl" data-alt="A Bird of Paradise plant with its large, tropical leaves." style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDmgy4-dMGBz8mvOrtgQ8TadT3bnD7-UxScCx_59gsRDGmD81nxLK6rDQa8b3JXEbCf2rzyqZfOHdWMnHG9R1ki6pp570PbCcY2fif0fARL_lXpAXtuX6I07EhknlwDWtQG6fFP6ZeydfuQy3b0n26xfLvmSI4DCnYOrbV9nGFa0PjkQQrtv3IQjhWs01Kovs3bE00EqF3ScOyZBkB97IJd0sS4nxGhROOnjj-4u6gpxT7fjJCiQBvIExWDX9PwGDNC4r9SiqD7Zig");'></div>
<div class="flex flex-col flex-1 justify-between p-4 pt-0 gap-4">
<div>
<p class="font-medium leading-normal">Bird of Paradise</p>
<p class="text-secondary dark:text-secondary/90 text-sm font-bold">$45.00</p>
</div>
<button class="flex min-w-[84px] w-full max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary/10 dark:bg-secondary/10 text-primary dark:text-secondary hover:bg-primary/20 dark:hover:bg-secondary/20 text-sm font-bold leading-normal tracking-[0.015em] transition-colors">
<span class="truncate">Add to Cart</span>
</button>
</div>
</div>
</div>
</div>
</main>
<!-- Footer -->
<footer class="mt-16 sm:mt-24  dark:bg-secondary/4 bg-black/30 py-12 px-4 sm:px-6 lg:px-10">
<div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-8">
<div class="md:col-span-1">
<div class="flex items-center gap-4">
      <div class="flex items-center gap-3 rounded-xl bg-primary/5 dark:bg-secondary/10 px-4 py-2 shadow-sm">
        <img 
          src="icon/plant_cactus_flower_nature_flower_pot_garden_planter_icon_141184.png" 
          alt="KP Plant_Shop Logo" 
          class="h-10 w-10 rounded-full border-2 border-primary/30 dark:border-secondary/30 object-cover"
        />
        <div class="flex flex-col ">
          <h1 class="text-2xl sm:text-3xl font-extrabold text-primary dark:text-secondary tracking-tight leading-none">
            KP Plant_Shop
          </h1>
          <p class="text-xs sm:text-sm text-primary/70 dark:text-secondary/70 font-medium">
            Verdant • Fresh • Green
          </p>
        </div>
      </div>
    </div>
<p class="mt-4 text-sm text-text-light/70  dark:text-text-dark/70">Your home for happy, healthy plants. We're passionate about connecting people with nature.</p>
<div class="flex gap-4 mt-6">
<a class="text-text-light/80 hover:text-primary dark:text-text-dark/80 dark:hover:text-secondary transition-colors" href="#">
<svg aria-hidden="true" class="w-6 h-6" fill="currentColor" viewbox="0 0 24 24"><path clip-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" fill-rule="evenodd"></path></svg>
</a>
<a class="text-text-light/80 hover:text-primary dark:text-text-dark/80 dark:hover:text-secondary transition-colors" href="#">
<svg aria-hidden="true" class="w-6 h-6" fill="currentColor" viewbox="0 0 24 24"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.71v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"></path></svg>
</a>
<a class="text-text-light/80 hover:text-primary dark:text-text-dark/80 dark:hover:text-secondary transition-colors" href="#">
<svg aria-hidden="true" class="w-6 h-6" fill="currentColor" viewbox="0 0 24 24"><path clip-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.024.06 1.378.06 3.808s-.012 2.784-.06 3.808c-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.024.048-1.378.06-3.808.06s-2.784-.012-3.808-.06c-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.048-1.024-.06-1.378-.06-3.808s.012-2.784.06-3.808c.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 016.345 2.525c.636-.247 1.363-.416 2.427-.465C9.793 2.013 10.147 2 12.315 2zm-1.161 4.573a.75.75 0 01.75-.75h1.5a.75.75 0 010 1.5h-1.5a.75.75 0 01-.75-.75zM12 18.25a6.25 6.25 0 110-12.5 6.25 6.25 0 010 12.5zM12 16a4 4 0 100-8 4 4 0 000 8z" fill-rule="evenodd"></path></svg>
</a>
</div>
</div>
<div class="space-y-3">
<h4 class="font-bold text-primary dark:text-secondary">Shop</h4>
<ul class="space-y-2 text-sm">
<li><a class="hover:underline" href="#">Indoor Plants</a></li>
<li><a class="hover:underline" href="#">Outdoor Plants</a></li>
<li><a class="hover:underline" href="#">Pots &amp; Accessories</a></li>
<li><a class="hover:underline" href="#">Plant Care</a></li>
</ul>
</div>
<div class="space-y-3">
<h4 class="font-bold text-primary dark:text-secondary">About</h4>
<ul class="space-y-2 text-sm">
<li><a class="hover:underline" href="#">Our Story</a></li>
<li><a class="hover:underline" href="#">Contact Us</a></li>
<li><a class="hover:underline" href="#">FAQs</a></li>
<li><a class="hover:underline" href="#">Shipping &amp; Returns</a></li>
</ul>
</div>
<div>
<h4 class="font-bold text-primary dark:text-secondary">Newsletter</h4>
<p class="mt-3 text-sm">Get plant care tips and exclusive offers straight to your inbox.</p>
<form class="mt-4 flex gap-2">
<input class="form-input w-full rounded-lg border-primary/20 dark:border-secondary/20 bg-background-light dark:bg-background-dark focus:ring-primary dark:focus:ring-secondary focus:border-primary dark:focus:border-secondary text-sm" placeholder="Enter your email" type="email"/>
<button class="flex-shrink-0 h-10 px-4 rounded-lg bg-primary dark:bg-secondary text-white dark:text-primary font-bold text-sm" type="submit">Sign Up</button>
</form>
</div>
</div>
<div class="mt-12 border-t border-primary/20 dark:border-secondary/20 pt-8 text-center text-xs text-text-light/60 dark:text-text-dark/60">
<p>© 2026 KP Plant_Shop. All rights reserved.</p>
</div>
</footer>
</div>
</div>
</div>
</div>
</body>
<script>
document.addEventListener('click', function(e){
  // allow clicks on explicit login/signup anchors
  const a = e.target.closest('a');
  if(a){
    const href = (a.getAttribute('href') || '').toLowerCase();
    if(href.includes('login.php') || href.includes('signup.php')) return;
  }
  // protect interactive elements: buttons, links, product cards, Add to Cart (class protected-action optional)
  const protectedEl = e.target.closest('.protected-action, button, a, .card, .product-card, [role="button"]');
  if(protectedEl){
    // if the clicked anchor is login/signup we already returned above
    // show alert then redirect to login
    e.preventDefault();
    alert('Please login first!');
    window.location.href = 'login.php';
  }
});
</script>
</html>
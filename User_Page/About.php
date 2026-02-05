<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>About Us - Plant Shop</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/Z1srPv7lOy9C27hHQ+Xp8a4MxAQ5a+VWW5mS+NcOZxNucg5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous" referrerpolicy="no-referrer"/>
<link rel="stylesheet" href="Home.css"/>
<link rel="stylesheet" href="tailwind.config.js"/>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
<style>
    .material-symbols-outlined {
      font-variation-settings:
      'FILL' 0,
      'wght' 400,
      'GRAD' 0,
      'opsz' 24
    }

    .footer {
        background: #212529;
        color: #fff;
        padding: 2rem 0 1rem 0;
        margin-top: 3rem;
      }
      .footer a { color: #ffc107; text-decoration: none; }
      .footer a:hover { text-decoration: underline; }

  </style>
<script id="tailwind-config">
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            "primary": "#2bee4b",
            "background-light": "#f6f8f6",
            "background-dark": "#102213",
            "foreground-light": "#0d1b10",
            "foreground-dark": "#e7f3e9",
            "card-light": "#ffffff",
            "card-dark": "#1a2c1e",
            "muted-light": "#198754",
            "muted-dark": "#a3d9ac",
            "border-light": "#cfe7d3",
            "border-dark": "#2a4d31",
          },
          fontFamily: {
            "display": ["Lexend", "sans-serif"]
          },
          borderRadius: {"DEFAULT": "0.5rem", "lg": "1rem", "xl": "1.5rem", "full": "9999px"},
        },
      },
    }
  </script>
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-foreground-light dark:text-foreground-dark">
<div class="relative flex min-h-screen w-full flex-col group/design-root overflow-x-hidden">
<!-- TopNavBar -->
<header class="sticky top-0 z-50 w-full bg-muted-light">
  <div class="container mx-auto px-4">
    <div class="flex items-center justify-between border-b border-border-light dark:border-border-dark py-4">
      
      <!-- Logo -->
      <div class="flex items-center gap-4">
        <img src="icon/plant_cactus_flower_nature_flower_pot_garden_planter_icon_141184.png" 
             alt="KP Plant_Shop Logo" 
             class="w-10 h-10 img-colorful me-2" />
        <h2 class="text-primary text-lg font-bold leading-tight tracking-[-0.015em]">
          Kp Plan_Shop
        </h2>
      </div>

      <!-- Navigation -->
      <nav class="hidden md:flex items-center space-x-8" role="navigation">
        <a href="Index1.php" class="text-foreground-light dark:text-foreground-dark text-sm font-medium hover:text-primary transition-colors">Home</a>
        <a href="About.php" class="text-primary dark:text-primary text-sm font-bold ">About Us</a>
        <a href="Products.php" class="text-foreground-light dark:text-foreground-dark text-sm font-medium hover:text-primary transition-colors">Products</a>
        <a href="Contact.php" class="text-foreground-light dark:text-foreground-dark text-sm font-medium hover:text-primary transition-colors">Contact</a>
         <a href="Detail.php" class="text-foreground-light dark:text-foreground-dark text-sm font-medium hover:text-primary transition-colors">Details</a>
      </nav>

      <!-- Actions -->
      <div class="flex items-center gap-2">
        <button class="flex items-center justify-center h-10 rounded-xl bg-primary/20 text-foreground-light dark:text-foreground-dark px-2.5 hover:bg-primary/30 transition-colors">
          <span class="material-symbols-outlined text-xl">favorite</span>
        </button>
        <button class="flex items-center justify-center h-10 rounded-xl bg-primary/20 text-foreground-light dark:text-foreground-dark px-2.5 hover:bg-primary/30 transition-colors" onclick="location.href='products.php'">
          <span class="material-symbols-outlined text-xl">shopping_cart</span>
        </button>
        <div class="bg-[url('https://lh3.googleusercontent.com/...')] bg-center bg-cover rounded-full size-10"></div>
      </div>
    </div>
  </div>
</header>
<main class="flex-grow">
<!-- HeroSection -->
<section class="w-full @container">
<div class="flex min-h-[520px] flex-col gap-6 bg-cover bg-center bg-no-repeat @[480px]:gap-8 items-center justify-center p-4 text-center" data-alt="Lush green interior of a plant shop with sunlight streaming in" style='background-image: linear-gradient(rgba(13, 27, 16, 0.3) 0%, rgba(13, 27, 16, 0.6) 100%), url("https://lh3.googleusercontent.com/aida-public/AB6AXuCoiFHK6S5u5KOjxKxY0fhmI_Ismu36FXu-TuVv7Zsv5wEckDDwPoDrF7ItYexvixHv88tQ6SuEGwNpBWe_QbYQJrFzFvzMg3zDYIFHG_Jjd7VWrMtYqcOtLD0zrGIi_YQBFFisBzWL4q9kmmqXCgYSyd8lawlXqNWix_czptk0GuU1Wj2wSVPfqPxpFjY8FHz1OxL4MGqT9XP5-JjrlImZI_-A1SD6chgWyD9KaYFNFJ7zMistdAUHdOXD2HIO8tIbwZV5ybKj35Y");'>
<div class="flex flex-col gap-2 max-w-3xl">
<h1 class="text-white text-4xl font-black leading-tight tracking-[-0.033em] @[480px]:text-6xl @[480px]:font-black @[480px]:leading-tight @[480px]:tracking-[-0.033em]">Bringing Nature Indoors, Together.</h1>
<p class="text-white/90 text-sm font-normal leading-normal @[480px]:text-lg @[480px]:font-normal @[480px]:leading-normal">Discover the story behind our passion for plants and our commitment to greening your world.</p>
</div>
</div>
</section>
<!-- FeatureSection -->
<section class="w-full container mx-auto px-4 py-16 sm:py-24">
<div class="flex flex-col gap-12 @container">
<div class="flex flex-col gap-4 text-center items-center">
<h2 class="text-foreground-light dark:text-foreground-dark tracking-light text-3xl font-bold leading-tight @[480px]:text-4xl @[480px]:font-black @[480px]:leading-tight @[480px]:tracking-[-0.033em] max-w-2xl">Our Mission &amp; Values</h2>
<p class="text-muted-light dark:text-muted-dark text-base font-normal leading-normal max-w-3xl">We believe in the power of plants to transform spaces and lives. Our mission is to provide high-quality, healthy plants while fostering a community that shares our passion for nature and sustainability.</p>
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 p-0">
<div class="flex flex-1 gap-4 rounded-xl border border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark p-6 flex-col text-center items-center img-colorful me-2">
<div class="w-16 h-16 rounded-full bg-primary/20 flex items-center justify-center mb-4">
<span class="material-symbols-outlined text-primary text-4xl">eco</span>
</div>
<div class="flex flex-col gap-1">
<h3 class="text-foreground-light dark:text-foreground-dark text-lg font-bold leading-tight">Sustainability</h3>
<p class="text-muted-light dark:text-muted-dark text-sm font-normal leading-normal">We are committed to eco-friendly practices, from our sourcing to our packaging.</p>
</div>
</div>
<div class="flex flex-1 gap-4 rounded-xl border border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark p-6 flex-col text-center items-center img-colorful me-2">
<div class="w-16 h-16 rounded-full bg-primary/20 flex items-center justify-center mb-4">
<span class="material-symbols-outlined text-primary text-4xl">groups</span>
</div>
<div class="flex flex-col gap-1">
<h3 class="text-foreground-light dark:text-foreground-dark text-lg font-bold leading-tight ">Community</h3>
<p class="text-muted-light dark:text-muted-dark text-sm font-normal leading-normal">Connecting plant lovers and creating a space for learning and sharing.</p>
</div>
</div>
<div class="flex flex-1 gap-4 rounded-xl border border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark p-6 flex-col text-center items-center img-colorful me-2">
<div class="w-16 h-16 rounded-full bg-primary/20 flex items-center justify-center mb-4">
<span class="material-symbols-outlined text-primary text-4xl">verified</span>
</div>
<div class="flex flex-col gap-1">
<h3 class="text-foreground-light dark:text-foreground-dark text-lg font-bold leading-tight">Quality</h3>
<p class="text-muted-light dark:text-muted-dark text-sm font-normal leading-normal">Every plant is carefully selected and nurtured to ensure it thrives in its new home.</p>
</div>
</div>
</div>
</div>
</section>
<!-- Timeline -->
<section class="w-full bg-card-light dark:bg-card-dark py-16 sm:py-24">
<div class="container mx-auto px-4">
<div class="text-center mb-12">
<h2 class="text-foreground-light dark:text-foreground-dark text-3xl font-bold leading-tight tracking-[-0.015em] sm:text-4xl ">Our Journey</h2>
</div> 
<div class="grid grid-cols-[auto_1fr] md:grid-cols-[1fr_auto_1fr] gap-x-4 md:gap-x-8 items-start">
  <!-- Card Post Aboute  -->
<div class="hidden md:flex flex-col text-right pr-8" id="Postcard">
    <p class="text-foreground-light dark:text-foreground-dark text-lg font-medium leading-normal">
        The Seed of an Idea
    </p>
    <p class="text-muted-light dark:text-muted-dark text-base font-normal leading-normal">
        2018
    </p>
</div>
<div class="flex flex-col items-center h-full">
    <div class="w-10 h-10 rounded-full bg-primary/20 flex items-center justify-center img-colorful me-2">
        <span 
            class="material-symbols-outlined text-primary text-2xl cursor-pointer"
            onclick="PostCard()">
            lightbulb
        </span>
    </div>
    <div class="w-px bg-border-light dark:border-border-dark grow"></div>
</div>
<div class="flex flex-col pb-12 pl-4 md:pl-0 md:text-left">
<p class="text-foreground-light dark:text-foreground-dark text-lg font-medium leading-normal md:hidden">The Seed of an Idea</p>
<p class="text-muted-light dark:text-muted-dark text-base font-normal leading-normal md:hidden">2018</p>
<p class="text-foreground-light dark:text-foreground-dark mt-2">Our journey began with a simple idea: to make the world a greener place, one plant at a time. It started in a small apartment, filled with cuttings and a lot of hope.</p>
</div>
<div class="flex flex-col text-right pr-8 pb-12">
<p class="text-foreground-light dark:text-foreground-dark text-lg font-medium leading-normal">Our First Market Stall</p>
<p class="text-muted-light dark:text-muted-dark text-base font-normal leading-normal">2020</p>
<p class="text-foreground-light dark:text-foreground-dark mt-2 md:hidden">We took a leap of faith and set up our first stall at a local farmers' market. The response from the community was overwhelming and heartwarming.</p>
</div>
<div class="flex flex-col items-center h-full">
<div class="w-10 h-10 rounded-full bg-primary/20 flex items-center justify-center img-colorful me-2"><span class="material-symbols-outlined text-primary text-2xl">storefront</span></div>
<div class="w-px bg-border-light dark:border-border-dark grow"></div>
</div>
<div class="hidden md:flex flex-col pl-8">
<p class="text-foreground-light dark:text-foreground-dark mt-2">We took a leap of faith and set up our first stall at a local farmers' market. The response from the community was overwhelming and heartwarming.</p>
</div>
<div class="hidden md:flex flex-col text-right pr-8">
<p class="text-foreground-light dark:text-foreground-dark text-lg font-medium leading-normal">Opening Our Flagship Store</p>
<p class="text-muted-light dark:text-muted-dark text-base font-normal leading-normal">2022</p>
</div>
<div class="flex flex-col items-center h-full">
<div class="w-10 h-10 rounded-full bg-primary/20 flex items-center justify-center img-colorful me-2"><span class="material-symbols-outlined text-primary text-2xl">celebration</span></div>
<div class="w-px bg-border-light dark:border-border-dark grow"></div>
</div>
<div class="flex flex-col pb-12 pl-4 md:pl-0 md:text-left">
<p class="text-foreground-light dark:text-foreground-dark text-lg font-medium leading-normal md:hidden">Opening Our Flagship Store</p>
<p class="text-muted-light dark:text-muted-dark text-base font-normal leading-normal md:hidden">2021</p>
<p class="text-foreground-light dark:text-foreground-dark mt-2">After years of hard work, we opened our doors to a beautiful, light-filled space where our plants and our community could thrive together.</p>
</div>
<div class="flex flex-col text-right pr-8 pb-12">
<p class="text-foreground-light dark:text-foreground-dark text-lg font-medium leading-normal">Launching Our Website</p>
<p class="text-muted-light dark:text-muted-dark text-base font-normal leading-normal">2024</p>
<p class="text-foreground-light dark:text-foreground-dark mt-2 md:hidden">We expanded our reach, bringing our curated collection of plants and goods to homes across the country through our online store.</p>
</div>
<div class="flex flex-col items-center h-full">
<div class="w-10 h-10 rounded-full bg-primary/20 flex items-center justify-center  img-colorful me-2"><span class="material-symbols-outlined text-primary text-2xl">language</span></div>
</div>
<div class="hidden md:flex flex-col pl-8">
<p class="text-foreground-light dark:text-foreground-dark mt-2">We expanded our reach, bringing our curated collection of plants and goods to homes across the country through our online store.</p>
</div>
</div>
</div>
</section>
<!-- Team Section -->
<section class="w-full container mx-auto px-4 py-16 sm:py-24">
<div class="flex flex-col items-center gap-12">
<div class="text-center">
<h2 class="text-foreground-light dark:text-foreground-dark text-3xl font-bold leading-tight tracking-[-0.015em] sm:text-4xl">Meet the Team</h2>
<p class="text-muted-light dark:text-muted-dark text-base font-normal leading-normal mt-2 max-w-2xl">The passionate individuals dedicated to bringing more green into your life.</p>
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
<div class="flex flex-col items-center text-center group">
<div class="relative w-48 h-48 mb-4 ">
<div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-full img-colorful me-2" data-alt="Headshot of Tann Sophearath" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuAOhm-_Xj4a4mGqOtogxGVmvFkQk-FvL3w74nLagF-wSJOUaWIvwGfIWwpyf2jePnWdroyI8C5o-8LXb33Xr09sLOPj20RCi9ymUa2PLTJ1rbeLo-sGpJ7labNkGptw-skndPTHYldE6CMPAaoVMNNhCte1oRXeB28raeGIMuAI7ZuMUIDkWGKr0C0hojpAndKJ3OzwOp4Tj3VKw2YRnMQCwqvYVMwmPImM9UzY7dONo-QGkUHBYFCqjSgZrEiZPR68ziQyc49k4No");'></div>
</div>
<h3 class="text-foreground-light dark:text-foreground-dark text-lg font-bold leading-tight">Tann Sophearath</h3>
<p class="text-primary text-sm font-medium">Founder &amp; Chief Botanist</p>
<p class="text-muted-light dark:text-muted-dark text-sm mt-2">Sophearath lifelong love for botany is the root of our shop. She personally curates every plant we carry.</p>
</div>
<div class="flex flex-col items-center text-center group">
<div class="relative w-48 h-48 mb-4">
<div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-full img-colorful me-2" data-alt="Headshot of Yaun Menghong" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuAOL70nuVOocUsOlPZSzUVE11JpPbCuN7kRoFlvWRLvH_L_2VyNAv2Godw2_EB8_mzGMaQIquhnbC99pR02BJ7FODyYXAQRwDBuYlabgXlk3HT4ySASlLSy2guuusKGyC4FQuw_hCVBGhDn6PGLWH_bKEGn-ZjrCerrCdRrQLCR80PdAfrgniSBJqhb4v9gho4J5H53Ug-FymkC0xHyVbJ0v7fFYsrYZzg323pc0W6MzDFNjiqMedyu7sMlaCwmglT5SXtW80ZAzvU");'></div>
</div>
<h3 class="text-foreground-light dark:text-foreground-dark text-lg font-bold leading-tight">Yaun Menghong</h3>
<p class="text-primary text-sm font-medium">Head of Operations</p>
<p class="text-muted-light dark:text-muted-dark text-sm mt-2">Menghong ensures that from our greenhouse to your home, every plant's journey is a happy and healthy one.</p>
</div>
<div class="flex flex-col items-center text-center group">
<div class="relative w-48 h-48 mb-4">
<div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-full img-colorful me-2" data-alt="Headshot of Emily White" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuB8OMqbj_oNUQhuyiR3H-27T-6tckbOCRtkPxkkrBDnOb7pZYU5x3K_As3V2e1PBJ_5-mwW-M1E2irIeWImsunMntk2UXPun-q3p6GBamcYiBuYpp6H6fTRza1ulnB9gstXAdC0Dfan6K6_zCbSoL1JfCDt86a--CHiBgXRcB-hwEzyhTM-C65SGO_YArjqLKAykbxARTkFLwM80TovbHWUM_kVcP1gFW1JHhPB0vzyr--xCGDFpec7ml03EouBnXnEKbfPOcVx9XU");'></div>
</div>
<h3 class="text-foreground-light dark:text-foreground-dark text-lg font-bold leading-tight">Emily White</h3>
<p class="text-primary text-sm font-medium">Community Manager</p>
<p class="text-muted-light dark:text-muted-dark text-sm mt-2">Emily is the friendly face behind our workshops and social media, fostering our wonderful plant community.</p>
</div>
</div>
</div>
</section>
<!-- CTA -->
<section class="w-full bg-card-light dark:bg-card-dark">
<div class="container mx-auto px-4 py-16 sm:py-20">
<div class="bg-primary/20 dark:bg-primary/30 rounded-xl flex flex-col md:flex-row items-center justify-between p-8 md:p-12 gap-8">
<div class="text-center md:text-left">
<h2 class="text-foreground-light dark:text-foreground-dark text-2xl sm:text-3xl font-bold tracking-tight">Ready to Grow With Us?</h2>
<p class="text-muted-light dark:text-muted-dark mt-2 max-w-xl">Find the perfect green companion for your space. Explore our collection of healthy, beautiful plants today.</p>
</div>
<button class="flex min-w-[84px] max-w-[480px] w-full md:w-auto shrink-0 cursor-pointer items-center justify-center overflow-hidden rounded-xl h-12 px-5 bg-primary text-foreground-light text-base font-bold leading-normal tracking-[0.015em] hover:bg-opacity-90 transition-all">
<span class="truncate" onclick="location.href='products.php'">Shop Our Collection</span>
</button>
</div>
</div>
</section>
</main>
<!-- Footer -->
<footer class="w-full bg-foreground-light dark:bg-card-dark mt-auto">
<div class="container mx-auto px-4 py-12 text-background-light dark:text-muted-dark">
<div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-8">
<div class="col-span-2 lg:col-span-2">
<div class="flex items-center gap-2 mb-4">
<img src="icon/plant_cactus_flower_nature_flower_pot_garden_planter_icon_141184.png" alt="KP Plant_Shop Logo" width="50" height="30" class="img-colorful me-2" />
<h3 class="text-lg font-bold">KP Plant_Shop</h3>
</div>
<p class="text-sm max-w-sm">Your one-stop shop for everything green and beautiful. We're passionate about bringing nature into your home.</p>
</div>
<div>
<h4 class="font-bold mb-4">Shop</h4>
<ul class="space-y-2 text-sm">
<li><a class="hover:text-primary transition-colors" href="index1.php">Home</a></li>
<li><a class="hover:text-primary transition-colors" href="products.php">Products</a></li>
<li><a class="hover:text-primary transition-colors" href="products_pots.php">Pots &amp; Planters</a></li>
<li><a class="hover:text-primary transition-colors" href="products_tools.php">Tools &amp; Accessories</a></li>
</ul>
</div>
<div>
<h4 class="font-bold mb-4">About</h4>
<ul class="space-y-2 text-sm">
<li><a class="hover:text-primary transition-colors" href="index1.php">Our Story</a></li>
<li><a class="hover:text-primary transition-colors" href="About.php">Workshops</a></li>
<li><a class="hover:text-primary transition-colors" href="faqs.php">FAQs</a></li>
<li><a class="hover:text-primary transition-colors" href="contact.php">Contact Us</a></li>
</ul>
</div>
<div>
<h4 class="font-bold mb-4">Follow Us</h4>
<div class="flex space-x-4">
<a class="hover:text-primary transition-colors" href="#">
<svg aria-hidden="true" class="w-6 h-6" fill="currentColor" viewbox="0 0 24 24"><path clip-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" fill-rule="evenodd"></path></svg>
</a>
<a class="hover:text-primary transition-colors" href="#">
<svg aria-hidden="true" class="w-6 h-6" fill="currentColor" viewbox="0 0 24 24"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.71v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"></path></svg>
</a>
<a class="hover:text-primary transition-colors" href="#">
<svg aria-hidden="true" class="w-6 h-6" fill="currentColor" viewbox="0 0 24 24"><path clip-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.024.06 1.378.06 3.808s-.012 2.784-.06 3.808c-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.024.048-1.378.06-3.808.06s-2.784-.013-3.808-.06c-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.048-1.024-.06-1.378-.06-3.808s.012-2.784.06-3.808c.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 016.345 2.525c.636-.247 1.363-.416 2.427-.465C9.805 2.013 10.16 2 12.315 2zM12 7.177a4.823 4.823 0 100 9.646 4.823 4.823 0 000-9.646zM12 15a3 3 0 110-6 3 3 0 010 6zm4.83-8.44a1.2 1.2 0 100-2.4 1.2 1.2 0 000 2.4z" fill-rule="evenodd"></path></svg>
</a>
</div>
</div>
</div>
<div class="border-t border-border-dark dark:border-border-light/20 mt-8 pt-6 text-center text-sm">
<p>© 2026 KP PlanShop. All rights reserved.</p>
</div>
</div>
</footer>
</div>
</body>
</html>
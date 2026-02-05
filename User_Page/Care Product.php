<!DOCTYPE html>
<html lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Planty - Plant Care Guides</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;500;700;900&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
<style>
        .material-symbols-outlined {
            font-variation-settings:
                'FILL' 0,
                'wght' 400,
                'GRAD' 0,
                'opsz' 24;
        }
    </style>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#4CAF50",
                        "background-light": "#f6f8f6",
                        "background-dark": "#102213",
                        "card-light": "#ffffff",
                        "card-dark": "#1a2e1e",
                        "text-light": "#2F4F4F",
                        "text-dark": "#e8f5e9",
                        "text-subtle-light": "#556b6b",
                        "text-subtle-dark": "#a8c5ad",
                        "border-light": "#E8F5E9",
                        "border-dark": "#2a422e",
                        "accent": "#E57373"
                    },
                    fontFamily: {
                        "display": ["Lexend", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.5rem",
                        "lg": "1rem",
                        "xl": "1.5rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
</head>
<body class="font-display bg-background-light dark:bg-background-dark text-text-light dark:text-text-dark">
<div class="relative flex min-h-screen w-full flex-col">
<!-- TopNavBar -->
<header class="sticky top-0 z-10 w-full border-b border-border-light dark:border-border-dark bg-background-light/80 dark:bg-background-dark/80 backdrop-blur-sm">
<div class="container mx-auto flex items-center justify-between whitespace-nowrap px-4 py-3">
<div class="flex items-center gap-4">
<span class="material-symbols-outlined text-primary text-3xl">potted_plant</span>
<h2 class="text-text-light dark:text-text-dark text-xl font-bold">Planty</h2>
</div>
<nav class="hidden md:flex items-center gap-9">
<a class="text-text-light dark:text-text-dark text-sm font-medium leading-normal" href="products.php">Shop</a>
<a class="text-primary text-sm font-bold leading-normal border-b-2 border-primary pb-1" href="index.php">Home</a>
<a class="text-text-light dark:text-text-dark text-sm font-medium leading-normal" href="about.php">About</a>
</nav>
<div class="flex items-center gap-4">
<button  class="flex h-10 w-10 cursor-pointer items-center justify-center overflow-hidden rounded-full bg-border-light dark:bg-border-dark text-text-light dark:text-text-dark" onclick="location.href='ac_user.php'">
<span class="material-symbols-outlined">person</span>
</button>
</div>
</div>
</header>
<!-- Main Content -->
<main class="container mx-auto flex-grow px-4 py-8">
<!-- HeroSection -->
<div class="w-full mb-12">
<div class="flex min-h-[400px] flex-col items-center justify-center gap-6 rounded-xl bg-cover bg-center bg-no-repeat p-8 text-center" data-alt="Close up of lush green monstera leaves with soft lighting" style="background-image: linear-gradient(rgba(47, 79, 79, 0.4) 0%, rgba(47, 79, 79, 0.7) 100%), url('https://lh3.googleusercontent.com/aida-public/AB6AXuDHGWxVeLHoBveWB5MnNg6YX6bwdEEvP51UHEBkicUuGudxPlaSOguvaxLqMMHRmS0LT3zYeAKLo3EQlhl73wwanfbNpPvG2xuwEtg1zxiXO2EhLggZGyZVCaj3_ofo43-uqR4_xQgVD2mGIzlsHQSreoJXPzVfdm9lZvDuWkZ0mXhksOhuVrEHXHHpmdAyNgGz5Z7Jzum9dAHtULzowUpXef5AYODfLKe8YojOrmqJJd8UQF_Mn31nkNFq63OdEf4P2UoG1tYzqlI');">
<div class="flex flex-col gap-2">
<h1 class="text-white text-4xl font-black leading-tight tracking-tight md:text-5xl">Your Guide to Happy Plants</h1>
<h2 class="text-white/90 text-sm font-normal leading-normal md:text-base max-w-2xl mx-auto">
                            Find comprehensive guides on how to care for your favorite plants, from watering tips to sunlight needs. Let's grow together!
                        </h2>
</div>
</div>
</div>
<div class="flex flex-col lg:flex-row gap-8">
<!-- SideNavBar (Filters) -->
<aside class="w-full lg:w-1/4 xl:w-1/5">
<div class="sticky top-24 flex h-full flex-col gap-6 rounded-lg bg-card-light dark:bg-card-dark p-4 border border-border-light dark:border-border-dark">
<div class="flex flex-col gap-2">
<h3 class="text-lg font-bold">Filter Guides</h3>
<p class="text-sm text-text-subtle-light dark:text-text-subtle-dark">Find the perfect guide</p>
</div>
<div class="relative">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-text-subtle-light dark:text-text-subtle-dark">search</span>
<input class="w-full rounded-lg border border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark py-2 pl-10 pr-4 text-sm focus:border-primary focus:ring-primary" placeholder="Search plants..." type="text"/>
</div>
<div class="flex flex-col gap-4">
<div class="flex flex-col gap-2">
<p class="text-sm font-medium">Plant Type</p>
<div class="flex items-center gap-2"><input class="rounded border-gray-300 text-primary shadow-sm focus:ring-primary" type="checkbox"/> <span class="text-sm">Succulent</span></div>
<div class="flex items-center gap-2"><input class="rounded border-gray-300 text-primary shadow-sm focus:ring-primary" type="checkbox"/> <span class="text-sm">Tropical</span></div>
<div class="flex items-center gap-2"><input class="rounded border-gray-300 text-primary shadow-sm focus:ring-primary" type="checkbox"/> <span class="text-sm">Flowering</span></div>
</div>
<div class="flex flex-col gap-2">
<p class="text-sm font-medium">Light Needs</p>
<div class="flex items-center gap-2"><input class="rounded border-gray-300 text-primary shadow-sm focus:ring-primary" type="checkbox"/> <span class="text-sm">Low Light</span></div>
<div class="flex items-center gap-2"><input class="rounded border-gray-300 text-primary shadow-sm focus:ring-primary" type="checkbox"/> <span class="text-sm">Bright, Indirect</span></div>
<div class="flex items-center gap-2"><input class="rounded border-gray-300 text-primary shadow-sm focus:ring-primary" type="checkbox"/> <span class="text-sm">Direct Sun</span></div>
</div>
<div class="flex flex-col gap-2">
<p class="text-sm font-medium">Difficulty</p>
<div class="flex items-center gap-2"><input class="rounded border-gray-300 text-primary shadow-sm focus:ring-primary" type="checkbox"/> <span class="text-sm">Beginner</span></div>
<div class="flex items-center gap-2"><input class="rounded border-gray-300 text-primary shadow-sm focus:ring-primary" type="checkbox"/> <span class="text-sm">Intermediate</span></div>
<div class="flex items-center gap-2"><input class="rounded border-gray-300 text-primary shadow-sm focus:ring-primary" type="checkbox"/> <span class="text-sm">Expert</span></div>
</div>
</div>
</div>
</aside>
<!-- Content Area -->
<div class="w-full lg:w-3/4 xl:w-4/5">
<!-- Featured Article -->
<div class="mb-8 rounded-xl bg-accent/10 dark:bg-accent/20 border border-accent/20 dark:border-accent/30 p-6 flex flex-col md:flex-row items-center gap-6">
<div class="flex-shrink-0">
<div class="w-24 h-24 bg-center bg-no-repeat bg-cover rounded-full" data-alt="A healthy fiddle leaf fig plant in a terracotta pot" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAahwEDYJyRHLpLrWeXDLToLQqEtPPTIOc0VJGFPDB8sPpFn8ffr8eRBHwaKS8haioFTW2ekdh5K0PC08AjKCEOgAx_IEKPyVKqVrnO2WFjoloRJ8ei17xzUt-l_uIvJWPRRLisYEfEfM18qWsmL1x4ck6_-qmi4KDY5rtFeMRcnHB2-5gK0qiiX6PWgz1sClaVBWFDlVBpPtAPS_eqXS5dplASysvSHeTDdl8FrzXuIqfaQq9m-AXZz01bayk36XAw9pyp3iesIW0');"></div>
</div>
<div class="flex-grow text-center md:text-left">
<p class="text-sm font-bold text-accent">Seasonal Tip</p>
<h3 class="text-xl font-bold text-text-light dark:text-text-dark mt-1">Avoiding Root Rot in Winter</h3>
<p class="text-sm mt-2 text-text-subtle-light dark:text-text-subtle-dark">Overwatering is the biggest killer of houseplants in colder months. Learn how to adjust your schedule and keep your plants thriving until spring.</p>
<a class="inline-block mt-4 text-sm font-bold text-primary hover:underline" href="#">Read More →</a>
</div>
</div>
<!-- ImageGrid (Care Guides) -->
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
<div class="flex flex-col gap-3 pb-3 rounded-lg bg-card-light dark:bg-card-dark border border-border-light dark:border-border-dark overflow-hidden transition-shadow hover:shadow-lg">
<div class="w-full bg-center bg-no-repeat aspect-[4/3] bg-cover" data-alt="Monstera Deliciosa plant with its characteristic split leaves" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAoh9F3jC9AG_FQHMa1mJHalLyj_ysGSaJTc7PHsoWMgmWbuGfjyfjCAF4Jy10UPaFaqayu5BtRVR3ovgvHxBbB2JuBOPi2dqvDMypTC3oOxDXcC_cB-Fg2VrCIbveL0YdP4nAGwfHkxA6SqlgViZ8lQ2C2h1QFm7SNAsMfDD9_mFzbQFAvDKKtrI5lK6V5oS9DttBh1i_JdAz31aegYmujYuNg3O-lOHi0RvMZBvEMUiw_hwZ5na95NyQNzklKWA5ZmBfdf-P4A3A');"></div>
<div class="p-4 pt-0">
<p class="text-base font-bold">Monstera Deliciosa</p>
<div class="flex items-center gap-4 mt-2 text-text-subtle-light dark:text-text-subtle-dark text-sm">
<span class="flex items-center gap-1.5"><span class="material-symbols-outlined !text-base">water_drop</span> Easy</span>
<span class="flex items-center gap-1.5"><span class="material-symbols-outlined !text-base">light_mode</span> Bright</span>
<span class="flex items-center gap-1.5"><span class="material-symbols-outlined !text-base">thermostat</span> Warm</span>
</div>
</div>
</div>
<div class="flex flex-col gap-3 pb-3 rounded-lg bg-card-light dark:bg-card-dark border border-border-light dark:border-border-dark overflow-hidden transition-shadow hover:shadow-lg">
<div class="w-full bg-center bg-no-repeat aspect-[4/3] bg-cover" data-alt="A tall Fiddle Leaf Fig tree in a bright room" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuCK-ZhaRBHYI7yRwHEW7T0vEm2HtazQGgm4XmIFCv_d-QPgUuRv8chL2RQirHM2xxkuSyot8KJxHXCBU4UWZuL8CDPBKFC1xitGhLRb47jT3Gq57Jp3Lat_QsLtVYZSTCo--9qjFXzBs27jGMxDIj4ahs7D7qgSGOkEbxANsaErwruuZF_Zq4jq8tRg64pj2ppULQsDINtdoXmEtLbNXYDYSqeDE7y3SpTM9_cqN_s8G44j9VdqQ54hpXdEzdQcfo1gadWpBIJj_xI');"></div>
<div class="p-4 pt-0">
<p class="text-base font-bold">Fiddle Leaf Fig</p>
<div class="flex items-center gap-4 mt-2 text-text-subtle-light dark:text-text-subtle-dark text-sm">
<span class="flex items-center gap-1.5"><span class="material-symbols-outlined !text-base">water_drop</span> Medium</span>
<span class="flex items-center gap-1.5"><span class="material-symbols-outlined !text-base">light_mode</span> Bright</span>
<span class="flex items-center gap-1.5"><span class="material-symbols-outlined !text-base">thermostat</span> Warm</span>
</div>
</div>
</div>
<div class="flex flex-col gap-3 pb-3 rounded-lg bg-card-light dark:bg-card-dark border border-border-light dark:border-border-dark overflow-hidden transition-shadow hover:shadow-lg">
<div class="w-full bg-center bg-no-repeat aspect-[4/3] bg-cover" data-alt="A Snake Plant with its upright, variegated leaves" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAfKWOWXdnicKvK8v0wm0vjSI1U2wfkkkG5HgdF6goYwLU4muf7HzdskPVXPaEUeDpZVCHh7fryB3LDTeOEgtvp1tFIAzqPr93LlRfQYTG3JeS3nyzWYc-DaEM6ezGY2DHTkYFMpK1uFH3AUv3zJlFcUZV-77Ct-U8Z6bmQ8u0CL0laXcgytcyuS0rO8goeCpox7haRJLklY5zRz62vpvTmXt93aaPbcqI719LtSHde17JnjERphRBHQtvFrksnTSzQO0VfF4Giq5Q');"></div>
<div class="p-4 pt-0">
<p class="text-base font-bold">Snake Plant</p>
<div class="flex items-center gap-4 mt-2 text-text-subtle-light dark:text-text-subtle-dark text-sm">
<span class="flex items-center gap-1.5"><span class="material-symbols-outlined !text-base">water_drop</span> Easy</span>
<span class="flex items-center gap-1.5"><span class="material-symbols-outlined !text-base">light_mode</span> Low</span>
<span class="flex items-center gap-1.5"><span class="material-symbols-outlined !text-base">thermostat</span> Any</span>
</div>
</div>
</div>
<div class="flex flex-col gap-3 pb-3 rounded-lg bg-card-light dark:bg-card-dark border border-border-light dark:border-border-dark overflow-hidden transition-shadow hover:shadow-lg">
<div class="w-full bg-center bg-no-repeat aspect-[4/3] bg-cover" data-alt="A pothos plant with trailing vines" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuCZtoNJYrBUMpHMCxwMO0jtVhS6u5uXmRjCeU-mDrhTlP_7QpxfTOR2J0szXoNlt_Vtog8_YoIOUAe1H6uVKFAdLQ6BDmG5U8P1KhDysqUcveswz5ksFJG2syUe8zGcTeqpMhaFgS5iBIyoT41P5mexonGX7DoLk9k3Hy0cAIKVDa9aiskZj2G_kIcn6soSd98nguQepxtvvXEAkRBJA_IqC0PKf-WjxqSd7zNcgXwOYmwlrxYmVykFFTFPoUs5eZfPuNO7GBmbAtY');"></div>
<div class="p-4 pt-0">
<p class="text-base font-bold">Pothos</p>
<div class="flex items-center gap-4 mt-2 text-text-subtle-light dark:text-text-subtle-dark text-sm">
<span class="flex items-center gap-1.5"><span class="material-symbols-outlined !text-base">water_drop</span> Easy</span>
<span class="flex items-center gap-1.5"><span class="material-symbols-outlined !text-base">light_mode</span> Low</span>
<span class="flex items-center gap-1.5"><span class="material-symbols-outlined !text-base">thermostat</span> Warm</span>
</div>
</div>
</div>
<div class="flex flex-col gap-3 pb-3 rounded-lg bg-card-light dark:bg-card-dark border border-border-light dark:border-border-dark overflow-hidden transition-shadow hover:shadow-lg">
<div class="w-full bg-center bg-no-repeat aspect-[4/3] bg-cover" data-alt="A ZZ Plant with waxy, dark green leaves" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuCzjM8HytnCQLb5cRXMjWqx58GphwXZKO9kYGTZp0eIjjM7HMjZTFSenN5XPqUZPdXkherydcn4LMw1dh4bU6Fmb_OPRUFYzbapD2FGs5fubChyfAhl_mSbl0mWJ7hJYK1IRY4g4G9rCYFflr99fYlaCFtGgG8QDONfWVp7covdsoOj4RTvzpgdrOnCqZJZD1jSjSiTcRaAStnas_ybk6HoDFeNTn8lKgOyGklFdrt9pae_0XmnejyEtCej9FJ7HUei4eV7lLoAShI');"></div>
<div class="p-4 pt-0">
<p class="text-base font-bold">ZZ Plant</p>
<div class="flex items-center gap-4 mt-2 text-text-subtle-light dark:text-text-subtle-dark text-sm">
<span class="flex items-center gap-1.5"><span class="material-symbols-outlined !text-base">water_drop</span> Easy</span>
<span class="flex items-center gap-1.5"><span class="material-symbols-outlined !text-base">light_mode</span> Low</span>
<span class="flex items-center gap-1.5"><span class="material-symbols-outlined !text-base">thermostat</span> Warm</span>
</div>
</div>
</div>
<div class="flex flex-col gap-3 pb-3 rounded-lg bg-card-light dark:bg-card-dark border border-border-light dark:border-border-dark overflow-hidden transition-shadow hover:shadow-lg">
<div class="w-full bg-center bg-no-repeat aspect-[4/3] bg-cover" data-alt="A Calathea plant with patterned leaves" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAHJTayAJoKV5IJ5sz6GEWAUZgTX73F6GnGq1vlFAYocxaodMPClHNAQp-jJGM5s80eGoAz5jpe2AV7ufPQxThwAQmTjc2iPrv1Va61iEgsrKAsTrtnWpZLg5-tHkeEMjMV-b2SLaVkPZpL4UQD0qRUOs2rLlm6v7rWixuJJMq3pg_nUSfsIMmZAGy6db5tpfc7d5q025TU2XOMm1ji9hLAU-O5308bimpELhCbiJ_-4qQ3nptF2xHjy2qPYjVn-lNXkN60HDgzwEY');"></div>
<div class="p-4 pt-0">
<p class="text-base font-bold">Calathea</p>
<div class="flex items-center gap-4 mt-2 text-text-subtle-light dark:text-text-subtle-dark text-sm">
<span class="flex items-center gap-1.5"><span class="material-symbols-outlined !text-base">water_drop</span> Medium</span>
<span class="flex items-center gap-1.5"><span class="material-symbols-outlined !text-base">light_mode</span> Bright</span>
<span class="flex items-center gap-1.5"><span class="material-symbols-outlined !text-base">thermostat</span> Warm</span>
</div>
</div>
</div>
</div>
<!-- Pagination -->
<div class="mt-12 flex items-center justify-center p-4">
<a class="flex size-10 items-center justify-center rounded-full hover:bg-border-light dark:hover:bg-border-dark" href="#">
<span class="material-symbols-outlined">chevron_left</span>
</a>
<a class="text-sm font-bold flex size-10 items-center justify-center text-white bg-primary rounded-full" href="#">1</a>
<a class="text-sm font-medium flex size-10 items-center justify-center rounded-full hover:bg-border-light dark:hover:bg-border-dark" href="#">2</a>
<a class="text-sm font-medium flex size-10 items-center justify-center rounded-full hover:bg-border-light dark:hover:bg-border-dark" href="#">3</a>
<span class="text-sm flex size-10 items-center justify-center rounded-full">...</span>
<a class="text-sm font-medium flex size-10 items-center justify-center rounded-full hover:bg-border-light dark:hover:bg-border-dark" href="#">9</a>
<a class="flex size-10 items-center justify-center rounded-full hover:bg-border-light dark:hover:bg-border-dark" href="#">
<span class="material-symbols-outlined">chevron_right</span>
</a>
</div>
</div>
</div>
</main>
<!-- Footer -->
<footer class="w-full bg-card-light dark:bg-card-dark border-t border-border-light dark:border-border-dark">
<div class="container mx-auto grid grid-cols-1 md:grid-cols-4 gap-8 px-4 py-12">
<div class="flex flex-col gap-4">
<div class="flex items-center gap-2">
<span class="material-symbols-outlined text-primary text-2xl">potted_plant</span>
<h2 class="text-text-light dark:text-text-dark text-lg font-bold">Planty</h2>
</div>
<p class="text-sm text-text-subtle-light dark:text-text-subtle-dark">Your destination for happy, healthy plants.</p>
</div>
<div>
<h4 class="font-bold mb-4">Explore</h4>
<ul class="space-y-2 text-sm">
<li><a class="hover:text-primary" href="Products.php">Shop All</a></li>
<li><a class="hover:text-primary" href="Care Product.php">Care Guides</a></li>
<li><a class="hover:text-primary" href="About.php">About Us</a></li>
</ul>
</div>
<div>
<h4 class="font-bold mb-4">Support</h4>
<ul class="space-y-2 text-sm">
<li><a class="hover:text-primary" href="contact.php">Contact</a></li>
<li><a class="hover:text-primary" href="ai_chat.php">FAQs</a></li>
<li><a class="hover:text-primary" href="Order.php">Shipping &amp; Returns</a></li>
</ul>
</div>
<div>
<h4 class="font-bold mb-4">Follow Us</h4>
<div class="flex items-center gap-4">
<a class="hover:text-primary" href="#">
<svg aria-hidden="true" class="w-6 h-6" fill="currentColor" viewbox="0 0 24 24"><path clip-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" fill-rule="evenodd"></path></svg>
</a>
<a class="hover:text-primary" href="#">
<svg aria-hidden="true" class="w-6 h-6" fill="currentColor" viewbox="0 0 24 24"><path d="M12 2.25c-2.73 0-3.06.01-4.13.06a6.2 6.2 0 0 0-4.4 4.4C3.41 7.87 3.36 8.2 3.36 10.93c0 2.73.01 3.06.06 4.13a6.2 6.2 0 0 0 4.4 4.4c1.07.05 1.4.06 4.13.06s3.06-.01 4.13-.06a6.2 6.2 0 0 0 4.4-4.4c.05-1.07.06-1.4.06-4.13s-.01-3.06-.06-4.13a6.2 6.2 0 0 0-4.4-4.4C15.06 2.26 14.73 2.25 12 2.25zm0 1.69c2.68 0 2.98.01 4.04.06a4.52 4.52 0 0 1 3.25 3.25c.05 1.06.06 1.36.06 4.04s-.01 2.98-.06 4.04a4.52 4.52 0 0 1-3.25 3.25c-1.06.05-1.36.06-4.04.06s-2.98-.01-4.04-.06a4.52 4.52 0 0 1-3.25-3.25c-.05-1.06-.06-1.36-.06-4.04s.01-2.98.06-4.04a4.52 4.52 0 0 1 3.25-3.25c1.06-.05 1.36-.06 4.04-.06zm0 4.19a3.87 3.87 0 1 0 0 7.74 3.87 3.87 0 0 0 0-7.74zm0 6.05a2.18 2.18 0 1 1 0-4.36 2.18 2.18 0 0 1 0 4.36zm4.4-6.42a.91.91 0 1 0 0-1.82.91.91 0 0 0 0 1.82z"></path></svg>
</a>
</div>
</div>
</div>
<div class="border-t border-border-light dark:border-border-dark py-4 text-center">
<p class="text-xs text-text-subtle-light dark:text-text-subtle-dark">© 2024 Planty. All rights reserved.</p>
</div>
</footer>
</div>
</body></html>
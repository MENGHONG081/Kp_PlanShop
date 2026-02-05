<!DOCTYPE html>

<html lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Plant Shop - Product Details</title>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link rel="stylesheet" href="Home.css"/>
<script src="Home.js"></script>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
<script id="tailwind-config">
          tailwind.config = {
            darkMode: "class",
                    theme: {
                      extend: {
                                colors: {
                                  "primary": "#13ec37",
                                  "secondary": "#AEC3B0",
                                  "accent": "#E07A5F",
                                  "background-light": "#F8F8F8",
                                  "background-dark": "#1a1a1a",
                                  "text-light": "#333333",
                                  "text-dark": "#F0F0F0",
                                  "muted-light": "#198754",
                                },
                        },
                              fontFamily: {
                                "display": ["Manrope", "sans-serif"]
                              },
                        borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px"},
                      },
            };

    </script>
</head>
<body class="font-display bg-background-light dark:bg-background-dark text-text-light dark:text-text-dark">
<div class="relative flex h-auto min-h-screen w-full flex-col group/design-root overflow-x-hidden">
<div class="layout-container flex h-full grow flex-col">
<header class="sticky top-0 z-50 w-full bg-muted-light dark:bg-gray-900/80 backdrop-blur-sm border-b border-gray-200 dark:border-gray-700 px-4 sm:px-6 lg:px-10 py-4 flex items-center gap-6">
<div class="flex items-center gap-8">
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
              <a href="index1.php" class="text-foreground-light dark:text-foreground-dark text-sm font-medium hover:text-primary transition-colors">Home</a>
              <a href="About.php" class="text-foreground-light dark:text-foreground-dark text-sm font-medium hover:text-primary transition-colors">About Us</a>
              <a href="Products.php" class="text-foreground-light dark:text-foreground-dark text-sm font-medium hover:text-primary transition-colors">Products</a>
              <a href="contact.php" class="text-foreground-light dark:text-foreground-dark text-sm font-medium hover:text-primary transition-colors">Contact</a>
              
              <a href="Detail.php" class="text-primary dark:text-primary text-sm font-bold">Details</a>
            </nav>
</div>
<div class="flex flex-1 justify-end gap-2 sm:gap-4">
<div class="relative w-full max-w-xs">
  <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">search</span>
  <input id="searchInput" type="text" placeholder="Search"
    class="w-full h-10 pl-10 pr-4 rounded-lg bg-gray-200/50 dark:bg-gray-800 text-sm" />
</div>



<div class="flex gap-2">
<button class="flex h-10 w-10 items-center justify-center rounded-lg bg-gray-200/50 dark:bg-gray-800 text-text-light dark:text-text-dark hover:bg-gray-200 dark:hover:bg-gray-700" onclick="window.location.href='Order.php'">
  <span class="material-symbols-outlined">shopping_cart</span>
</button>
<button class="flex h-10 w-10 items-center justify-center rounded-lg bg-gray-200/50 dark:bg-gray-800 text-text-light dark:text-text-dark hover:bg-gray-200 dark:hover:bg-gray-700" onclick="window.location.href='ac_user.php'">
  <span class="material-symbols-outlined">person</span>
</button>
</div>
</div>
</header>
<main class="flex-1 px-4 sm:px-6 lg:px-10 py-8">
<div class="mx-auto max-w-7xl">
<div class="flex flex-wrap gap-2 mb-8">
<a class="text-secondary text-sm font-medium leading-normal hover:text-primary" href="index1.php">Home</a>
<span class="text-secondary text-sm font-medium leading-normal">/</span>
<a class="text-secondary text-sm font-medium leading-normal hover:text-primary" href="Products.php">Shop</a>
<span class="text-secondary text-sm font-medium leading-normal">/</span>
<a class="text-secondary text-sm font-medium leading-normal hover:text-primary" href="Detail.php">Indoor Plants</a>
<span class="text-secondary text-sm font-medium leading-normal">/</span>
<span class="text-text-light dark:text-text-dark text-sm font-medium leading-normal">Monstera Deliciosa</span>
</div>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16">
<div class="flex flex-col gap-4">
<div class="w-full bg-center bg-no-repeat aspect-[4/3] bg-cover rounded-xl" data-alt="A large, healthy Monstera Deliciosa plant with fenestrated leaves in a terracotta pot." style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuD2E6sIsqL9MuPBwp1ZCxMOyF_AdKVIDz-EIZGKLTEyhI0XJxCjcTWDkrfKS_0EnfJ5UQsIJ-QqfBOCMaiY3UcHkiPquaHjkK5KbmtAuge_dwIDqyrSiu-siJxr9xVzwdA1R6Llt1AaNZixTTSonAJDh-rjZuksB889_msuP_ki8ndAfrmJGSfcWb0tIiHZ_P-wraJcQj7Cp4hUjSfD8AvcGKeryNUg8Ae7-RySaFhY-NmKom6c3mlbTq2Ln7VBZVNRWtyHdi3Si8s");'></div>
<div class="grid grid-cols-4 gap-4">
<div class="w-full bg-center bg-no-repeat aspect-square bg-cover rounded-lg cursor-pointer border-2 border-primary" data-alt="Close up of a Monstera leaf showing its characteristic splits." style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCLkESa9QCb2hZACtuh7w5Ift7LZovMYuez1j9yswBIUZkNCWddBlBSj9eMim0IC-GOSKSTrOlz3-K0r60lbRqVjp4Jg0ieXfnT5XLivhboSfFvlOuyXvi2K2kNVBjW0pAAPfG3y1_DP6TQRk7deQ1DBQFSy8qdUDB-V7aacDgSarIyC6uz68NV8cRWQMXiO9CpvLiyLIKSxetYQIGM4Bnv3JAZLPgif47CiZa_DsLXk379fFq4DNZ5A6Fbc1wJO6g7IfOJWJDMcIk");'></div>
<div class="w-full bg-center bg-no-repeat aspect-square bg-cover rounded-lg cursor-pointer opacity-70 hover:opacity-100" data-alt="The Monstera plant in a bright, modern living room setting." style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDkNLIlxsRYC-Ftq97ocFcDUnAyBu0X1fwRwNpJ3n3c_JgNaZcbajRMMy-yccyWdDFLNCpZ916tK00fOto-q6rLdQLjMTfEdsQ9C5tifxLReUmLs-N0_u8v9uy4FpRTgjl5bWjtg9oG81N7nc-bp4IsyihNFbgvHboQqDGfW1AmQwKrw9N9LcOvUMEBJJATN7Sg_nBYmhHVY3X3SYKyxbdc0Grot0tQnWBS5oo1H7lL4rb3pfSgQyxsRKv96wjtwHsURZRs23Yocik");'></div>
<div class="w-full bg-center bg-no-repeat aspect-square bg-cover rounded-lg cursor-pointer opacity-70 hover:opacity-100" data-alt="A new leaf unfurling on the Monstera plant." style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuAMs47vLM_LOd21mVa3LulImJwHkmLBPl2MQ5Kt0LHsOiVr-OFV4wVToe-HQvlAC-iRa_QRUt8Xg37325mfjie689yxxLs5O_Fi2ygDVEjQCsCsNyA3eA0-771tZ4j_w2iXvGTTwT7ajGmZl-DXOS02vg2XTyR8nJRLBowoSKaInPEhsBJAE9Cbj0zVQu4zqIeM6GrG_JUuKpWgDKg261o5Ep51VC_aVyG85BQ8r9qicBg2-mAzWwZX6_PCykYkDjO2unGgEzTzmb0");'></div>
<div class="w-full bg-center bg-no-repeat aspect-square bg-cover rounded-lg cursor-pointer opacity-70 hover:opacity-100" data-alt="Full view of the plant from a different angle." style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCiZrawncsrLFpDeG12m7pP1dDFcjGYzufBtd4DZzYdSVSjPqYioDIbUxj-VCZD4vNHTnCMq45prZkaipRu9r14W8KoS-27WjWDIaQ9RmBz-sZGWVw5EPv9kzqfwMAEHiiQmyXeUt2kwUApRQgp0YlGhvgqz_ey3SEKCqZKId6B2r3P-cEkiy1feS_p5dn3_oN9N6RtsjzwK8myl4s6qVofTG5ViIxz6Cnjit3rx-zPdAoQrVA9wk2jciVwvsmYbCgGkR1rWr_uq3s");'></div>
</div>
</div>
<div class="flex flex-col gap-6">
<div class="flex flex-col gap-3">
<h1 class="text-text-light dark:text-text-dark text-4xl lg:text-5xl font-extrabold leading-tight tracking-tighter">Monstera Deliciosa</h1>
<p class="text-secondary text-base font-normal leading-normal">Bring a touch of the tropics to your home with this iconic, easy-care plant.</p>
<div class="flex items-baseline gap-4 mt-2">
<p class="text-text-light dark:text-text-dark text-3xl font-bold">$45.00</p>
<p class="text-gray-400 line-through text-xl font-medium">$55.00</p>
<span class="bg-accent/20 text-accent font-bold text-xs px-2 py-1 rounded-full">SALE</span>
</div>
</div>
<div class="flex flex-col sm:flex-row sm:items-center gap-4">
<div class="flex items-center border border-gray-200 dark:border-gray-700 rounded-lg p-2">
<button class="px-3 py-1 text-lg font-bold text-primary hover:bg-gray-200/50 dark:hover:bg-gray-700 rounded-md">-</button>
<input class="w-12 text-center bg-transparent border-0 focus:ring-0 text-text-light dark:text-text-dark font-medium" type="text" value="1"/>
<button class="px-3 py-1 text-lg font-bold text-primary hover:bg-gray-200/50 dark:hover:bg-gray-700 rounded-md">+</button>
</div>
<button class="w-full sm:w-auto flex-1 flex items-center justify-center gap-3 bg-primary text-white font-bold py-3 px-8 rounded-lg hover:opacity-90 transition-opacity">
<span class="material-symbols-outlined">add_shopping_cart</span>
<span>Add to Cart</span>
</button>
</div>
<div class="border-t border-gray-200 dark:border-gray-700 pt-6 space-y-4">
<div>
<h3 class="font-bold text-lg mb-2">Description</h3>
<p class="text-secondary text-sm">The Monstera Deliciosa, often called the Swiss Cheese Plant, is famous for its natural leaf holes. A native of tropical forests, it's a dramatic and fast-growing vine that makes a stunning statement piece in any room. It is surprisingly easy to care for and is known for its air-purifying qualities.</p>
</div>
<div>
<h3 class="font-bold text-lg mb-2">Care Guide</h3>
<ul class="list-disc list-inside text-secondary text-sm space-y-1">
<li><strong>Light:</strong> Bright, indirect sunlight. Avoid direct sun which can scorch the leaves.</li>
<li><strong>Water:</strong> Water thoroughly when the top 2 inches of soil are dry.</li>
<li><strong>Humidity:</strong> Prefers high humidity but tolerates average household levels.</li>
</ul>
</div>
<div>
<h3 class="font-bold text-lg mb-2">Shipping Info</h3>
<p class="text-secondary text-sm">Ships in a 6" nursery pot. Height is approximately 18-24 inches. We ship Monday-Wednesday to avoid weekend delays. Expect delivery within 3-5 business days.</p>
</div>
</div>
</div>
</div>
</div>
<div class="mx-auto max-w-7xl mt-16 sm:mt-24">
<div class="border-t border-gray-200 dark:border-gray-700 pt-12">
<h2 class="text-3xl font-bold text-text-light dark:text-text-dark mb-8 text-center">You Might Also Like</h2>
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
<div class="flex flex-col gap-3 group">
<div class="relative overflow-hidden rounded-lg">
<div class="w-full bg-center bg-no-repeat aspect-[4/5] bg-cover rounded-lg transition-transform duration-300 group-hover:scale-105" data-alt="A Fiddle Leaf Fig plant in a white pot." style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuAHR0ChuAWMrNQA-ykHqZqbgjWf0S0oLJKgYEDIgPiCTcxwlt8hHSzyKTpSBGbmPAXSEvlu10KGN6C2DT3Ly-nVvslXAYpuu-iORaZfkb73EGHAlx0Jc-Px9DjltuaQbeY3YtSTKLPBamN8SeiLMV3Fm1iuI0dLz29z2tM5JfiqdPoGv_kdx4tfRdVZQOTLffVstkGuwcqCF5xonIuuQN7pgpElIaLn6ERLNcPuivPU32PshUSFUyVl8hpkaLs4Jy-uCi7x7CPLlGw");'></div>
<button class="absolute bottom-3 right-3 h-10 w-10 flex items-center justify-center bg-primary text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300">
<span class="material-symbols-outlined text-xl">add</span>
</button>
</div>
<div class="flex flex-col items-center text-center">
<h4 class="font-semibold text-text-light dark:text-text-dark">Fiddle Leaf Fig</h4>
<p class="text-secondary text-sm">$65.00</p>
</div>
</div>
<div class="flex flex-col gap-3 group">
<div class="relative overflow-hidden rounded-lg">
<div class="w-full bg-center bg-no-repeat aspect-[4/5] bg-cover rounded-lg transition-transform duration-300 group-hover:scale-105" data-alt="A Snake Plant in a minimalist pot." style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDURqEMQONQA8gaCg3SS7MxLX1HLah7n6pqGuKIsqWtkttdwBjUbTdKJ2ebotFba_RDfnSLyiwqHuQm4SlhgcBZoDe6L50Q4vBXJOICI7IQ0uA5zKmuEdmn1OG72Q5tdpeG2sCyEqNmZYUXo5mUgI6EP4iSFNOgLkTaB8w8q5-f15h7wGkPX_m-IFtFwlDXOMLHNiR9bGS3En2sfP2eaLI8H3m487Data-swdW_ILwu-bkAL41w8pB_zbgdQbv60AXaJjOeSeTk2eo");'></div>
<button class="absolute bottom-3 right-3 h-10 w-10 flex items-center justify-center bg-primary text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300">
<span class="material-symbols-outlined text-xl">add</span>
</button>
</div>
<div class="flex flex-col items-center text-center">
<h4 class="font-semibold text-text-light dark:text-text-dark">Snake Plant</h4>
<p class="text-secondary text-sm">$35.00</p>
</div>
</div>
<div class="flex flex-col gap-3 group">
<div class="relative overflow-hidden rounded-lg">
<div class="w-full bg-center bg-no-repeat aspect-[4/5] bg-cover rounded-lg transition-transform duration-300 group-hover:scale-105" data-alt="A Pothos plant with trailing vines." style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuD_j14_bvShBYoUQDPGQmxat6zeqtvJKXelrYhPy1OS1wGB5WNK0jBnItX5jot7DaIhrvPOTFscfcfmESPe8pqhsWPZIqoFkAW4BXE6goKarRWERuZwa_I1PyKR5iw3Qsu5opGjcY7X-fIki-Gi9Dc0UeSVR-ry7u_-aaWZ26b3q3MdNKMp5ip1AfbNzPdlsf3MQgmtB0vvtO7aXpdPJ52qoLEHkSNUVZNADSJ1VU8fe0hAG1l5AGmRqiaQ5ccuIEKs5Kz8FFxawnk");'></div>
<button class="absolute bottom-3 right-3 h-10 w-10 flex items-center justify-center bg-primary text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300">
<span class="material-symbols-outlined text-xl">add</span>
</button>
</div>
<div class="flex flex-col items-center text-center">
<h4 class="font-semibold text-text-light dark:text-text-dark">Golden Pothos</h4>
<p class="text-secondary text-sm">$25.00</p>
</div>
</div>
<div class="flex flex-col gap-3 group">
<div class="relative overflow-hidden rounded-lg">
<div class="w-full bg-center bg-no-repeat aspect-[4/5] bg-cover rounded-lg transition-transform duration-300 group-hover:scale-105" data-alt="A ZZ Plant with dark green, glossy leaves." style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDBtwYY_rBo1s5jHIv2Onq27lVQIqZznWjGvCSBkqIm8OhQy6tY_FtH4Dh1VC-m7iHxvfod1BugvBFquifD_q04x_J38iOs8fOUXf99OOk1A1_6FDYSRA4otsM16wrSS6h9FFUlv_-h4h1mL6_qwV23tGdlVHisAkJGAHvsp3vkfexcUUEroDUR9fTia21JzGOrqN0F_zRPEMMX_wDmhcLa79s1D5QvewzCzXu_PARF1KHo4Q3B_v5oyQ7ri9rSfyk05ZT2wtrqz-c");'></div>
<button class="absolute bottom-3 right-3 h-10 w-10 flex items-center justify-center bg-primary text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300">
<span class="material-symbols-outlined text-xl">add</span>
</button>
</div>
<div class="flex flex-col items-center text-center">
<h4 class="font-semibold text-text-light dark:text-text-dark">ZZ Plant</h4>
<p class="text-secondary text-sm">$40.00</p>
</div>
</div>
</div>
</div>
</div>
</main>
</div>
</div>
</body></html>
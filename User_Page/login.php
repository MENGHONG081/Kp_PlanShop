<?php
require 'config.php'; // contains $pdo connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? null;
    //$fullname = $_POST['fullname'] ?? null;
    $password = $_POST['password'] ?? null;

    if ($email && $password) {

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
         // ✅ Create session from DB values
            $_SESSION['user'] = $user['fullname'];
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email']    = $user['email'];
            $_SESSION['just_logged_in'] = true;

            header("Location: index1.php");
            exit;

        } else {
            echo "<script>alert('Invalid email or password.');</script>";
        }

    } else {
        echo "<script>alert('Please fill in all fields.');</script>";
    }
}

?>
<!DOCTYPE html>
<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>The Leafy Loft - Login</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com" rel="preconnect"/>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<script src="Home.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
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
            "primary": "#3D550C",
            "background-light": "#f6f8f6",
            "background-dark": "#102213",
            "text-light": "#333333",
            "text-dark": "#E0E0E0",
            "accent": "#D97925",
            "field-bg-light": "#F4F4F4",
            "field-bg-dark": "#1A2E1D",
            "field-border-light": "#E0E0E0",
            "field-border-dark": "#2C402F",
          },
          fontFamily: {
            "display": ["Manrope", "sans-serif"]
          },
          borderRadius: {
            "DEFAULT": "0.25rem",
            "lg": "0.5rem",
            "xl": "0.75rem",
            "full": "9999px"
          },
        },
      },
    }
  </script>
</head>
<body class="font-display bg-background-light dark:bg-background-dark">
<div id="design-root" class="design-root">
<div class="relative flex min-h-screen w-full flex-col group/design-root overflow-x-hidden">
<div class="layout-container flex h-full grow flex-col">
<div class="flex flex-1 justify-center">
<div class="layout-content-container flex flex-col w-full flex-1">
<div class="grid grid-cols-1 md:grid-cols-2 min-h-screen">
<!-- Left Column: Image -->
<div class="hidden md:flex flex-col bg-center bg-no-repeat bg-cover aspect-auto" data-alt="A sunlit room filled with a variety of lush green houseplants, creating a serene and natural atmosphere." style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDn57njUOiQAnKfMoN6I8X3dVDcRLzDqKKcedlofopanxfiAqKQpWazjHy3zpNmUc-7xm-Pu0PvNPVv3ViWXEvBP5yb_3h_QBFNE5TmtTAAp1Ug3FuEaN8Tgp7Qx0o5JLlGUSJKJxaCtu2Z7nfOIrCb_cN9KPHiW-u1tXHrW4l5Q0UVdqWFGrXTsRxjXiZIvev80nhIaA3MiyggNSzG6JXdp_bk0XcSVv9Y2qmKgR_jZ8o_hSw1HHfdPepC8PaPofpvUhJRoiW8YW8");'></div>
<!-- Right Column: Form -->
<div class="flex w-full items-center justify-center p-8 lg:p-12">
<div class="flex flex-col w-full max-w-md gap-6">
<!-- Logo -->
  <div class="flex items-center gap-2 self-start mb-4">
  <span><img src="icon/plant_cactus_flower_nature_flower_pot_garden_planter_icon_141184.png" alt="Kp Plant_Shop Logo" width="40" height="40"></span>
  <p class="text-2xl font-bold text-text-light dark:text-text-dark">Kp Plant_Shop</p>
  </div>
<form method="POST" action="login.php" class="w-full max-w-md space-y-6">
<div class="flex flex-col gap-6">
<!-- Headline -->
<h1 class="text-text-light dark:text-text-dark tracking-tight text-[32px] font-bold leading-tight text-left">Welcome Back!</h1>
<!-- Email Input -->
  <div class="flex w-full flex-wrap items-end">
  <label class="flex flex-col w-full flex-1">
  <p class="text-text-light dark:text-text-dark text-base font-medium leading-normal pb-2">Email</p>
  <input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-text-light dark:text-text-dark focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-field-border-light dark:border-field-border-dark bg-field-bg-light dark:bg-field-bg-dark focus:border-primary dark:focus:border-green-400 h-14 placeholder:text-gray-400 dark:placeholder:text-gray-500 p-4 text-base font-normal leading-normal" type="email" name="email" placeholder="Enter your email" required/>
  </label>
  </div>
<!-- Password Input -->
    <div class="flex w-full flex-wrap items-end">
    <label class="flex flex-col w-full flex-1">
    <p class="text-text-light dark:text-text-dark text-base font-medium leading-normal pb-2">Password</p>
    <div class="flex w-full flex-1 items-stretch rounded-lg">
    <input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-text-light dark:text-text-dark focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-field-border-light dark:border-field-border-dark bg-field-bg-light dark:bg-field-bg-dark focus:border-primary dark:focus:border-green-400 h-14 placeholder:text-gray-400 dark:placeholder:text-gray-500 p-4 rounded-r-none border-r-0 pr-2 text-base font-normal leading-normal"type="password" name="password" placeholder="Enter your password" type="password" value=""/>
    <div class="text-gray-500 dark:text-gray-400 flex border border-field-border-light dark:border-field-border-dark bg-field-bg-light dark:bg-field-bg-dark items-center justify-center px-4 rounded-r-lg border-l-0" data-icon="Eye">
    <span class="material-symbols-outlined cursor-pointer">visibility</span>
     </div>
     </div>
    </label>
    </div>
<!-- Forgot Password Link -->
  <div class="w-full flex justify-end">
  <p class="text-primary dark:text-green-400 hover:text-accent dark:hover:text-accent text-sm font-medium leading-normal underline cursor-pointer">Forgot Password?</p>
  </div>
<!-- Login Button -->
<button class="flex items-center justify-center text-center font-bold text-base h-14 w-full rounded-lg bg-primary text-white hover:bg-opacity-90 transition-colors duration-200 mt-4" id="loginBtn">Login</button>
<!-- Sign Up Link -->
<p class="text-text-light dark:text-text-dark text-sm font-normal text-center pt-6">New here? <a class="font-bold text-primary dark:text-green-400 hover:text-accent dark:hover:text-accent underline" href="Sige_Up.php">Create an account</a></p>
</div>
  </form>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
    </div>
</body>
</html>

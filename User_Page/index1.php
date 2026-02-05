<?php
require 'config1.php'; // Session and cart handling

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_SESSION['success'])) {
    echo "<div class='alert alert-success'>".$_SESSION['success']."</div>";
    unset($_SESSION['success']); // clear after showing
}


$fullname = $_SESSION['user'];
$email = $_SESSION['email'];
//$fullname = $_SESSION['fullname'];

// Determine user info
$isLoggedIn = isset($_SESSION['user']); // Choose one consistent key, e.g., 'user'
//$fullname = $isLoggedIn && isset($_SESSION['fullname']) ? htmlspecialchars($_SESSION['fullname']) : 'Guest';
//$username = $isLoggedIn ? htmlspecialchars($_SESSION['user']) : '';
// feedback backend logic can go here if needed
// add img user
$userId = $_SESSION['user_id'];
$successMessage = '';
$errorMessage = '';
$userProfile = null;

// Fetch user profile
try {
    $profileuserStmt = $pdo->prepare("SELECT id, imgUser FROM users WHERE id = ?");
    $profileuserStmt->execute([$userId]);
    $userProfile = $profileuserStmt->fetch(PDO::FETCH_ASSOC);
    if (!$userProfile) {
        header("Location: login.php");
        exit();
    }
} catch (PDOException $e) {
    $errorMessage = 'Error loading profile. Please try again.';
    error_log("Profile fetch error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="robots" content="noindex, nofollow">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Welcome to KP Plant Shop! Discover a wide variety of high-quality indoor and outdoor plants, gardening supplies, and expert tips to grow your green family.">
    <meta property="og:title" content="KP Plant Shop - Your Green Sanctuary">
    <meta property="og:description" content="Shop the best selection of tropical plants and gardening tools at KP Plant Shop.">
    <meta property="og:image" content="https://yourdomain.com/image/13.jpg">
    <link rel="icon" type="image/png" href="icon/plant_cactus_flower_nature_flower_pot_garden_planter_icon_141184.png">
    <title>KP Plant_Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Home.css">
    <script src="Home.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
     <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Redirect to login if user clicks anywhere on the page while not logged in
            // Remove or adjust this if it's too aggressive
            document.body.addEventListener('click', function (e) {
                <?php if (!$isLoggedIn): ?>
                    // Only redirect if clicking on something that looks like a protected action
                    if (e.target.closest('.protected-action') || e.target.closest('button, a, .card')) {
                        alert('Please login first!');
                        window.location.href = 'login.php';
                    }
                <?php endif; ?>
            });

            // Handle buttons with class 'protected-action'
            document.querySelectorAll('.protected-action').forEach(btn => {
                btn.addEventListener('click', function (e) {
                    <?php if (!$isLoggedIn): ?>
                        e.preventDefault();
                        alert('Please login first!');
                        window.location.href = 'login.php';
                    <?php else: ?>
                        alert('Action performed successfully.');
                        // Proceed with actual action here if needed
                    <?php endif; ?>
                });
            });
        });
    </script>
 
 <style>
  /* Background tint for icons */
.bg-success-subtle {
    background-color: rgba(25, 135, 84, 0.1) !important;
}

/* Smooth zoom effect for images */
.hover-zoom {
    transition: transform 0.5s ease, box-shadow 0.5s ease;
    cursor: pointer;
}

.hover-zoom:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.15) !important;
}

.hover-zoom img {
    transition: transform 0.8s ease;
}

.hover-zoom:hover img {
    transform: scale(1.1);
}

/* Line height for better readability */
.leading-relaxed {
    line-height: 1.7;
}

/* Content Entrance Animation */
.transition-up {
    transition: all 0.3s ease;
}

.transition-up:hover {
    transform: translateX(10px);
}
.custom-hr { border: none; height: 3px; background: linear-gradient(to right, #28a745, #a8e063); border-radius: 2px; margin: 1rem 0; }

/* Container for the floating button */
.ai-float-button {
  position: fixed;
  bottom: 25px;
  right: 25px;
  width: 65px;
  height: 65px;
  padding: 0;
  border: none;
  border-radius: 50%;
  cursor: pointer;
  z-index: 1000;
  background: none; /* Removes default button gray */
  transition: transform 0.2s;
  box-shadow: 0 4px 15px rgba(0,0,0,0.3);
}

.ai-float-button:hover {
  transform: scale(1.1);
}

/* Makes the image a perfect circle inside the button */
.ai-img-circle {
  width: 100%;
  height: 100%;
  border-radius: 50%;
  object-fit: cover; /* Prevents the image from looking squashed */
  display: block;
}

/* Chat window styling */
.chat-window {
  position: fixed;
  bottom: 100px;
  right: 25px;
  width: 320px;
  height: 400px;
  background: white;
  border-radius: 15px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.2);
  display: none; /* Hidden by default */
  flex-direction: column;
  z-index: 1001;
  overflow: hidden;
}

.chat-header {
  background: #007bff;
  color: white;
  padding: 15px;
  display: flex;
  justify-content: space-between;
}

.close-btn {
  background: none;
  border: none;
  color: white;
  font-size: 20px;
  cursor: pointer;
}
.search-container{
  position: -webkit-sticky; /* For Safari support */
  position: sticky;
  top: 0;
  width: 100%;
}

/* Top nav / header theme */
header, .navbar, .top-nav {
  background: linear-gradient(90deg,#16a34a 0%, #059669 100%);
  color: #fff;
}
header a, .navbar a { color: rgba(255,255,255,0.95); }
header .btn, .navbar .btn { border-radius: 999px; }

/* Footer theme */
footer {
  background: linear-gradient(90deg,#16a34a 0%, #059669 100%);
  color: #fff;
}

/* Ensure login/signup anchors stand out */
a[href*="login.php"], a[href*="signup.php"]{
  background: #fff; color:#059669; padding: .4rem .8rem; border-radius: 999px; font-weight:600; text-decoration:none;
}
</style>
</head>
<body >
<?php include 'nav.php';?>
<!-- SEARCH BAR UNDER NAV -->
<div class="search-container  mb-2">
    <div class="left-search">
        <input class="form-control me-2" type="search"placeholder="Search for plants..." aria-label="Search">
        <button class="btn btn-success me-2" type="submit">Search</button>
    </div>
    <div class="icon-group alighn-items-center d-flex justify-content-center gap-3">
        <img src="icon/4105939-info-information_113916.ico" width="32" height="32" class="img-colorful" />
        <img src="icon/3844439-gear-setting-settings-wheel_110294.ico" width="32" height="32" class="img-colorful" />
        <img src="icon/buy_cart_ecommerce_basket_shopping_icon_227311.ico" width="32" height="32" class="img-colorful" onclick="window.location.href='Order.php'" />
        <img src="https://img.icons8.com/?size=100&id=saSupsgVcmJe&format=png&color=000000" width="32" height="32" class="img-colorful" onclick="window.location.href='ai_chat.php'"/>
        <div class="d-flex gap-2  align-items-center text-success">
        <img src="<?php echo htmlspecialchars($userProfile['imgUser'] ?? 'icon/icons8-account.gif'); ?>" width="32" height="32" class="img-colorful" onclick="window.location.href='ac_user.php'" />
        <span class="fw-bold" id="user-name"> Hi <?php echo $fullname; ?><br><p> Welcome Back!</p></span>
       </div>
       
    <div class="d-flex gap-2">
    <!-- Login/Logout Buttons -->
                <?php if ($isLoggedIn): ?>
                    <a href="logout.php" class="btn btn-outline-danger btn-sm">Log out</a>
                    <?php if ($fullname === 'admin'): // or however you check admin ?>
                        <a href="/plant_admin/login.php" class="btn btn-outline-success btn-sm">Admin</a>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="login.php" class="btn btn-outline-success btn-sm">Log in</a>
                    <a href="signup.php" class="btn btn-outline-primary btn-sm">Sign Up</a>
                <?php endif; ?>
  </div>
    </div>
</div> 
<div class="text-success">
  <hr>
</div>
<div class="container ">
  <!-- JavaScript to handle protected actions -->
  <!-- Hero Section with Carousel and Overlay Text -->
  <div class="row align-items-center mb-5">
    <div class="col-12">
      <div id="heroCarousel" class="carousel slide position-relative" data-bs-ride="carousel">
        <div class="carousel-inner rounded shadow">
          <div class="carousel-item active">
            <img src="vgif/5.gif" class="d-block w-100 img-colorful" style="max-height:400px; object-fit:cover;" alt="Featured Plant 1">
            <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-3">
              <div class = "wrapper  ">
        <marquee id="slide" direction="right" scollamount="4" >
          <div class="images" id="imgbox">
            <h2 class="mb-2 text-warning">Welcome to KP Plant_Shop</h2>
          </div>
           </marquee>
         </div>
              
              <p class="lead text-white">Your one-stop shop for all things green and beautiful!</p>
            </div>
          </div>
          <div class="carousel-item">
            <img src="https://i.pinimg.com/originals/65/b0/59/65b05933ab0776a765ef40a47564fb80.gif" class="d-block w-100 img-colorful" style="max-height:400px; object-fit:cover;" alt="Featured Plant 2">
            <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-3">
              <h2 class="mb-2 text-warning">Welcome to KP Plant_Shop</h2>
              <p class="lead text-white">Your one-stop shop for all things green and beautiful!</p>
            </div>
          </div>
          <div class="carousel-item">
            <img src="https://i.pinimg.com/originals/0f/57/74/0f5774f9540988e95749051084517f28.gif" class="d-block w-100 img-colorful" style="max-height:400px; object-fit:cover;" alt="Featured Plant 3">
            <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-3">
              <h2 class="mb-2 text-warning">Welcome to KP Plant_Shop</h2>
              <p class="lead text-white">Your one-stop shop for all things green and beautiful!</p>
            </div>
          </div>
        <div class="carousel-item">
            <img src="vgif/1.gif" class="d-block w-100 img-colorful" style="max-height:400px; object-fit:cover;" alt="Featured Plant 3">
            <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-3">
              <h2 class="mb-2 text-warning">Welcome to KP Plant_Shop</h2>
              <p class="lead text-white">Your one-stop shop for all things green and beautiful!</p>
            </div>
          </div>
          <div class="carousel-item">
            <img src="vgif/2.gif" class="d-block w-100 img-colorful" style="max-height:400px; object-fit:cover;" alt="Featured Plant 3">
            <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-3">
              <h2 class="mb-2 text-warning">Welcome to KP Plant_Shop</h2>
              <p class="lead text-white">Your one-stop shop for all things green and beautiful!</p>
            </div>
          </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>
      </div>
    </div>
  </div>

  <!-- AI Float Buttons -->
       <button class="ai-float-button">
        <img src="https://www.htx.gov.sg/images/default-source/news/2024/ai-article-1-banner-shot-min.jpg?sfvrsn=4b7c6915_3" 
            alt="AI Assistant" 
            class="ai-img-circle"
            class="img-colorful"
            onclick="window.location.href='ai_chat.Php' ">
      </button>

<div id="ai-chat-window" class="chat-window">
  <div class="chat-header">
    <span>AI Assistant</span>
    <button onclick="toggleAIChat()" class="close-btn">&times;</button>
  </div>
  <div class="chat-body">How can I help you today?</div>
</div>

 <div class="container py-5">
  <div class="row align-items-center g-5">

    <div class="col-lg-6">
      <div class="mb-5">
        <span class="text-uppercase fw-bold text-success tracking-widest small">Since 2010</span>
        <h2 class="display-5 fw-bold text-dark mb-3">Growing Our <span class="text-success">Green Family</span></h2>
        <div class="rounded-pill bg-success" style="height: 5px; width: 80px;"></div>
      </div>

      <div class="d-flex mb-4 transition-up">
        <div class="me-3">
          <div class="bg-success-subtle p-2 rounded-3 text-success">
             <i class="bi bi-seedling-fill h4"></i> </div>
        </div>
        <div>
          <h5 class="fw-bold text-success mb-1">Our Journey</h5>
          <p class="text-muted leading-relaxed">
            KP Plant_Shop sprouted from a small family passion. What began in a backyard greenhouse has blossomed into a beloved sanctuary for the local gardening community.
          </p>
        </div>
      </div>

      <div class="d-flex mb-4 transition-up">
        <div class="me-3">
          <div class="bg-success-subtle p-2 rounded-3 text-success">
             <i class="bi bi-heart-pulse-fill h4"></i>
          </div>
        </div>
        <div>
          <h5 class="fw-bold text-success mb-1">Our Mission</h5>
          <p class="text-muted leading-relaxed">
            We don't just sell plants; we cultivate joy. Our mission is to make high-quality greenery accessible to everyone, from first-time plant parents to master gardeners.
          </p>
        </div>
      </div>

      <div class="d-flex mb-0 transition-up">
        <div class="me-3">
          <div class="bg-success-subtle p-2 rounded-3 text-success">
             <i class="bi bi-people-fill h4"></i>
          </div>
        </div>
        <div>
          <h5 class="fw-bold text-success mb-1">Community Involvement</h5>
          <p class="text-muted leading-relaxed">
            We grow together. Through workshops and plant swaps, we’re building a greener, more connected world, one leaf at a time.
          </p>
        </div>
      </div>
    </div>

    <div class="col-lg-6">
      <div class="row g-3">
        <div class="col-7">
          <div class="hover-zoom rounded-4 shadow-lg overflow-hidden h-100">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTcy5I0EfK8jK1BlWmYUa-XZlggzgv6H4szkw&s" 
                 class="img-fluid w-100 h-100 object-fit-cover" alt="Nursery">
          </div>
        </div>
        <div class="col-5">
          <div class="row g-3">
            <div class="col-12">
              <div class="hover-zoom rounded-4 shadow overflow-hidden">
                <img src="image/13.jpg" class="img-fluid" alt="Plants">
              </div>
            </div>
            <div class="col-12">
              <div class="hover-zoom rounded-4 shadow overflow-hidden">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR_89vePkYn2E6PxZXvgxPS5DDPdJ7LWSp71w&s" 
                     class="img-fluid" alt="Greenery">
              </div>
            </div>
          </div>
        </div>
        <div class="col-12">
          <div class="hover-zoom rounded-4 shadow overflow-hidden" style="max-height: 200px;">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSyHdgqRBjkyaLxxMWSYMzNKaz9SiDUYtBJPA&s" 
                 class="img-fluid w-100 object-fit-cover" alt="Shop Interior">
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
<!-- Product show  Section -->
<div class="text-center py-5">
    <h2 class="display-5 fw-bold text-success mb-2">My Product </h2>
    <div class="d-flex justify-content-center">
        <div class="bg-primary rounded" style="width: 60px; height: 4px;"></div>
    </div>
    <p class="text-muted mt-3"> Detailed information about our products.</p>
  </div>

  <!-- arrival Section -->
  <div class="section-header d-flex align-items-center mb-3"> <!-- Icon --> 
    <i class="bi bi-stars text-success fs-2 me-2"></i>
     <!-- Text --> <h2 class="mb-0 fw-bold" style="font-family: 'Poppins', sans-serif;">New Arrival</h2>
     </div> <!-- Styled horizontal rule --> <hr class="custom-hr">
  <p class="mb-4">Check out the latest additions to our plant collection.</p>
  <?php include 'products_arv.php'; ?>

  <!-- Best Seller Section -->
  <div class="section-header d-flex align-items-center mb-3"> <!-- Icon --> 
    <i class="bi bi-trophy-fill text-success fs-2 me-2"></i>
     <!-- Text --> <h2 class="mb-0 fw-bold" style="font-family: 'Poppins', sans-serif;"> Best Seller</h2>
     </div> <!-- Styled horizontal rule --> <hr class="custom-hr">
  <p class="mb-4">This Product Is Popular For Together.</p>
<?php if (empty($bestSellers)): ?>
            <p class="text-center text-muted">No  products Seller .</p>
        <?php else: ?>
<div class="row g-4 mb-5 ">
<?php foreach ($bestSellers as $p): ?>
  <div class="col-md-3 col-sm-6">
  <div class="d-flex flex-nowrap overflow-auto p-2" style="gap: 20px;">
  <div class="product-card h-100 text-center border p-3 rounded shadow-sm position-relative">
    <div class="position-absolute top-0 start-0 m-2">
    <span class="badge bg-warning text-dark">
    <i class="bi bi-star-fill me-1"></i> Best Seller
    </span>
    </div>
    <img src="../plant_admin/uploads/<?= htmlspecialchars($p['image'] ?? 'placeholder.png') ?>"
                 class="card-img-top product-img"
                 style="height: 250px; width: 200px; object-fit: cover; transition: transform 0.4s ease;"
                 loading="lazy"
                 class="img-colorful product-img mb-3">
      <h5><?= htmlspecialchars($p['name']) ?></h5>
    <p class="text-success fw-bold">$<?= number_format($p['price'], 2) ?></p>
    <button onclick="window.location.href='/PLANT_PROJECT/User_Page/Products.php'" class="btn btn-success btn-sm">Add to Cart</button>
    <button class="btn btn-outline-primary btn-sm ms-2">View</button>
  </div>
</div>
</div>
<?php endforeach; ?>
</div>
<?php endif; ?>

  <!-- Product Promotions Section -->
  <a href="Order.php" class="section-header d-flex align-items-center mb-3 text-decoration-none"> <!-- Icon --> 
    <i class="bi bi-tag-fill text-success fs-2 me-2"></i>
    <h2 class="mb-0 fw-bold" style="font-family: 'Poppins', sans-serif;">Promotionss && Discounts</h2>
  </a>
<hr class="custom-hr">
<?php if (empty($discountProducts)): ?>
  <div class="text-center py-5">
    <p class="text-muted mb-0">No discounted products at the moment.</p>
  </div>
<?php else: ?>
  <section class="container mb-5">
    <div class="row g-4">
      <?php foreach ($discountProducts as $p):
        $finalPrice = $p['price_after_discount'] ?? ($p['price'] * (1 - $p['discount_percent']/100));
      ?>
        <div class="col-md-6 col-lg-4">
          <div class="card h-100 border-0 shadow-sm product-card modern-card position-relative overflow-hidden">
            <!-- Modern ribbon-style discount badge -->
            <div class="position-absolute top-0 start-0 z-3">
              <span class="discount-ribbon bg-danger text-white fw-bold px-4 py-2">
                <i class="bi bi-tag-fill"></i>
                -<?= $p['discount_percent'] ?>%
              </span>
            </div>
            <img src="../plant_admin/uploads/<?= htmlspecialchars($p['image'] ?? 'placeholder.png') ?>"
                 alt="<?= htmlspecialchars($p['name']) ?>"
                 class="card-img-top product-img"
                 style="height: 280px; object-fit: cover; transition: transform 0.4s ease;"
                 loading="lazy">
            <div class="card-body d-flex flex-column p-4">
              <!-- Product-specific title (more engaging than generic) -->
              <h5 class="card-title fw-bold text-dark mb-2">
                <?= htmlspecialchars($p['name']) ?>
              </h5>
              <!-- Discount description / short info -->
              <p class="card-text text-muted flex-grow-1 small">
                <?= htmlspecialchars($p['discount_desc'] ?? 'Limited time offer on this beautiful plant!') ?>
              </p>
              <!-- Price section - bold discount price + savings highlight -->
              <div class="price-wrap mb-4 d-flex align-items-baseline">
                <span class="fs-3 fw-bold text-danger me-2">$<?= number_format($finalPrice, 2) ?></span>
                <span class="text-decoration-line-through text-muted me-3">$<?= number_format($p['price'], 2) ?></span>
                <span class="badge bg-success small">Save $<?= number_format($p['price'] - $finalPrice, 2) ?></span>
              </div>
              <a href="products.php" class="btn btn-success mt-auto fw-bold">Shop Now →</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>
<?php endif; ?>

  <!-- More Products Section -->
  <div class="section-header d-flex align-items-center mb-3"> <!-- Icon --> 
    <i class="bi bi-basket3-fill  text-success fs-2 me-2"></i>
     <!-- Text --> <h2 class="mb-0 fw-bold" style="font-family: 'Poppins', sans-serif;">Our Products</h2>
     </div> <!-- Styled horizontal rule --> <hr class="custom-hr">
    <p class="mb-4">Explore our wide range of plants and gardening supplies.</p>
  <?php include 'product_disply.php'; ?>

  <div class="section-header d-flex align-items-center mb-3"> <!-- Icon --> 
    <i class="bi bi-lightbulb-fill text-success fs-2 me-2"></i>
     <!-- Text --> <h2 class="mb-0 fw-bold" style="font-family: 'Poppins', sans-serif;">Details Products</h2>
     </div> <!-- Styled horizontal rule --> <hr class="custom-hr">

  <?php include 'Detail_inf.php'; ?>
  <!-- Modern Partners Section -->
<section class="partners-section py-5">
  <div class="container">
    <!-- Section Header -->
    <div class="section-header text-center mb-5">
      <h2 class="display-5 fw-bold text-success mb-3">Trusted By Industry Leaders</h2>
      <div class="divider mx-auto mb-3">
        <div class="line"></div>
        <div class="circle"></div>
        <div class="line"></div>
      </div>
      <p class="lead text-muted mb-0">We collaborate with premier growers, nurseries, and gardening innovators to bring you exceptional quality plants and sustainable solutions.</p>
    </div>

    <!-- Partners Grid -->
    <div class="row g-4 justify-content-center mb-5">
      <!-- Costa Farms (Major plant producer) -->
      <div class="col-6 col-md-4 col-lg-3">
        <div class="partner-card h-100 text-center p-4 rounded-4 shadow-sm bg-white">
          <div class="partner-logo-wrapper mb-4 d-flex align-items-center justify-content-center" style="height: 80px;">
            <img src="https://costagroup.currentjobs.co/content/skins/CostaGroup/img/Costa_Tag-Logo_Dark-Green_RGB.png" 
                 alt="Costa Farms" 
                 class="img-fluid" 
                 style="max-height: 160px; filter: brightness(0.9);">
          </div>
          <h5 class="card-title text-success fw-semibold mb-2">Premium Plant Supply</h5>
          <p class="card-text text-muted small mb-0">America's largest indoor houseplant producer providing rare and trending varieties.</p>
        </div>
      </div>

      <!-- Monrovia (Premium nursery) -->
      <div class="col-6 col-md-4 col-lg-3">
        <div class="partner-card h-100 text-center p-4 rounded-4 shadow-sm bg-white">
          <div class="partner-logo-wrapper mb-4 d-flex align-items-center justify-content-center" style="height: 80px;">
            <img src="https://blog.plantmaster.com/wp-content/uploads/2018/01/Monrovia-300x200.png" 
                 alt="Monrovia" 
                 class="img-fluid" 
                 style="max-height: 150px;">
          </div>
          <h5 class="card-title text-success fw-semibold mb-2">Exclusive Varieties</h5>
          <p class="card-text text-muted small mb-0">High-quality, uniquely bred plants with sustainable growing practices since 1926.</p>
        </div>
      </div>

      <!-- The Sill (Modern plant retailer) -->
      <div class="col-6 col-md-4 col-lg-3">
        <div class="partner-card h-100 text-center p-4 rounded-4 shadow-sm bg-white">
          <div class="partner-logo-wrapper mb-4 d-flex align-items-center justify-content-center" style="height: 80px;">
            <img src="https://bestselfmedia.com/wp-content/uploads/2016/06/The-Sill_LOGO.jpg" 
                 alt="The Sill" 
                 class="img-fluid" 
                 style="max-height: 180px;">
          </div>
          <h5 class="card-title text-success fw-semibold mb-2">Urban Plant Solutions</h5>
          <p class="card-text text-muted small mb-0">Modern plant care products and designer planters for contemporary indoor gardens.</p>
        </div>
      </div>

      <!-- Proven Winners (Plant genetics) -->
      <div class="col-6 col-md-4 col-lg-3">
        <div class="partner-card h-100 text-center p-4 rounded-4 shadow-sm bg-white">
          <div class="partner-logo-wrapper mb-4 d-flex align-items-center justify-content-center" style="height: 80px;">
            <img src="https://tse3.mm.bing.net/th/id/OIP.Ep83pV_MPNRbeIRF8l25qAHaG1?rs=1&pid=ImgDetMain&o=7&rm=3" 
                 alt="Proven Winners" 
                 class="img-fluid" 
                 style="max-height: 120px;">
          </div>
          <h5 class="card-title text-success fw-semibold mb-2">Superior Genetics</h5>
          <p class="card-text text-muted small mb-0">Award-winning plant varieties with guaranteed performance and vibrant colors.</p>
        </div>
      </div>

      <!-- Espoma (Organic fertilizers) -->
      <div class="col-6 col-md-4 col-lg-3">
        <div class="partner-card h-100 text-center p-4 rounded-4 shadow-sm bg-white">
          <div class="partner-logo-wrapper mb-4 d-flex align-items-center justify-content-center" style="height: 80px;">
            <img src="https://seekvectorlogo.com/wp-content/uploads/2020/07/espoma-organic-vector-logo.png" 
                 alt="Espoma" 
                 class="img-fluid" 
                 style="max-height: 180px;">
          </div>
          <h5 class="card-title text-success fw-semibold mb-2">Organic Care Solutions</h5>
          <p class="card-text text-muted small mb-0">Natural, organic fertilizers and soil amendments for healthier, happier plants.</p>
        </div>
      </div>

      <!-- Miracle-Gro (Garden supplies) -->
      <div class="col-6 col-md-4 col-lg-3">
        <div class="partner-card h-100 text-center p-4 rounded-4 shadow-sm bg-white">
          <div class="partner-logo-wrapper mb-4 d-flex align-items-center justify-content-center" style="height: 80px;">
            <img src="https://iconape.com/wp-content/files/py/197670/png/197670.png" 
                 alt="Miracle-Gro" 
                 class="img-fluid" 
                 style="max-height: 140px;">
          </div>
          <h5 class="card-title text-success fw-semibold mb-2">Complete Garden Care</h5>
          <p class="card-text text-muted small mb-0">Comprehensive plant nutrition and soil solutions for optimal growth and vitality.</p>
        </div>
      </div>
    </div>

    <!-- Partnership Benefits Section -->
    <div class="row justify-content-center mt-5">
      <div class="col-md-10 col-lg-8">
        <div class="partnership-benefits p-4 p-lg-5 rounded-4 bg-light-success border border-success-subtle">
          <h4 class="text-center text-success mb-4">Partnership Benefits</h4>
          <div class="row g-4">
            <div class="col-md-4 text-center">
              <div class="benefit-icon mb-3">
                <i class="fas fa-truck text-success fa-2x"></i>
              </div>
              <h6 class="fw-semibold">Direct Supply Chain</h6>
              <p class="small text-muted mb-0">Fresh plants delivered directly from nurseries</p>
            </div>
            <div class="col-md-4 text-center">
              <div class="benefit-icon mb-3">
                <i class="fas fa-award text-success fa-2x"></i>
              </div>
              <h6 class="fw-semibold">Quality Guaranteed</h6>
              <p class="small text-muted mb-0">All plants meet premium quality standards</p>
            </div>
            <div class="col-md-4 text-center">
              <div class="benefit-icon mb-3">
                <i class="fas fa-leaf text-success fa-2x"></i>
              </div>
              <h6 class="fw-semibold">Sustainable Sourcing</h6>
              <p class="small text-muted mb-0">Eco-friendly growing and delivery practices</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- Plant Shop Activities Section -->
<section class="shop-activities py-5 bg-light">
  <div class="container">
    <!-- Section Header -->
    <div class="section-header text-center mb-5">
      <h2 class="display-5 fw-bold text-success mb-3">Our Plant & Environmental Activities</h2>
      <div class="divider mx-auto mb-3">
        <div class="line"></div>
        <div class="circle"></div>
        <div class="line"></div>
      </div>
      <p class="lead text-muted mb-0">Join us in our mission to protect plants, promote biodiversity, and create a greener environment.</p>
    </div>

    <!-- Activity Cards -->
    <div class="row g-4">
      <!-- Activity 1: Plant Protection -->
      <div class="col-lg-6 mb-4">
        <div class="activity-card card h-100 border-0 shadow-sm overflow-hidden">
          <div class="row g-0 h-100">
            <div class="col-md-5">
              <img src="https://images.unsplash.com/photo-1416879595882-3373a0480b5b?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80" 
                   class="img-fluid h-100" 
                   alt="Plant Protection Activity"
                   style="object-fit: cover;">
            </div>
            <div class="col-md-7">
              <div class="card-body p-4 d-flex flex-column h-100">
                <div class="activity-icon mb-3">
                  <i class="fas fa-shield-alt text-success fa-2x"></i>
                </div>
                <h3 class="card-title text-success fw-bold">Plant Protection Program</h3>
                <p class="card-text text-muted flex-grow-1">
                  Our comprehensive plant health program includes organic pest control, disease prevention, and proper nutrition guidelines. We educate customers on sustainable plant care practices that protect both indoor and outdoor greenery.
                </p>
                <div class="activity-highlights mb-3">
                  <span class="badge bg-success-subtle text-success me-2 mb-2">Organic Pest Control</span>
                  <span class="badge bg-success-subtle text-success me-2 mb-2">Disease Management</span>
                  <span class="badge bg-success-subtle text-success mb-2">Plant Health Workshops</span>
                </div>
                <a href="#" class="btn btn-outline-success align-self-start">Join Our Program <i class="fas fa-arrow-right ms-2"></i></a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Activity 2: Environmental Conservation -->
      <div class="col-lg-6 mb-4">
        <div class="activity-card card h-100 border-0 shadow-sm overflow-hidden">
          <div class="row g-0 h-100">
            <div class="col-md-5">
              <img src="https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80" 
                   class="img-fluid h-100" 
                   alt="Environmental Conservation"
                   style="object-fit: cover;">
            </div>
            <div class="col-md-7">
              <div class="card-body p-4 d-flex flex-column h-100">
                <div class="activity-icon mb-3">
                  <i class="fas fa-globe-americas text-success fa-2x"></i>
                </div>
                <h3 class="card-title text-success fw-bold">Environmental Conservation</h3>
                <p class="card-text text-muted flex-grow-1">
                  We actively participate in reforestation projects, urban greening initiatives, and biodiversity conservation. Our shop uses eco-friendly packaging and supports local environmental organizations through monthly donations.
                </p>
                <div class="activity-highlights mb-3">
                  <span class="badge bg-success-subtle text-success me-2 mb-2">Reforestation Projects</span>
                  <span class="badge bg-success-subtle text-success me-2 mb-2">Urban Greening</span>
                  <span class="badge bg-success-subtle text-success mb-2">Eco-friendly Packaging</span>
                </div>
                <a href="#" class="btn btn-outline-success align-self-start">Support Our Mission <i class="fas fa-arrow-right ms-2"></i></a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Activity 3: Community Garden -->
      <div class="col-lg-6 mb-4">
        <div class="activity-card card h-100 border-0 shadow-sm overflow-hidden">
          <div class="row g-0 h-100">
            <div class="col-md-5">
              <img src="https://static.wixstatic.com/media/674b8d_0420e2d709684d829f9d1b97f521dfff~mv2.png/v1/fill/w_925,h_520,al_c,q_90,usm_0.66_1.00_0.01,enc_avif,quality_auto/674b8d_0420e2d709684d829f9d1b97f521dfff~mv2.png?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80" 
                   class="img-fluid h-100" 
                   alt="Community Garden Project"
                   style="object-fit: cover;">
            </div>
            <div class="col-md-7">
              <div class="card-body p-4 d-flex flex-column h-100">
                <div class="activity-icon mb-3">
                  <i class="fas fa-seedling text-success fa-2x"></i>
                </div>
                <h3 class="card-title text-success fw-bold">Community Garden Project</h3>
                <p class="card-text text-muted flex-grow-1">
                  We sponsor and maintain community gardens across the city, providing free seeds, plants, and gardening workshops. Join our volunteer program and help us create green spaces that benefit everyone.
                </p>
                <div class="activity-highlights mb-3">
                  <span class="badge bg-success-subtle text-success me-2 mb-2">Free Seeds Program</span>
                  <span class="badge bg-success-subtle text-success me-2 mb-2">Gardening Workshops</span>
                  <span class="badge bg-success-subtle text-success mb-2">Volunteer Opportunities</span>
                </div>
                <a href="#" class="btn btn-outline-success align-self-start">Volunteer Now <i class="fas fa-arrow-right ms-2"></i></a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Activity 4: Plant Adoption -->
      <div class="col-lg-6 mb-4">
        <div class="activity-card card h-100 border-0 shadow-sm overflow-hidden">
          <div class="row g-0 h-100">
            <div class="col-md-5">
              <img src="https://greenvision.earth/wp-content/uploads/2022/08/Untitled-design-94.png?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80" 
                   class="img-fluid h-100" 
                   alt="Plant Rescue & Adoption"
                   style="object-fit: cover;">
            </div>
            <div class="col-md-7">
              <div class="card-body p-4 d-flex flex-column h-100">
                <div class="activity-icon mb-3">
                  <i class="fas fa-heart text-success fa-2x"></i>
                </div>
                <h3 class="card-title text-success fw-bold">Plant Rescue & Adoption</h3>
                <p class="card-text text-muted flex-grow-1">
                  We rescue neglected plants and provide them with proper care until they're ready for adoption. Adopt a rescued plant and give it a second chance at life. Every adoption supports our rescue program.
                </p>
                <div class="activity-highlights mb-3">
                  <span class="badge bg-success-subtle text-success me-2 mb-2">Plant Rescue Services</span>
                  <span class="badge bg-success-subtle text-success me-2 mb-2">Adoption Events</span>
                  <span class="badge bg-success-subtle text-success mb-2">Rehabilitation Program</span>
                </div>
                <a href="#" class="btn btn-outline-success align-self-start">Adopt a Plant <i class="fas fa-arrow-right ms-2"></i></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Statistics Banner -->
    <div class="row mt-5">
      <div class="col-12">
        <div class="stats-banner p-4 p-lg-5 rounded-4 bg-success text-white text-center">
          <h3 class="mb-4">Our Impact So Far</h3>
          <div class="row g-4">
            <div class="col-md-3 col-6">
              <div class="stat-item">
                <h2 class="display-6 fw-bold mb-2">1,500+</h2>
                <p class="mb-0">Plants Protected</p>
              </div>
            </div>
            <div class="col-md-3 col-6">
              <div class="stat-item">
                <h2 class="display-6 fw-bold mb-2">200+</h2>
                <p class="mb-0">Trees Planted</p>
              </div>
            </div>
            <div class="col-md-3 col-6">
              <div class="stat-item">
                <h2 class="display-6 fw-bold mb-2">50+</h2>
                <p class="mb-0">Workshops Held</p>
              </div>
            </div>
            <div class="col-md-3 col-6">
              <div class="stat-item">
                <h2 class="display-6 fw-bold mb-2">300+</h2>
                <p class="mb-0">Plants Adopted</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

  <!-- Customer Feedback & Ratings Section -->
<div class="row my-5">
  
  <!-- Feedback Form -->
  <div class="col-md-6 mb-4 mb-md-0">
    <div class="card shadow-lg border-0">
      <div class="card-body">
        <h3 class="card-title text-success fw-bold mb-3">
          <i class="bi bi-chat-dots-fill"></i> Customer Feedback
        </h3>
        <hr class="border-success opacity-50">
        <form action="submit_feedback.php" method="POST">
          <div class="mb-3">
            <label for="name" class="form-label fw-semibold" >Name</label>
            <input type="text" class="form-control rounded-pill" id="name" name="name" value="<?php echo $fullname ?>" placeholder="Enter your name" required disabled>
          </div>
          <div class="mb-3"> 
            <label for="email" class="form-label fw-semibold">Email</label>
            <input type="email" class="form-control rounded-pill" id="email" name="email" value="<?php echo $email ?>" placeholder="Enter your email" required disabled>
          </div>
          <div class="mb-3">
            <label for="comments" class="form-label fw-semibold">Comments</label>
            <textarea class="form-control" id="comments" name="comments" rows="3" required></textarea>
          </div>
          <div class="mb-3">
            <label for="rating" class="form-label fw-semibold">Rating</label>
            <select class="form-select rounded-pill" id="rating" name="rating" required>
              <option value="" disabled selected>Select rating</option>
              <option value="5">★★★★★ - Excellent</option>
              <option value="4">★★★★☆ - Very Good</option>
              <option value="3">★★★☆☆ - Good</option>
              <option value="2">★★☆☆☆ - Fair</option>
              <option value="1">★☆☆☆☆ - Poor</option>
            </select>
          </div>
          <button type="submit" class="btn btn-success w-100 rounded-pill shadow-sm">
            <i class="bi bi-send-fill"></i> Submit Feedback
          </button>
        </form>
      </div>
    </div>
  </div>

  <!-- Feedback Aside -->
  <div class="col-md-6">
    <?php if (empty($feedbacks)): ?>
      <div class="alert alert-light border text-center py-4 rounded-3">
        <span class="fs-3 mb-2 d-block">💬</span>
        <p class="mb-0 text-muted">No feedback yet—be the first to share your experience!</p>
      </div>
    <?php else: ?>
      <div class="row g-3">
        <?php foreach ($feedbacks as $feedback): ?>
          <div class="col-12">
            <div class="card h-100 border-0 shadow-sm hover-lift">
              <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                  <h6 class="fw-bold mb-0 text-dark">
                    <?= htmlspecialchars($feedback['fullname']); ?>
                  </h6>
                  <div class="text-warning small" id ="rating">
                    ★★★★★
                    <span class="text-muted ms-1"><?= htmlspecialchars($feedback['rating']); ?></span>
                  </div>
                </div>
                <p class="text-muted small mb-0 line-clamp-3">
                  "<?= htmlspecialchars($feedback['comments']); ?>"
                </p>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>

</div>

 <?php include 'popUp.php';?> 
<!-- Bootstrap Modal for Product Image -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="productModalLabel"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <img id="modalProductImg" src="" alt="Product Large" class="img-fluid rounded shadow" style="max-height:350px;">
      </div>
    </div>
  </div>
</div>
<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const test = document.getElementById("name1");
  const rating = document.getElementById("rating");
  test.addEventListener("click", () => {
    test.value = "<?php echo htmlspecialchars($fullname); ?>";
    rating.textContent = "<?php echo htmlspecialchars($rating); ?>";
    if (rating.textContent >= 4) {
      rating.innerHTML = '★★★★★ <span class="text-muted ms-1">' + rating.textContent + '</span>';
    } else if (rating.textContent >= 3) {
      rating.innerHTML = '★★★★☆ <span class="text-muted ms-1">' + rating.textContent + '</span>';
    } else if (rating.textContent >= 2) {
      rating.innerHTML = '★★★☆☆ <span class="text-muted ms-1">' + rating.textContent + '</span>';
    } else if (rating.textContent >= 1) {
      rating.innerHTML = '★★☆☆☆ <span class="text-muted ms-1">' + rating.textContent + '</span>';
    } else {
      rating.innerHTML = '★☆☆☆☆ <span class="text-muted ms-1">' + rating.textContent + '</span>';
    }
  });

  function toggleAIChat() {
  const chatWindow = document.getElementById('ai-chat-window');
  
  if (chatWindow.style.display === "flex") {
    chatWindow.style.display = "none";
  } else {
    chatWindow.style.display = "flex";
  }
}

document.addEventListener('click', function(e){
  const a = e.target.closest('a');
  if(a){
    const href = (a.getAttribute('href') || '').toLowerCase();
    if(href.includes('login.php') || href.includes('signup.php')) return;
  }
  const protectedEl = e.target.closest('.protected-action, button, a, .card, .product-card, [role="button"]');
  if(protectedEl){
    e.preventDefault();
    alert('Please login first!');
    window.location.href = 'login.php';
  }
});
</script>
</body>
</html>



<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-success mb-1 shadow-sm">
  <div class="container">
    <a class="navbar-brand d-flex align-items-left" href="menubar.php">
      <img src="../User_Page/icon/plant_cactus_flower_nature_flower_pot_garden_planter_icon_141184.png" alt="KP Plant_Shop Logo" width="40" height="40" class="img-colorful me-2" />
      <!--<span class="fw-bold">KP Plant_Shop</span>-->
      <div class = "wrapper  ">
        <div class="images" id="imgbox">
          <span class="gradient-text cool-animate">KP Plant_Shop</span>
        </div>
        <style>
          .gradient-text.cool-animate {
            background: linear-gradient(90deg, #198754, #0aef52ff, #64f327ff, #9df74eff);
            background-size: 200% 200%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: gradientMove 3s linear infinite alternate;
            font-size: 2rem;
            font-weight: bold;
            letter-spacing: 2px;
            display: inline-block;
          }
          @keyframes gradientMove {
            0% { background-position: 0% 50%; }
            100% { background-position: 100% 50%; }
          }
        </style>
      </div>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link active" href="index.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link  active" href="products.php">Products</a></li>
        <li class="nav-item"><a class="nav-link active" href="categories.php">Categories</a></li>
        <li class="nav-item"><a class="nav-link  active" href="orders.php">Orders</a></li>
        <li class="nav-item"><a class="nav-link active" href="users.php">Users</a></li>
        <li class="nav-item"><a class="nav-link active" href="setting.php">Settings</a></li>
        <li class="nav-item"><a class="nav-link active" href="/PLANT_PROJECT/plant_admin/logout.php">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

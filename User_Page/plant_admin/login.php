<?php
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'config.php';

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Trim inputs
    $username = trim($_POST['user'] ?? '');
    $password = $_POST['pass'] ?? '';

    // Validate input
    if ($username === '' || $password === '') {
        $error = "Please enter username and password.";
    } else {

        $stmt = $pdo->prepare(
            "SELECT id, password 
             FROM admins 
             WHERE username = :username 
             LIMIT 1"
        );

        $stmt->execute([
            ':username' => $username
        ]);

        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && password_verify($password, $admin['password'])) {

            // Prevent session fixation
            session_regenerate_id(true);

            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_login_time'] = time();

            header('Location: plant_admin/index.php');
            exit;

        } else {
            $error = "Invalid username or password.";
        }
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin Login</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
  <style>
    :root {
      --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
      --glass-bg: rgba(255, 255, 255, 0.1);
      --glass-border: rgba(255, 255, 255, 0.2);
      --text-primary: #333;
      --text-secondary: #666;
    }
    
    body {
      background: linear-gradient(135deg, #0f0f23 0%, #1a1a2e 50%, #16213e 100%);
      min-height: 100vh;
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      position: relative;
    }
    
    body::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><defs><radialGradient id="a" cx="50%" cy="50%" r="50%"><stop offset="0%" stop-color="%23ffffff" stop-opacity="0.1"/><stop offset="100%" stop-color="%23ffffff" stop-opacity="0"/></radialGradient></defs><circle cx="200" cy="200" r="100" fill="url(%23a)"/><circle cx="800" cy="300" r="150" fill="url(%23a)"/><circle cx="400" cy="700" r="80" fill="url(%23a)"/></svg>') repeat;
      animation: float 20s ease-in-out infinite;
      z-index: 1;
    }
    
    @keyframes float {
      0%, 100% { transform: translateY(0px) rotate(0deg); }
      50% { transform: translateY(-20px) rotate(180deg); }
    }
    
    .login-container {
      z-index: 2;
      position: relative;
    }
    
    .card {
      backdrop-filter: blur(20px);
      background: var(--glass-bg);
      border: 1px solid var(--glass-border);
      border-radius: 24px;
      box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
      overflow: hidden;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      max-width: 420px;
      width: 100%;
    }
    
    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 35px 60px rgba(0, 0, 0, 0.3);
    }
    
    .card-header {
      background: var(--primary-gradient);
      color: white;
      border: none;
      text-align: center;
      padding: 2rem;
      font-weight: 700;
      font-size: 1.5rem;
      letter-spacing: -0.025em;
      position: relative;
      overflow: hidden;
    }
    
    .card-header::after {
      content: '';
      position: absolute;
      top: -50%;
      right: -50%;
      width: 200%;
      height: 200%;
      background: var(--secondary-gradient);
      opacity: 0.1;
      transform: rotate(45deg);
      transition: opacity 0.3s ease;
    }
    
    .card-header:hover::after {
      opacity: 0.2;
    }
    
    .logo-aside {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 1rem;
      margin-bottom: 1rem;
    }
    
    .logo-img {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      padding: 0.5rem;
      background: linear-gradient(135deg, rgba(255,255,255,0.2), rgba(255,255,255,0.1));
      backdrop-filter: blur(10px);
      border: 2px solid rgba(255,255,255,0.3);
      transition: all 0.3s ease;
      animation: logoFloat 3s ease-in-out infinite;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
    }
    
    .logo-img:hover {
      transform: scale(1.1) rotate(5deg);
      box-shadow: 0 12px 40px rgba(25, 135, 84, 0.4); /* Green glow for plant theme */
      background: linear-gradient(135deg, rgba(25,135,84,0.3), rgba(32,201,151,0.2));
    }
    
    @keyframes logoFloat {
      0%, 100% { transform: translateY(0px) rotate(0deg); }
      50% { transform: translateY(-8px) rotate(2deg); }
    }
    
    .logo-text {
      font-size: 1.3rem;
      font-weight: 800;
      background: linear-gradient(90deg, #ffffff, #f0f9ff);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      letter-spacing: -0.02em;
      position: relative;
    }
    
    .logo-text::after {
      content: '';
      position: absolute;
      bottom: -0.5rem;
      left: 0;
      right: 0;
      height: 2px;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.5), transparent);
      border-radius: 1px;
    }
    
    .card-body {
      padding: 2.5rem;
      background: transparent;
    }
    
    .form-control {
      background: rgba(255, 255, 255, 0.9);
      border: 1px solid rgba(255, 255, 255, 0.3);
      border-radius: 12px;
      padding: 1rem 1.25rem;
      font-size: 1rem;
      transition: all 0.3s ease;
      backdrop-filter: blur(10px);
    }
    
    .form-control:focus {
      background: white;
      border-color: var(--primary-gradient);
      box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
      transform: translateY(-2px);
    }
    
    .form-control::placeholder {
      color: var(--text-secondary);
      opacity: 0.7;
    }
    
    .input-group {
      position: relative;
      margin-bottom: 1.5rem;
    }
    
    .input-group i {
      position: absolute;
      left: 1rem;
      top: 50%;
      transform: translateY(-50%);
      color: var(--text-secondary);
      z-index: 3;
      transition: color 0.3s ease;
    }
    
    .form-control:focus + i {
      color: #0be981ff;
    }
    
    .btn {
      background: var(--primary-gradient);
      border: none;
      border-radius: 12px;
      padding: 1rem;
      font-weight: 600;
      font-size: 1.1rem;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }
    
    .btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
    }
    
    .btn::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
      transition: left 0.5s ease;
    }
    
    .btn:hover::before {
      left: 100%;
    }
    
    .alert {
      border-radius: 12px;
      border: none;
      background: rgba(220, 53, 69, 0.1);
      color: #dc3545;
      backdrop-filter: blur(10px);
      border-left: 4px solid #dc3545;
    }
    
    @media (max-width: 576px) {
      .card-body {
        padding: 1.5rem;
      }
      
      .card-header {
        font-size: 1.25rem;
        padding: 1.5rem;
      }
      
      .logo-img {
        width: 50px;
        height: 50px;
      }
      
      .logo-text {
        font-size: 1.1rem;
      }
    }
  </style>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
<div class="container login-container">
  <div class="card mx-auto">
    <div class="card-header mb-3">
      <div class="logo-aside">
        <img src="../User_Page/icon/plant_cactus_flower_nature_flower_pot_garden_planter_icon_141184.png" alt="KP Plant Shop Logo" class="logo-img">
        <span class="logo-text">Plant Admin</span>
      </div>
    </div>
    <div class="card-body">
      <?php if (isset($error)) echo "<div class='alert alert-danger mb-3'><i class='fas fa-exclamation-triangle me-2'></i>$error</div>"; ?>
      <form method="POST">
        <div class="input-group mb-3">
          <span class="input-group-text"><i class="fas fa-user"></i></span>
          <input class="form-control" name="user" placeholder="Username" required>
        </div>
        <div class="input-group mb-4">
          <span class="input-group-text"><i class="fas fa-lock"></i></span>
          <input class="form-control" name="pass" type="password" placeholder="Password" required>
        </div>
        <button class="btn btn-primary w-100" type="submit">
          <i class="fas fa-sign-in-alt me-2"></i>Login
        </button>
      </form>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
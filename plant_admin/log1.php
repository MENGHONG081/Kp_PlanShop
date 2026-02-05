<?php
require 'config.php';
if(isset($_SESSION['admin_id'])) header('Location:/PLANT_PROJECT/plant_admin/index.php');

if($_SERVER['REQUEST_METHOD']=='POST'){
    $stmt = $pdo->prepare("SELECT id,password FROM admins WHERE username=?");
    $stmt->execute([$_POST['user']]);
    $admin = $stmt->fetch();
    // ...existing code...

  // ...existing code...

    if($admin && password_verify($_POST['pass'], $admin['password'])){
      $_SESSION['admin_id'] = $admin['id'];
      header('Location: /PLANT_PROJECT/plant_admin/index.php');
      exit();
    }else{
      $error = "Invalid credentials";
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
      --primary-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
      --secondary-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
      --glass-bg: rgba(255, 255, 255, 0.15);
      --glass-border: rgba(255, 255, 255, 0.25);
      --text-primary: #2d3436;
      --text-secondary: #636e72;
      --leaf-green: #10ac84;
      --fresh-blue: #48dbfb;
    }
    
    body {
      background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 50%, #e8f5e8 100%);
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
      background-image: 
        radial-gradient(circle at 20% 80%, rgba(76, 175, 80, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(72, 219, 251, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 40% 40%, rgba(129, 236, 236, 0.05) 0%, transparent 50%);
      animation: gentleBreeze 25s ease-in-out infinite;
      z-index: 1;
    }
    
    @keyframes gentleBreeze {
      0%, 100% { transform: translateX(0px) scale(1); opacity: 0.8; }
      50% { transform: translateX(10px) scale(1.05); opacity: 1; }
    }
    
    .login-container {
      z-index: 2;
      position: relative;
    }
    
    .card {
      backdrop-filter: blur(15px);
      background: var(--glass-bg);
      border: 1px solid var(--glass-border);
      border-radius: 20px;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
      overflow: hidden;
      transition: transform 0.4s ease, box-shadow 0.4s ease;
      max-width: 420px;
      width: 100%;
      position: relative;
    }
    
    .card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: var(--secondary-gradient);
    }
    
    .card:hover {
      transform: translateY(-8px);
      box-shadow: 0 30px 60px rgba(0, 0, 0, 0.15);
    }
    
    .card-header {
      background: var(--secondary-gradient);
      color: white;
      border: none;
      text-align: center;
      padding: 2rem;
      font-weight: 700;
      font-size: 1.6rem;
      letter-spacing: -0.02em;
      position: relative;
      overflow: hidden;
    }
    
    .card-header i {
      background: rgba(255, 255, 255, 0.2);
      border-radius: 50%;
      width: 50px;
      height: 50px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      margin-right: 0.75rem;
      font-size: 1.2rem;
    }
    
    .card-header::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 100%;
      height: 2px;
      background: rgba(255, 255, 255, 0.3);
    }
    
    .card-body {
      padding: 2.5rem;
      background: transparent;
    }
    
    .form-control {
      background: rgba(255, 255, 255, 0.85);
      border: 2px solid rgba(67, 233, 123, 0.2);
      border-radius: 16px;
      padding: 1.1rem 1.3rem;
      font-size: 1rem;
      transition: all 0.3s ease;
      backdrop-filter: blur(10px);
      color: var(--text-primary);
    }
    
    .form-control:focus {
      background: white;
      border-color: var(--secondary-gradient);
      box-shadow: 0 0 0 0.25rem rgba(67, 233, 123, 0.2);
      transform: translateY(-1px);
      outline: none;
    }
    
    .form-control::placeholder {
      color: var(--text-secondary);
      opacity: 0.6;
    }
    
    .input-group {
      position: relative;
      margin-bottom: 1.75rem;
    }
    
    .input-group i {
      position: absolute;
      left: 1.2rem;
      top: 50%;
      transform: translateY(-50%);
      color: var(--leaf-green);
      z-index: 3;
      transition: color 0.3s ease, transform 0.3s ease;
      font-size: 1.1rem;
    }
    
    .form-control:focus ~ i {
      color: var(--fresh-blue);
      transform: translateY(-50%) scale(1.1);
    }
    
    .btn {
      background: var(--secondary-gradient);
      border: none;
      border-radius: 16px;
      padding: 1.1rem;
      font-weight: 600;
      font-size: 1.1rem;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
      color: white;
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }
    
    .btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 12px 30px rgba(67, 233, 123, 0.3);
    }
    
    .btn::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
      transition: left 0.6s ease;
    }
    
    .btn:hover::before {
      left: 100%;
    }
    
    .alert {
      border-radius: 16px;
      border: none;
      background: rgba(244, 67, 54, 0.1);
      color: #c62828;
      backdrop-filter: blur(10px);
      border-left: 4px solid #e53935;
      padding: 1rem 1.25rem;
    }
    
    .alert i {
      margin-right: 0.5rem;
    }
    
    @media (max-width: 576px) {
      .card-body {
        padding: 2rem 1.75rem;
      }
      
      .card-header {
        font-size: 1.4rem;
        padding: 1.75rem;
      }
    }
  </style>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
<div class="container login-container">
  <div class="card mx-auto">
    <div class="card-header d-flex align-items-center justify-content-center">
      <i class="fas fa-seedling"></i>
      Plant Admin Login
    </div>
    <div class="card-body">
      <?php if(isset($error)) echo "<div class='alert alert-danger mb-3 d-flex align-items-center'><i class='fas fa-exclamation-triangle'></i>$error</div>"; ?>
      <form method="POST">
        <div class="input-group">
          <input class="form-control ps-4" name="user" placeholder="Username" required>
          <i class="fas fa-user"></i>
        </div>
        <div class="input-group">
          <input class="form-control ps-4" name="pass" type="password" placeholder="Password" required>
          <i class="fas fa-lock"></i>
        </div>
        <button class="btn w-100" type="submit">
          <i class="fas fa-arrow-right me-2"></i>Enter Garden
        </button>
      </form>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
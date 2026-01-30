
<?php
session_start(); //must be at the top of every page that uses session data.
include 'config.php'; // contains $pdo connection
$message = "";
$toastClass = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['fullname'] ?? '';
    $email    = $_POST['email'] ?? '';
    $phone    = $_POST['phone'] ?? '';
    $password = $_POST['password'] ?? '';

    // ✅ Basic validation
    if ($username && $email && $phone && $password) {

        // Check if email already exists
        $checkEmailStmt = $pdo->prepare("SELECT email FROM users WHERE email = ?");
        $checkEmailStmt->execute([$email]);

        if ($checkEmailStmt->rowCount() > 0) {
            echo "<div class='alert alert-warning'>Email ID already exists</div>";
        } else {
            try {
                // ✅ Hash the password before saving
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Insert new user
                $stmt = $pdo->prepare("INSERT INTO users (fullname, email, phone, password) VALUES (?, ?, ?, ?)");
                $stmt->execute([$username, $email, $phone, $hashedPassword]);

                // ✅ Set session after successful insert
                $_SESSION['user'] = $username;
                $_SESSION['user_id'] = $pdo->lastInsertId();

                // Redirect to dashboard
                header("Location: index1.php");
                exit;

            } catch (PDOException $e) {
                echo "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
            }
        }
    } else {
        echo "<div class='alert alert-danger'>Please fill in all fields.</div>";
    }
}
?>
<!----------  FRONTEND : MODERN & COOL  ---------->
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Sign Up</title>
<script src="Home.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
    :root{
        --bg:#111;
        --card:#1a1a1a;
        --accent:#00e5ff;
        --accent-dark:#00a2c7;
        --text:#f1f1f1;
        --error:#ff3b30;
        --success:#34c759;
        --radius:16px;
        --transition:.35s cubic-bezier(.4,0,.2,1);
    }
    *{box-sizing:border-box;margin:0;padding:0;font-family:'Segoe UI',sans-serif}
    body{
    background:url('image/12.jpg') no-repeat center center fixed;
    background-attachment: fixed; /* fix background image */
    background-position: center center;
    background-size: cover;   /* scale image to fill screen */
    color: var(--text);
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    padding: 20px;
    position  : relative;

    }
    .card{
       background-color: #f5f5f594;   /* light gray */
       color: #0afe47ff; 
        border-radius:var(--radius);
        box-shadow:0 20px 50px rgba(18, 231, 36, 0.46);
        width:100%;width:420px;
        padding:40px 35px;
        animation:fadeIn .8s var(--transition) forwards;
        opacity:0;
    }
    @keyframes fadeIn{to{opacity:1;transform:translateY(0)}from{opacity:0;transform:translateY(25px)}}
    h2{margin-bottom:30px;text-align:center;font-size:28px;letter-spacing:1px}
    .field{margin-bottom:22px;position:relative}
    label{
        position:absolute;
        left:14px;top:18px;
        font-size:16px;color:#888;
        pointer-events:none;
        transition:var(--transition);
    }
    input{
        width:100%;
        background:#222;
        border:2px solid transparent;
        border-radius:10px;
        padding:18px 14px 10px;
        font-size:16px;
        color:var(--text);
        transition:var(--transition);
    }
    input:focus{
        border-color:var(--accent);
        outline:none;
    }
    input:focus+label,input:not(:placeholder-shown)+label{
        top:4px;left:12px;font-size:12px;color:var(--accent);
    }
    button{
        width:100%;
        border:none;
        background:linear-gradient(45deg,var(--accent),var(--accent-dark));
        color:#000;
        font-size:17px;font-weight:600;
        padding:14px 0;border-radius:10px;
        cursor:pointer;
        transition:var(--transition);
        margin-top:10px;
    }
    button:hover{transform:translateY(-2px);box-shadow:0 8px 25px rgba(0,229,255,.35)}
    .msg{
        margin-bottom:20px;text-align:center;
        font-size:14px;padding:12px;border-radius:8px;
    }
    .error{background:rgba(255,59,48,.15);color:var(--error)}
    .success{background:rgba(52,199,89,.15);color:var(--success)}
</style>
</head>
<body>
<form class="card" method="post">
    <h2>Create Account</h2>
    <div class="field">
        <input type="text"  name="fullname" id="fullname" placeholder=" " required>
        <label for="fullname">Full Name</label>
    </div>

    <div class="field">
        <input type="email" name="email" id="email" placeholder=" " required>
        <label for="email">Email</label>
    </div>

    <div class="field">
        <input type="tel"   name="phone" id="phone" placeholder=" " required>
        <label for="phone">Phone</label>
        
    </div>

    <div class="field">
        <input type="password" name="password" id="password" placeholder=" " required minlength="6">
        <label for="password">Password</label>
    </div>
    <button type="submit" id="signupBtn">Sign Up</button>
    <div class="field text-center mt-4">
  <p style="margin-top:15px; font-size:14px; color:#888;">
    Back to account?
    <a href="login.php" style="color:var(--accent); text-decoration:none;">
      Log In
    </a>
  </p>
</div>
</form>
        </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
        let toastElList = [].slice.call(document.querySelectorAll('.toast'))
        let toastList = toastElList.map(function (toastEl) {
            return new bootstrap.Toast(toastEl, { delay: 3000 });
        });
        toastList.forEach(toast => toast.show());
    </script>
</body>
</html>
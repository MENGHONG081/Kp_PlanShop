<?php
include 'config.php';

$errors = [];
$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
        logSecurityEvent('csrf_token_mismatch', ['action' => 'signup_attempt']);
        $errors[] = 'Security validation failed. Please try again.';
    } else {
        // Sanitize inputs
        $fullname = sanitizeInput($_POST['fullname'] ?? '');
        $email = sanitizeInput($_POST['email'] ?? '');
        $phone = sanitizeInput($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        // Validate inputs
        if (empty($fullname)) {
            $errors[] = 'Full name is required.';
        } elseif (strlen($fullname) < 2 || strlen($fullname) > 100) {
            $errors[] = 'Full name must be between 2 and 100 characters.';
        }
        
        if (empty($email)) {
            $errors[] = 'Email is required.';
        } elseif (!validateEmail($email)) {
            $errors[] = 'Please enter a valid email address.';
        }
        
        if (empty($phone)) {
            $errors[] = 'Phone number is required.';
        } elseif (!preg_match('/^[0-9\-\+\(\)\s]+$/', $phone) || strlen($phone) < 7) {
            $errors[] = 'Please enter a valid phone number.';
        }
        
        if (empty($password)) {
            $errors[] = 'Password is required.';
        } else {
            $passwordErrors = validatePasswordStrength($password);
            if (!empty($passwordErrors)) {
                $errors = array_merge($errors, $passwordErrors);
            }
        }
        
        if ($password !== $confirmPassword) {
            $errors[] = 'Passwords do not match.';
        }
        
        // Check if email already exists
        if (empty($errors)) {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $errors[] = 'Email address is already registered.';
            }
        }
        
        // If no errors, create account
        if (empty($errors)) {
            try {
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
                
                $stmt = $pdo->prepare("INSERT INTO users (fullname, email, phone, password, created_at) VALUES (?, ?, ?, ?, NOW())");
                $stmt->execute([$fullname, $email, $phone, $hashedPassword]);
                
                $userId = $pdo->lastInsertId();
                
                // Set session
                session_regenerate_id(true);
                $_SESSION['user'] = $fullname;
                $_SESSION['user_id'] = $userId;
                $_SESSION['email'] = $email;
                $_SESSION['just_logged_in'] = true;
                $_SESSION['login_time'] = time();
                
                logSecurityEvent('successful_signup', ['user_id' => $userId]);
                $success = true;
                
                // Redirect after 2 seconds
                header("refresh:2;url=index1.php");
            } catch (PDOException $e) {
                logSecurityEvent('signup_database_error', ['error' => 'Database error during signup']);
                $errors[] = 'An error occurred during registration. Please try again later.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>KP Plant_Shop - Sign Up</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
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
                },
            },
        }
    </script>
</head>
<body class="font-display bg-background-light dark:bg-background-dark">
    <div class="relative flex min-h-screen w-full flex-col group/design-root overflow-x-hidden">
        <div class="layout-container flex h-full grow flex-col">
            <div class="flex flex-1 justify-center">
                <div class="layout-content-container flex flex-col w-full flex-1">
                    <div class="grid grid-cols-1 md:grid-cols-2 min-h-screen">
                        <!-- Left Column: Image (hidden on mobile) -->
                        <div class="hidden md:flex flex-col bg-center bg-no-repeat bg-cover" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDn57njUOiQAnKfMoN6I8X3dVDcRLzDqKKcedlofopanxfiAqKQpWazjHy3zpNmUc-7xm-Pu0PvNPVv3ViWXEvBP5yb_3h_QBFNE5TmtTAAp1Ug3FuEaN8Tgp7Qx0o5JLlGUSJKJxaCtu2Z7nfOIrCb_cN9KPHiW-u1tXHrW4l5Q0UVdqWFGrXTsRxjXiZIvev80nhIaA3MiyggNSzG6JXdp_bk0XcSVv9Y2qmKgR_jZ8o_hSw1HHfdPepC8PaPofpvUhJRoiW8YW8");'></div>
                        
                        <!-- Right Column: Form -->
                        <div class="flex w-full items-center justify-center p-6 sm:p-8 lg:p-12">
                            <div class="flex flex-col w-full max-w-md gap-6">
                                <!-- Logo -->
                                <div class="flex items-center gap-2 self-start mb-4">
                                    <img src="icon/plant_cactus_flower_nature_flower_pot_garden_planter_icon_141184.png" alt="KP Plant_Shop Logo" width="40" height="40">
                                    <p class="text-2xl font-bold text-text-light dark:text-text-dark">KP Plant_Shop</p>
                                </div>
                                
                                <!-- Success Message -->
                                <?php if ($success): ?>
                                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg" role="alert">
                                    <strong>Success!</strong> Your account has been created. Redirecting...
                                </div>
                                <?php endif; ?>
                                
                                <!-- Error Messages -->
                                <?php if (!empty($errors)): ?>
                                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg" role="alert">
                                    <strong>Errors:</strong>
                                    <ul class="list-disc list-inside mt-2">
                                        <?php foreach ($errors as $error): ?>
                                        <li><?php echo escapeHTML($error); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                                <?php endif; ?>
                                
                                <form method="POST" action="Sige_Up_secure.php" class="w-full space-y-4">
                                    <input type="hidden" name="csrf_token" value="<?php echo escapeHTML(generateCSRFToken()); ?>">
                                    
                                    <h1 class="text-text-light dark:text-text-dark tracking-tight text-2xl sm:text-3xl font-bold leading-tight">Create Account</h1>
                                    
                                    <!-- Full Name -->
                                    <div class="flex flex-col gap-2">
                                        <label class="text-text-light dark:text-text-dark text-sm font-medium">Full Name</label>
                                        <input type="text" name="fullname" placeholder="Enter your full name" value="<?php echo isset($_POST['fullname']) ? escapeHTML($_POST['fullname']) : ''; ?>" required class="w-full px-4 py-3 rounded-lg border border-field-border-light dark:border-field-border-dark bg-field-bg-light dark:bg-field-bg-dark text-text-light dark:text-text-dark focus:outline-none focus:ring-2 focus:ring-primary/50"/>
                                    </div>
                                    
                                    <!-- Email -->
                                    <div class="flex flex-col gap-2">
                                        <label class="text-text-light dark:text-text-dark text-sm font-medium">Email Address</label>
                                        <input type="email" name="email" placeholder="Enter your email" value="<?php echo isset($_POST['email']) ? escapeHTML($_POST['email']) : ''; ?>" required class="w-full px-4 py-3 rounded-lg border border-field-border-light dark:border-field-border-dark bg-field-bg-light dark:bg-field-bg-dark text-text-light dark:text-text-dark focus:outline-none focus:ring-2 focus:ring-primary/50"/>
                                    </div>
                                    
                                    <!-- Phone -->
                                    <div class="flex flex-col gap-2">
                                        <label class="text-text-light dark:text-text-dark text-sm font-medium">Phone Number</label>
                                        <input type="tel" name="phone" placeholder="Enter your phone number" value="<?php echo isset($_POST['phone']) ? escapeHTML($_POST['phone']) : ''; ?>" required class="w-full px-4 py-3 rounded-lg border border-field-border-light dark:border-field-border-dark bg-field-bg-light dark:bg-field-bg-dark text-text-light dark:text-text-dark focus:outline-none focus:ring-2 focus:ring-primary/50"/>
                                    </div>
                                    
                                    <!-- Password -->
                                    <div class="flex flex-col gap-2">
                                        <label class="text-text-light dark:text-text-dark text-sm font-medium">Password</label>
                                        <input type="password" name="password" placeholder="Create a strong password" required class="w-full px-4 py-3 rounded-lg border border-field-border-light dark:border-field-border-dark bg-field-bg-light dark:bg-field-bg-dark text-text-light dark:text-text-dark focus:outline-none focus:ring-2 focus:ring-primary/50"/>
                                        <p class="text-xs text-text-light/70 dark:text-text-dark/70">
                                            Password must contain: 8+ characters, uppercase, lowercase, number, and special character
                                        </p>
                                    </div>
                                    
                                    <!-- Confirm Password -->
                                    <div class="flex flex-col gap-2">
                                        <label class="text-text-light dark:text-text-dark text-sm font-medium">Confirm Password</label>
                                        <input type="password" name="confirm_password" placeholder="Confirm your password" required class="w-full px-4 py-3 rounded-lg border border-field-border-light dark:border-field-border-dark bg-field-bg-light dark:bg-field-bg-dark text-text-light dark:text-text-dark focus:outline-none focus:ring-2 focus:ring-primary/50"/>
                                    </div>
                                    
                                    <!-- Sign Up Button -->
                                    <button type="submit" class="w-full h-12 rounded-lg bg-primary text-white font-bold hover:bg-opacity-90 transition-colors duration-200 mt-6">
                                        Create Account
                                    </button>
                                    
                                    <!-- Login Link -->
                                    <p class="text-text-light dark:text-text-dark text-sm font-normal text-center pt-4">
                                        Already have an account? <a class="font-bold text-primary dark:text-green-400 hover:text-accent dark:hover:text-accent underline" href="login.php">Login here</a>
                                    </p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

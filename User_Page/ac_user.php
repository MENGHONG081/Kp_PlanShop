<?php
session_start();
require 'config.php'; // assumes $pdo is ready + PDO::ERRMODE_EXCEPTION

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$successMessage = '';
$errorMessage = '';
$userProfile = null;

// ────────────────────────────────────────────────
//  HANDLE PROFILE PICTURE UPLOAD (AJAX)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_image'])) {
    header('Content-Type: application/json');

    $file = $_FILES['profile_image'];
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $maxSize = 5 * 1024 * 1024; // 5MB

    if ($file['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'message' => 'Upload error: ' . $file['error']]);
        exit;
    }

    if ($file['size'] > $maxSize) {
        echo json_encode(['success' => false, 'message' => 'File too large (max 5MB)']);
        exit;
    }

    if (!in_array($file['type'], $allowedTypes)) {
        echo json_encode(['success' => false, 'message' => 'Only JPG, PNG, GIF, WebP allowed']);
        exit;
    }

    $uploadDir = 'uploads/profile/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $newFileName = 'user_' . $userId . '_' . time() . '.' . $extension;
    $targetPath = $uploadDir . $newFileName;

    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        // Save path (store relative path or full URL depending on your setup)
        $dbPath = $targetPath; // or "/{$targetPath}" or "https://yourdomain.com/{$targetPath}"

        $stmt = $pdo->prepare("UPDATE users SET imgUser = ? WHERE id = ?");
        $stmt->execute([$dbPath, $userId]);

        echo json_encode([
            'success' => true,
            'message' => 'Profile picture updated',
            'path' => $dbPath
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to save file']);
    }
    exit;
}

// ────────────────────────────────────────────────
//  FETCH CURRENT PROFILE
try {
    $stmt = $pdo->prepare("SELECT id, fullname, email, phone, imgUser FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $userProfile = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$userProfile) {
        session_destroy();
        header("Location: login.php");
        exit();
    }
} catch (PDOException $e) {
    $errorMessage = 'Cannot load profile.';
    error_log("Profile load error: " . $e->getMessage());
}

// ────────────────────────────────────────────────
//  HANDLE TEXT PROFILE UPDATE
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_profile'])) {
    $fullname = trim($_POST['fullname'] ?? '');
    $email    = trim($_POST['email']    ?? '');
    $phone    = trim($_POST['phone']    ?? '');

    // Basic validation
    if (empty($fullname) || strlen($fullname) < 3 || strlen($fullname) > 50) {
        $errorMessage = 'Full name must be 3–50 characters.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 100) {
        $errorMessage = 'Invalid email.';
    } elseif (strlen($phone) < 9 || strlen($phone) > 20) {
        $errorMessage = 'Phone number looks invalid (9–20 chars).';
    } else {
        try {
            // Check for duplicates (exclude self)
            $check = $pdo->prepare("SELECT 1 FROM users WHERE (email = ? OR fullname = ?) AND id != ?");
            $check->execute([$email, $fullname, $userId]);
            if ($check->fetch()) {
                $errorMessage = 'Email or username already taken.';
            } else {
                $stmt = $pdo->prepare("UPDATE users SET fullname = ?, email = ?, phone = ? WHERE id = ?");
                $stmt->execute([$fullname, $email, $phone, $userId]);

                $_SESSION['user'] = $fullname; // update session if you use it
                $successMessage = 'Profile updated successfully!';

                // Refresh data
                $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
                $stmt->execute([$userId]);
                $userProfile = $stmt->fetch(PDO::FETCH_ASSOC);
            }
        } catch (PDOException $e) {
            $errorMessage = 'Update failed.';
            error_log("Profile update error: " . $e->getMessage());
        }
    }
}

// Password change logic remains the same (omitted here for brevity)

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Plant Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%); min-height: 100vh; }
        .profile-container { max-width: 800px; margin: 2rem auto; }
        .card { border: none; border-radius: 1rem; box-shadow: 0 10px 30px rgba(0,0,0,0.12); overflow: hidden; }
        .card-header { background: linear-gradient(135deg, #198754, #20c997); color: white; }
        .profile-avatar-container { position: relative; width: 140px; height: 140px; margin: 0 auto 1.5rem; }
        .profile-avatar { 
            width: 100%; height: 100%; border-radius: 50%; object-fit: cover; border: 5px solid white; box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            background: #eee center/cover;
        }
        .avatar-overlay {
            position: absolute; bottom: 8px; right: 8px; width: 38px; height: 38px;
            background: #198754; color: white; border-radius: 50%; display: flex;
            align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 2px 8px rgba(0,0,0,0.3);
            transition: all 0.2s;
        }
        .avatar-overlay:hover { background: #157347; transform: scale(1.1); }
        .password-strength { height: 4px; border-radius: 2px; margin-top: 6px; }
        .strength-weak    { background: #dc3545; width: 33%; }
        .strength-medium  { background: #ffc107; width: 66%; }
        .strength-strong  { background: #198754; width: 100%; }

        
        .strength-weak   { background: #dc3545; width: 35%; }
        .strength-medium { background: #fd7e14; width: 70%; }
        .strength-strong { background: #198754; width: 100%; }

        .password-strength {
            height: 6px;
            border-radius: 3px;
            transition: all 0.3s;
        }
    </style>
</head>
<body>

<?php include 'nav.php'; ?>

<div class="container profile-container">

    <?php if ($successMessage): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-2"></i> <?= htmlspecialchars($successMessage) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <?php if ($errorMessage): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-triangle me-2"></i> <?= htmlspecialchars($errorMessage) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header text-center">
            <h4><i class="fas fa-user me-2"></i>My Profile</h4>
        </div>
        <div class="card-body p-4">

            <div class="text-center">
                <div class="profile-avatar-container">
                    <?php
                    $avatarSrc = !empty($userProfile['imgUser']) && file_exists($userProfile['imgUser'])
                        ? htmlspecialchars($userProfile['imgUser'])
                        : 'https://via.placeholder.com/140?text=' . urlencode(substr($userProfile['fullname'] ?? '?', 0, 1));
                    ?>
                    <img src="<?= $avatarSrc ?>" alt="Profile" class="profile-avatar" id="preview-avatar">
                    <label for="user-file" class="avatar-overlay" title="Change profile picture">
                        <i class="fas fa-camera"></i>
                    </label>
                    <input type="file" id="user-file" accept="image/jpeg,image/png,image/gif,image/webp" style="display:none;">
                </div>
                <h5><?= htmlspecialchars($userProfile['fullname'] ?? 'User') ?></h5>
                <p class="text-muted"><?= htmlspecialchars($userProfile['email'] ?? '') ?></p>
            </div>

            <hr class="my-4">

            <!-- Update Profile Form -->
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="update_profile" value="1">

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Full Name</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control" name="fullname" 
                                   value="<?= htmlspecialchars($userProfile['fullname'] ?? '') ?>" required maxlength="50">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control" name="email" 
                                   value="<?= htmlspecialchars($userProfile['email'] ?? '') ?>" required maxlength="100">
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Phone Number</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                            <input type="tel" class="form-control" name="phone" 
                                   value="<?= htmlspecialchars($userProfile['phone'] ?? '') ?>" required maxlength="20">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 mt-4">
                    <i class="fas fa-save me-2"></i> Save Profile Changes
                </button>
            </form>

            <!-- Change Password Section - initially hidden -->
<div class="mt-5 pt-4 border-top">
    <div class="text-center mb-4">
        <button type="button" class="btn btn-outline-success btn-lg px-5" 
                id="togglePasswordBtn">
            <i class="fas fa-lock me-2"></i> Change Password
        </button>
    </div>

    <!-- The form starts hidden -->
    <div id="passwordFormContainer" class="collapse">

        <div class="card border-success shadow-sm">
            <div class="card-header bg-success text-white text-center py-3">
                <h5 class="mb-0">Update Your Password</h5>
            </div>
            <div class="card-body p-4">

                <form method="POST" id="changePasswordForm">
                    <input type="hidden" name="change_password" value="1">

                    <div class="mb-3">
                        <label for="current_password" class="form-label fw-medium">Current Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control" id="current_password" 
                                   name="current_password" required autocomplete="current-password">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="new_password" class="form-label fw-medium">New Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                            <input type="password" class="form-control" id="new_password" 
                                   name="new_password" minlength="8" required autocomplete="new-password">
                        </div>
                        <div class="password-strength mt-2" id="strengthBar"></div>
                        <small id="strengthText" class="form-text">Strength: —</small>
                    </div>

                    <div class="mb-4">
                        <label for="confirm_password" class="form-label fw-medium">Confirm New Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-check"></i></span>
                            <input type="password" class="form-control" id="confirm_password" 
                                   name="confirm_password" required autocomplete="new-password">
                        </div>
                        <div id="matchMessage" class="form-text mt-1" style="min-height: 1.4rem;"></div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success" id="submitPasswordBtn" disabled>
                            <i class="fas fa-shield-alt me-2"></i> Save New Password
                        </button>
                        <button type="button" class="btn btn-outline-secondary mt-2" id="cancelPasswordBtn">
                            Cancel
                        </button>
                    </div>
                </form>

            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Live preview + AJAX upload
const fileInput = document.getElementById('user-file');
const preview = document.getElementById('preview-avatar');

fileInput.addEventListener('change', async function(e) {
    if (!this.files?.[0]) return;

    // Show preview immediately
    const reader = new FileReader();
    reader.onload = ev => preview.src = ev.target.result;
    reader.readAsDataURL(this.files[0]);

    // Upload
    const formData = new FormData();
    formData.append('profile_image', this.files[0]);

    try {
        const res = await fetch('', { method: 'POST', body: formData });
        const data = await res.json();

        if (data.success) {
            // You can update preview with server path if you want to be extra sure
            // preview.src = data.path + '?t=' + Date.now();
            alert('Profile picture updated!');
        } else {
            alert('Upload failed: ' + data.message);
            location.reload(); // revert preview
        }
    } catch (err) {
        console.error(err);
        alert('Network/upload error');
        location.reload();
    }
});

// Toggle change password form
const toggleBtn = document.getElementById('togglePasswordBtn');
const container = document.getElementById('passwordFormContainer');
const cancelBtn = document.getElementById('cancelPasswordBtn');

toggleBtn.addEventListener('click', () => {
    const collapse = new bootstrap.Collapse(container, {
        toggle: true
    });
});

cancelBtn.addEventListener('click', () => {
    const collapse = bootstrap.Collapse.getInstance(container);
    if (collapse) collapse.hide();
    
    // Optional: clear form
    document.getElementById('changePasswordForm').reset();
    document.getElementById('strengthBar').className = 'password-strength mt-2';
    document.getElementById('strengthText').textContent = 'Strength: —';
    document.getElementById('matchMessage').textContent = '';
    document.getElementById('submitPasswordBtn').disabled = true;
});

// ───────────────────────────────────────
// Password strength + match logic
const newPass = document.getElementById('new_password');
const confirmPass = document.getElementById('confirm_password');
const strengthBar = document.getElementById('strengthBar');
const strengthText = document.getElementById('strengthText');
const matchMsg = document.getElementById('matchMessage');
const submitBtn = document.getElementById('submitPasswordBtn');

function updateStrength() {
    const val = newPass.value;
    let score = 0;
    if (val.length >= 8) score++;
    if (/[a-z]/.test(val)) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;

    strengthBar.className = 'password-strength mt-2';
    strengthText.className = 'form-text';

    if (score <= 1) {
        strengthBar.classList.add('strength-weak');
        strengthText.innerHTML = 'Very weak <span class="text-danger">✗</span>';
    } else if (score <= 3) {
        strengthBar.classList.add('strength-medium');
        strengthText.innerHTML = 'Medium <span class="text-warning">⚠</span>';
    } else {
        strengthBar.classList.add('strength-strong');
        strengthText.innerHTML = 'Strong <span class="text-success">✓</span>';
    }

    checkCanSubmit();
}

function checkMatch() {
    if (confirmPass.value === '') {
        matchMsg.textContent = '';
        confirmPass.classList.remove('is-valid', 'is-invalid');
        checkCanSubmit();
        return;
    }

    if (newPass.value === confirmPass.value) {
        matchMsg.innerHTML = '<span class="text-success">Passwords match ✓</span>';
        confirmPass.classList.remove('is-invalid');
        confirmPass.classList.add('is-valid');
    } else {
        matchMsg.innerHTML = '<span class="text-danger">Passwords do not match ✗</span>';
        confirmPass.classList.remove('is-valid');
        confirmPass.classList.add('is-invalid');
    }

    checkCanSubmit();
}

function checkCanSubmit() {
    const strong = newPass.value.length >= 8;
    const match = newPass.value === confirmPass.value && confirmPass.value !== '';
    submitBtn.disabled = !(strong && match);
}

newPass.addEventListener('input', () => { updateStrength(); checkMatch(); });
confirmPass.addEventListener('input', checkMatch);

// Optional: reset validation styles when form is hidden
container.addEventListener('hidden.bs.collapse', () => {
    document.querySelectorAll('.is-valid, .is-invalid').forEach(el => {
        el.classList.remove('is-valid', 'is-invalid');
    });
});
</script>
</body>
</html>
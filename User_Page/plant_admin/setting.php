<?php
require __DIR__ . '/auth.php';;
 // Assuming this sets $pdo; functions.php not needed here

// Initialize variables
$successMessage = '';
$errorMessage = '';
$admin = null;

// Fetch admin info
try {
    $stmt = $pdo->prepare("SELECT id, username FROM admins WHERE id = ?"); // Assuming email field exists; adjust as needed
    $stmt->execute([$_SESSION['admin_id']]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$admin) {
        // Redirect if admin not found (security)
        header("Location: login.php"); // Or error page
        exit();
    }
} catch (PDOException $e) {
    $errorMessage = 'Error loading profile. Please try again.';
    error_log("Admin fetch error: " . $e->getMessage()); // Log in production
}

// Handle password change
if (isset($_POST['change_pass'])) {
    $oldPass = $_POST['old'] ?? '';
    $newPass = $_POST['new'] ?? '';
    $confirmPass = $_POST['confirm'] ?? '';

    // Basic validation
    if (empty($oldPass) || empty($newPass) || empty($confirmPass)) {
        $errorMessage = 'All fields are required.';
    } elseif ($newPass !== $confirmPass) {
        $errorMessage = 'New passwords do not match.';
    } elseif (strlen($newPass) < 8) {
        $errorMessage = 'New password must be at least 8 characters long.';
    } elseif (!password_verify($oldPass, $admin['password'])) {
        $errorMessage = 'Old password is incorrect.';
    } else {
        try {
            $hashedNewPass = password_hash($newPass, PASSWORD_DEFAULT);
            $updateStmt = $pdo->prepare("UPDATE admins SET password = ? WHERE id = ?");
            $updateStmt->execute([$hashedNewPass, $_SESSION['admin_id']]);
            $successMessage = 'Password updated successfully!';
        } catch (PDOException $e) {
            $errorMessage = 'Failed to update password. Please try again.';
            error_log("Password update error: " . $e->getMessage());
        }
    }
}

// Check for flash messages (if redirected from elsewhere)
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $successMessage = 'Action completed successfully!';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Settings - Plant Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZHNDGi6F2m9uR6jW8t5H1e2qW84D6ryZ5zWjT1M3L1D5t7XzP5M8q9M8z7Q9Q9Z9Z9" crossorigin="anonymous" referrerpolicy="no-referrer">
    <style>
        body { background: linear-gradient(to bottom, #f8fafc, #e2e8f0); min-height: 100vh; }
        .settings-header { font-size: 2.5rem; font-weight: 700; color: #198754; margin-bottom: 2rem; text-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .card { border-radius: 1rem; box-shadow: 0 4px 20px rgba(0,0,0,0.1); border: none; overflow: hidden; transition: transform 0.2s ease; }
        .card:hover { transform: translateY(-2px); }
        .card-header { background: linear-gradient(135deg, #198754, #20c997); color: white; font-weight: 600; border-radius: 1rem 1rem 0 0 !important; }
        .form-control:focus { border-color: #198754; box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25); }
        .password-strength { height: 4px; border-radius: 2px; margin-top: 5px; transition: all 0.3s ease; }
        .strength-weak { background: #dc3545; }
        .strength-medium { background: #ffc107; }
        .strength-strong { background: #198754; }
        .btn-logout { background: #6c757d; border-color: #6c757d; }
        .btn-logout:hover { background: #5a6268; }
    </style>
</head>
<body>
    <?php include 'plant_admin/nav.php'; ?>

    <div class="container mt-5">
        <!-- Success/Error Alerts -->
        <?php if ($successMessage): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i><?php echo htmlspecialchars($successMessage); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if ($errorMessage): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i><?php echo htmlspecialchars($errorMessage); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-4">
                    <div class="settings-header">
                        <i class="fas fa-cog me-2"></i>Admin Settings
                    </div>
                    <p class="text-muted">Manage your account details securely.</p>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-user me-2"></i>Profile Information
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1"><strong><i class="fas fa-user-circle me-1"></i>Username:</strong> <?php echo htmlspecialchars($admin['username'] ?? 'N/A'); ?></p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-0"><strong><i class="fas fa-envelope me-1"></i>Email:</strong> <?php echo htmlspecialchars($admin['email'] ?? 'Not set'); ?></p>
                            </div>
                        </div>
                        <!-- Optional: Add edit profile button/form if needed -->
                    </div>
                </div>

                <div class="card" id ="passwordCard">
                    <div class="card-header">
                        <i class="fas fa-lock me-2"></i>Change Password
                    </div>
                    <div class="card-body">
                        <form method="post" id="passwordForm">
                            <div class="mb-3">
                                <label for="old" class="form-label">Current Password</label>
                                <input type="password" class="form-control" id="old" name="old" required>
                            </div>
                            <div class="mb-3">
                                <label for="new" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="new" name="new" required minlength="8">
                                <div class="password-strength" id="strengthBar"></div>
                                <div class="form-text">Must be at least 8 characters, including uppercase, lowercase, number, and symbol.</div>
                            </div>
                            <div class="mb-3">
                                <label for="confirm" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" id="confirm" name="confirm" required>
                                <div class="form-text" id="matchText" style="display: none; color: #dc3545;">Passwords do not match.</div>
                            </div>
                            <button type="submit" name="change_pass" class="btn btn-success w-100" id="updateBtn">
                                <i class="fas fa-save me-1"></i>Update Password
                            </button>
                        </form>
                    </div>
                </div>

                <div class="text-center mt-4 mb-5 ">
                    <a href="logout.php" class="btn btn-logout" onclick="return confirm('Are you sure you want to log out?');">
                        <i class="fas fa-sign-out-alt me-1"></i>Log Out
                    </a>
                    <button class="btn btn-logout" id="toggleBtn" onclick=" return confirm('Are you sure you want to Change Password?'  );">
                        <i class="fas fa-sign-out-alt me-1"></i>Change Password 
                    </button>
                    <button class="btn btn-logout" onclick="window.location.href='plant_admin/sitting_pageuser.php';">
                        <i class="fas fa-sign-out-alt me-1"></i>Sitting PageUser
                    </button>
                    <!-- Trigger Button -->
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#carouselModal">
                    Create Pop Up
                    </button>

                </div>
            </div>
        </div>

        <!-- Admin Page - Carousel Management -->

                        <!-- Modal -->
                        <div class="modal fade" id="carouselModal" tabindex="-1" aria-labelledby="carouselModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form id="carouselForm">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="carouselModalLabel">Add New Slide</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="imgUrl" class="form-label">Image URL</label>
                                                <input type="url" id="imgUrl" class="form-control" placeholder="https://..." required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="slideTitle" class="form-label">Title</label>
                                                <input type="text" id="slideTitle" class="form-control" placeholder="Main heading" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="slideDesc" class="form-label">Description</label>
                                                <textarea id="slideDesc" class="form-control" rows="3" placeholder="Short description or subtitle" required></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label for="btnText" class="form-label">Button Text (optional)</label>
                                                <input type="text" id="btnText" class="form-control" placeholder="e.g. Need Peace →">
                                            </div>
                                            <div class="mb-3">
                                                <label for="btnLink" class="form-label">Button Link (optional)</label>
                                                <input type="url" id="btnLink" class="form-control" placeholder="https://...">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-success">Save Slide</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Add Slide Button -->
                        <div class="container mt-4">
                            <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#carouselModal">
                                + Add New Slide
                            </button>

                            <!-- Carousel Preview (Admin) -->
                            <div id="customCarousel" class="carousel slide mt-4 border rounded shadow" data-bs-ride="carousel">
                                <div class="carousel-inner" id="carouselInner"></div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#customCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#customCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div>
                        </div>


    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        // Password strength indicator
        const newPassInput = document.getElementById('new');
        const strengthBar = document.getElementById('strengthBar');
        const confirmInput = document.getElementById('confirm');
        const matchText = document.getElementById('matchText');
        const updateBtn = document.getElementById('updateBtn');
        const passwordCard = document.getElementById('passwordCard');
       // const toggleBtn = document.getElementById("toggleBtn");
        passwordCard.classList.add('disabled');
        function calculateStrength(password) {
            let score = 0;
            if (password.length >= 8) score += 1;
            if (/[a-z]/.test(password)) score += 1;
            if (/[A-Z]/.test(password)) score += 1;
            if (/[0-9]/.test(password)) score += 1;
            if (/[^A-Za-z0-9]/.test(password)) score += 1;
            return score;
        }

        newPassInput.addEventListener('input', function() {
            const strength = calculateStrength(this.value);
            strengthBar.className = 'password-strength';
            if (strength < 3) {
                strengthBar.classList.add('strength-weak');
                strengthBar.style.width = '33%';
            } else if (strength < 5) {
                strengthBar.classList.add('strength-medium');
                strengthBar.style.width = '66%';
            } else {
                strengthBar.classList.add('strength-strong');
                strengthBar.style.width = '100%';
            }
        });

        // Password match check
        confirmInput.addEventListener('input', function() {
            if (this.value !== newPassInput.value) {
                matchText.style.display = 'block';
                updateBtn.disabled = true;
            } else {
                matchText.style.display = 'none';
                if (calculateStrength(newPassInput.value) >= 3) {
                    updateBtn.disabled = false;
                }
            }
        });

        newPassInput.addEventListener('input', function() {
            confirmInput.dispatchEvent(new Event('input'));
        });

        // Auto-dismiss alerts after 5s
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
            const toggleBtn = document.getElementById("toggleBtn");
            toggleBtn.addEventListener("click", () => {
            if (passwordCard.style.display === "none") {
                passwordCard.style.display = "block"; // show card
            } else {
                passwordCard.style.display = "none";  // hide card
            }
            });

            document.getElementById("carouselForm").addEventListener("submit", e => {
                e.preventDefault();
                const slides = JSON.parse(localStorage.getItem("slides") || "[]");
                slides.push({
                    image: document.getElementById("imgUrl").value,
                    title: document.getElementById("slideTitle").value,
                    description: document.getElementById("slideDesc").value
                });
                localStorage.setItem("slides", JSON.stringify(slides));
                alert("Slide saved!");

                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById("carouselModal"));
                modal.hide();

                // Refresh carousel
                loadSlides();
                });

                function loadSlides() {
                const slides = JSON.parse(localStorage.getItem("slides") || "[]");
                const carouselInner = document.getElementById("carouselInner");
                carouselInner.innerHTML = "";
                slides.forEach((slide, index) => {
                    const item = document.createElement("div");
                    item.className = "carousel-item" + (index === 0 ? " active" : "");
                    item.innerHTML = `
                    <img src="${slide.image}" class="d-block w-100" style="max-height:70vh; object-fit:cover;" alt="${slide.title}">
                    <div class="carousel-caption d-flex flex-column justify-content-center h-100 text-white text-start ps-5 pb-5">
                        <h2 class="display-4 fw-bold mb-3 text-shadow">${slide.title}</h2>
                        <p class="fs-4 fw-light mb-4 opacity-90">${slide.description}</p>
                    </div>
                    `;
                    carouselInner.appendChild(item);
                });
                }

                // Load slides on page load
                loadSlides();

                // Slide data structure
                let slides = JSON.parse(localStorage.getItem('carouselSlides')) || [
                    {
                        imgUrl: "https://i.pinimg.com/736x/c3/d5/db/c3d5dbdb618c753c8467454d942ef1d6.jpg",
                        title: "Cambodia Need Peace",
                        desc: "Thai Military Attacks and Violations of Cambodian",
                        btnText: "Need Peace →",
                        btnLink: "https://angkor-whispers.vercel.app/"
                    }
                    // You can add your other default slides here too
                ];

                // Render carousel
                function renderCarousel() {
                    const inner = document.getElementById('carouselInner');
                    inner.innerHTML = '';

                    slides.forEach((slide, index) => {
                        const isActive = index === 0 ? 'active' : '';

                        const item = document.createElement('div');
                        item.className = `carousel-item ${isActive}`;
                        item.innerHTML = `
                            <img src="${slide.imgUrl}" class="d-block w-100" style="max-height: 70vh; object-fit: cover;" alt="${slide.title}">
                            <div class="carousel-caption d-flex flex-column justify-content-center h-100 text-white text-start ps-5 pb-5">
                                <h2 class="display-4 fw-bold mb-3 text-shadow">${slide.title}</h2>
                                <p class="fs-4 fw-light mb-4 opacity-90">${slide.desc}</p>
                                ${slide.btnText && slide.btnLink ? `
                                    <a href="${slide.btnLink}" class="btn btn-success btn-lg px-5 py-3 rounded-pill shadow" target="_blank">
                                        ${slide.btnText}
                                    </a>
                                ` : ''}
                                <button class="btn btn-danger btn-sm mt-3" onclick="deleteSlide(${index})">
                                    Delete Slide
                                </button>
                            </div>
                        `;
                        inner.appendChild(item);
                    });

                    // Re-initialize carousel if needed
                    const carousel = new bootstrap.Carousel(document.getElementById('customCarousel'));
                }

                // Delete slide
                function deleteSlide(index) {
                    if (confirm('Delete this slide?')) {
                        slides.splice(index, 1);
                        localStorage.setItem('carouselSlides', JSON.stringify(slides));
                        renderCarousel();
                    }
                }

                // Form submit
                document.getElementById('carouselForm').addEventListener('submit', function(e) {
                    e.preventDefault();

                    const newSlide = {
                        imgUrl: document.getElementById('imgUrl').value.trim(),
                        title: document.getElementById('slideTitle').value.trim(),
                        desc: document.getElementById('slideDesc').value.trim(),
                        btnText: document.getElementById('btnText').value.trim(),
                        btnLink: document.getElementById('btnLink').value.trim()
                    };

                    if (!newSlide.imgUrl || !newSlide.title || !newSlide.desc) {
                        alert('Please fill in all required fields');
                        return;
                    }

                    slides.push(newSlide);
                    localStorage.setItem('carouselSlides', JSON.stringify(slides));

                    // Reset form & close modal
                    this.reset();
                    bootstrap.Modal.getInstance(document.getElementById('carouselModal')).hide();

                    renderCarousel();
                });

                // Load on page start
                renderCarousel();

    </script>
</body>
</html>
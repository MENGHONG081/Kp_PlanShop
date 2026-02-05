<?php
include 'plant_admin/auth.php';
// Initialize variables
$successMessage = '';
$errorMessage = '';
$editId = isset($_GET['edit']) ? (int)$_GET['edit'] : null;
$editUser = null;

// Fetch edit data if needed
if ($editId) {
    $editStmt = $pdo->prepare("SELECT id, fullname, email, phone, FROM users WHERE id = ?");
    $editStmt->execute([$editId]);
    $editUser = $editStmt->fetch(PDO::FETCH_ASSOC);
    if (!$editUser) {
        $errorMessage = 'User not found.';
        $editId = null;
    }
}

// Handle form submission
if (isset($_POST['save'])) {
    // Basic validation
    $fullname = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    if (empty($fullname) || strlen($fullname) > 255) {
        $errorMessage = 'Fullname is required and must be under 255 characters.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = 'Invalid email format.';
    } elseif (empty($phone) || strlen($phone) > 20) {
        $errorMessage = 'Phone is required and must be under 20 characters.';
    } else {
        // Check email uniqueness (if different from current)
        $emailCheckStmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $emailCheckStmt->execute([$email, $editId ?? 0]);
        if ($emailCheckStmt->fetch()) {
            $errorMessage = 'Email already exists.';
        } else {
            try {
                if (isset($_POST['id']) && $_POST['id']) {
                    $id = (int)$_POST['id'];
                    // Check if exists before update
                    $checkStmt = $pdo->prepare("SELECT id FROM users WHERE id = ?");
                    $checkStmt->execute([$id]);
                    if ($checkStmt->fetch()) {
                        $updateStmt = $pdo->prepare("UPDATE users SET fullname = ?, email = ?, phone = ? WHERE id = ?");
                        $updateStmt->execute([$fullname, $email, $phone, $id]);
                        $successMessage = 'User updated successfully!';
                    } else {
                        $errorMessage = 'User not found for update.';
                    }
                } else {
                    // For new user, we'd need password, but since this is admin add, perhaps generate or require password field
                    // Assuming no add for now, or add password field if needed. For simplicity, redirect to registration.
                    $errorMessage = 'User creation via admin panel not implemented. Use registration form.';
                    // To enable add: Add password field, hash it, and insert.
                }
                // Redirect to avoid resubmit
                if (empty($errorMessage)) {
                    header("Location: plant_admin/users.php?success=1");
                    exit();
                }
            } catch (PDOException $e) {
                $errorMessage = 'Database error occurred. Please try again.';
                // Log error in production: error_log($e->getMessage());
            }
        }
    }
}

// Handle delete
if (isset($_GET['del'])) {
    $delId = (int)$_GET['del'];
    try {
        $deleteStmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $deleteStmt->execute([$delId]);
        $successMessage = 'User deleted successfully!';
    } catch (PDOException $e) {
        $errorMessage = 'Error deleting user.';
    }
    header("Location: plant_admin/users.php?success=1");
    exit();
}

// Fetch all users
try {
    $usersStmt = $pdo->query("SELECT id, fullname, email, phone, created_at FROM users ORDER BY created_at DESC");
    $users = $usersStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $errorMessage = 'Error loading users.';
    $users = [];
}

// Check for flash messages from redirect
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $successMessage = 'Action completed successfully!';
}
// Count today's feedback
$stmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM customer_feedback 
    WHERE DATE(submitted_at) = CURDATE()
");
$stmt->execute();
$todayCount = $stmt->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - Plant Shop Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .table-hover tbody tr:hover { background-color: rgba(0,123,255,.075); }
        .alert { border-radius: 8px; }
    </style>
</head>
<body>
    <?php include 'nav.php'; ?>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-users me-2"></i>User Management</h2>
            <!-- Note: Add button disabled for now; implement registration link or full add form -->
             <div class ="d-flex gap-2">
            <a href="registration.php" class="btn btn-primary" title="Add via Registration">
                <i class="fas fa-plus me-1"></i>Add User
            </a>
            <a href="feedback.php" class="btn btn-info" title="View Feedback">
                <i class="bi bi-chat-right-text-fill me-1"></i>Feedback
                <span class="badge bg-light text-dark ms-2">
                <?= $todayCount ?>
                </span>
            </a>
            </div>    
        </div>

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

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Registered</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($users)): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">No users found. Encourage registrations!</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($users as $u): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($u['id']); ?></td>
                                        <td><?php echo htmlspecialchars($u['fullname']); ?></td>
                                        <td><?php echo htmlspecialchars($u['email']); ?></td>
                                        <td><?php echo htmlspecialchars($u['phone']); ?></td>
                                        <td><?php echo date('M j, Y', strtotime($u['created_at'])); ?></td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#userModal" onclick="editUser(<?php echo $u['id']; ?>, '<?php echo addslashes($u['fullname']); ?>', '<?php echo addslashes($u['email']); ?>', '<?php echo addslashes($u['phone']); ?>')">
                                                <i class="fas fa-edit me-1"></i>Edit
                                            </a>
                                            <a href="users.php?del=<?php echo $u['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user? This cannot be undone and may affect orders.');">
                                                <i class="fas fa-trash me-1"></i>Delete
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit User Modal (No Add for security; use registration) -->
    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" id="userForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="userModalLabel">Edit User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editId" value="">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" maxlength="255" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="tel" class="form-control" id="phone" name="phone" maxlength="20" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="save" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        function editUser(id, username, email, phone) {
            document.getElementById('editId').value = id;
            document.getElementById('username').value = username;
            document.getElementById('email').value = email;
            document.getElementById('phone').value = phone;
            document.getElementById('userModalLabel').textContent = 'Edit User';

            new bootstrap.Modal(document.getElementById('userModal')).show();
        }

        // Reset modal on hide
        document.getElementById('userModal').addEventListener('hidden.bs.modal', function () {
            this.querySelector('form').reset();
            document.getElementById('editId').value = '';
            document.getElementById('userModalLabel').textContent = 'Edit User';
        });

        // Auto-dismiss alerts after 5s
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Optional: Auto-open edit modal if ?edit=ID on load
        <?php if ($editId): ?>
            editUser(<?php echo $editId; ?>, <?php echo json_encode($editUser['username']); ?>, <?php echo json_encode($editUser['email']); ?>, <?php echo json_encode($editUser['phone']); ?>);
        <?php endif; ?>
    </script>
</body>
</html>
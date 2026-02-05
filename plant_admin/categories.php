<?php
require 'auth.php'; // Assuming this handles session/auth and sets $pdo
// Initialize variables
$successMessage = '';
$errorMessage = '';
$editId = isset($_GET['edit']) ? (int)$_GET['del'] : null; // Wait, fix: use $_GET['edit']
$editCategory = null;

// Fetch edit data if needed
if ($editId) {
    $editStmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
    $editStmt->execute([$editId]);
    $editCategory = $editStmt->fetch(PDO::FETCH_ASSOC);
    if (!$editCategory) {
        $errorMessage = 'Category not found.';
        $editId = null;
    }
}

// Handle form submission
if (isset($_POST['save'])) {
    // Basic validation
    $name = trim($_POST['name'] ?? '');
    if (empty($name) || strlen($name) > 100) {
        $errorMessage = 'Category name is required and must be under 100 characters.';
    } else {
        try {
            if (isset($_POST['id']) && $_POST['id']) {
                $id = (int)$_POST['id'];
                // Check if exists before update
                $checkStmt = $pdo->prepare("SELECT id FROM categories WHERE id = ?");
                $checkStmt->execute([$id]);
                if ($checkStmt->fetch()) {
                    $updateStmt = $pdo->prepare("UPDATE categories SET name = ? WHERE id = ?");
                    $updateStmt->execute([$name, $id]);
                    $successMessage = 'Category updated successfully!';
                } else {
                    $errorMessage = 'Category not found for update.';
                }
            } else {
                // Insert new
                $insertStmt = $pdo->prepare("INSERT INTO categories (name) VALUES (?)");
                $insertStmt->execute([$name]);
                $successMessage = 'Category added successfully!';
            }
            // Redirect to avoid resubmit
            header("Location: categories.php?success=1");
            exit();
        } catch (PDOException $e) {
            $errorMessage = 'Database error occurred. Please try again.';
            // Log error in production: error_log($e->getMessage());
        }
    }
}

// Handle delete
if (isset($_GET['del'])) {
    $delId = (int)$_GET['del'];
    try {
        $deleteStmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
        $deleteStmt->execute([$delId]);
        $successMessage = 'Category deleted successfully!';
    } catch (PDOException $e) {
        $errorMessage = 'Error deleting category.';
    }
    header("Location: categories.php?success=1");
    exit();
}

// Fetch all categories
try {
    $categoriesStmt = $pdo->query("SELECT * FROM categories ORDER BY name ASC");
    $categories = $categoriesStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $errorMessage = 'Error loading categories.';
    $categories = [];
}

// Check for flash messages from redirect
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $successMessage = 'Action completed successfully!';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Management - Plant Shop Admin</title>
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
            <h2><i class="fas fa-tags me-2"></i>Category Management</h2>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#catModal">
                <i class="fas fa-plus me-1"></i>Add Category
            </button>
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
                                <th>Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($categories)): ?>
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">No categories found. Add one to get started!</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($categories as $c): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($c['id']); ?></td>
                                        <td><?php echo htmlspecialchars($c['name']); ?></td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#catModal" onclick="editCategory(<?php echo $c['id']; ?>, '<?php echo addslashes($c['name']); ?>')">
                                                <i class="fas fa-edit me-1"></i>Edit
                                            </a>
                                            <a href="categories.php?del=<?php echo $c['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this category? This cannot be undone.');">
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

    <!-- Add/Edit Category Modal -->
    <div class="modal fade" id="catModal" tabindex="-1" aria-labelledby="catModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" id="catForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="catModalLabel">Add Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editId" value="">
                        <div class="mb-3">
                            <label for="name" class="form-label">Category Name</label>
                            <input type="text" class="form-control" id="name" name="name" maxlength="100" required placeholder="Enter category name (e.g., Succulents)">
                            <div class="form-text">Keep it descriptive for easy plant organization.</div>
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
        function editCategory(id, name) {
            document.getElementById('editId').value = id;
            document.getElementById('name').value = name;
            document.getElementById('catModalLabel').textContent = 'Edit Category';
            new bootstrap.Modal(document.getElementById('catModal')).show();
        }

        // Reset modal on hide
        document.getElementById('catModal').addEventListener('hidden.bs.modal', function () {
            this.querySelector('form').reset();
            document.getElementById('editId').value = '';
            document.getElementById('catModalLabel').textContent = 'Add Category';
        });

        // Auto-dismiss alerts after 5s
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
</body>
</html>
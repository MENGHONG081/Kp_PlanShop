<?php
require __DIR__ . '/auth.php';
require __DIR__ . '/functions.php';


// Initialize variables
$successMessage = '';
$errorMessage = '';
$editId = isset($_GET['edit']) ? (int)$_GET['edit'] : null;
$editProduct = null;

// Fetch edit data if needed
if ($editId) {
    $editStmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $editStmt->execute([$editId]);
    $editProduct = $editStmt->fetch(PDO::FETCH_ASSOC);
    if (!$editProduct) {
        $errorMessage = 'Product not found.';
        $editId = null;
    }
}

// Handle form submission
if (isset($_POST['save'])) {
    // Basic validation
    $name = trim($_POST['name'] ?? '');
    $cat = (int)($_POST['category_id'] ?? 0);
    $price = floatval($_POST['price'] ?? 0);
    $stock = (int)($_POST['stock'] ?? 0);
    $desc = trim($_POST['description'] ?? '');
    $img = '';

    if (empty($name) || strlen($name) > 255) {
        $errorMessage = 'Product name is required and must be under 255 characters.';
    } elseif ($cat <= 0) {
        $errorMessage = 'Please select a valid category.';
    } elseif ($price <= 0) {
        $errorMessage = 'Price must be greater than 0.';
    } elseif ($stock < 0) {
        $errorMessage = 'Stock cannot be negative.';
    } else {
        // Validate category exists
        $catCheck = $pdo->prepare("SELECT id FROM categories WHERE id = ?");
        $catCheck->execute([$cat]);
        if (!$catCheck->fetch()) {
            $errorMessage = 'Selected category does not exist.';
        } else {
            // Handle image upload
            if (!empty($_FILES['image']['name'])) {
                $img = uploadImage($_FILES['image']);
                if (!$img) {
                    $errorMessage = 'Failed to upload image. Please try a smaller file.';
                }
            }

            if (empty($errorMessage)) {
                try {
                    if (isset($_POST['id']) && $_POST['id']) {
                        $id = (int)$_POST['id'];
                        // Check if exists before update
                        $checkStmt = $pdo->prepare("SELECT id FROM products WHERE id = ?");
                        $checkStmt->execute([$id]);
                        if ($checkStmt->fetch()) {
                            if ($img) {
                                $updateStmt = $pdo->prepare("UPDATE products SET name=?, category_id=?, price=?, stock=?, description=?, image=? WHERE id=?");
                                $updateStmt->execute([$name, $cat, $price, $stock, $desc, $img, $id]);
                            } else {
                                $updateStmt = $pdo->prepare("UPDATE products SET name=?, category_id=?, price=?, stock=?, description=? WHERE id=?");
                                $updateStmt->execute([$name, $cat, $price, $stock, $desc, $id]);
                            }
                            $successMessage = 'Product updated successfully!';
                        } else {
                            $errorMessage = 'Product not found for update.';
                        }
                    } else {
                        // Insert new (image is '' if none uploaded)
                        $insertStmt = $pdo->prepare("INSERT INTO products (name, category_id, price, stock, description, image) VALUES (?, ?, ?, ?, ?, ?)");
                        $insertStmt->execute([$name, $cat, $price, $stock, $desc, $img]);
                        $successMessage = 'Product added successfully!';
                    }
                    // Redirect to avoid resubmit
                    header("Location: plant_admin/products.php?success=1");
                    exit();
                } catch (PDOException $e) {
                    $errorMessage = 'Database error occurred. Please try again.';
                    // Log error in production: error_log($e->getMessage());
                }
            }
        }
    }
}

// Handle delete
if (isset($_GET['del'])) {
    $delId = (int)$_GET['del'];
    try {
        // Optional: Delete associated image file
        $imgStmt = $pdo->prepare("SELECT image FROM products WHERE id = ?");
        $imgStmt->execute([$delId]);
        $productImg = $imgStmt->fetchColumn();
        if ($productImg && file_exists("uploads/$productImg")) {
            unlink("uploads/$productImg");
        }

        $deleteStmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $deleteStmt->execute([$delId]);
        $successMessage = 'Product deleted successfully!';
    } catch (PDOException $e) {
        $errorMessage = 'Error deleting product.';
    }
    header("Location:plant_admin/products.php?success=1");
    exit();
}

// Fetch all products and categories
try {
    $productsStmt = $pdo->query("SELECT p.*, c.name AS cat FROM products p JOIN categories c ON c.id = p.category_id ORDER BY p.name ASC");
    $products = $productsStmt->fetchAll(PDO::FETCH_ASSOC);

    $categoriesStmt = $pdo->query("SELECT * FROM categories ORDER BY name ASC");
    $categories = $categoriesStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $errorMessage = 'Error loading data.';
    $products = [];
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
    <title>Product Management - Plant Shop Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css"> <!-- Assuming this is your custom CSS -->
    <style>
        body { background-color: #f8f9fa; }
        .table-hover tbody tr:hover { background-color: rgba(0,123,255,.075); }
        .alert { border-radius: 8px; }
        .product-img { max-width: 60px; height: auto; border-radius: 4px; }
        .modal .current-image { max-width: 100%; height: auto; border-radius: 4px; margin-bottom: 10px; }
    </style>
</head>
<body>
    <?php include 'nav.php'; ?>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-boxes me-2"></i>Product Management</h2>
    <div class="d-flex flex-wrap gap-2">
  <!-- Search Toggle Button (visible on mobile/small screens) -->
<button class="btn btn-primary d-flex align-items-center gap-2 shadow-sm search-btn d-md-none"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#searchCollapse"
        aria-expanded="false"
        aria-controls="searchCollapse">
    <i class="fas fa-search"></i>
    <span>Search</span>
</button>

<!-- Full Search Form (always visible on desktop, collapsible on mobile) -->
<div class="collapse d-md-block w-100" id="searchCollapse">
    <form class="d-flex mx-auto my-3 my-md-0" style="max-width: 600px;" id="searchForm">
        <input class="form-control me-2 shadow-sm" type="search" placeholder="Search products..." 
               aria-label="Search" id="searchInput" autocomplete="off">
        <button class="btn btn-primary shadow-sm" type="submit">
            <i class="fas fa-search"></i>
        </button>
    </form>

    <!-- Live Search Results Dropdown -->
    <div id="searchResults" class="position-absolute bg-white shadow-lg rounded-3 mt-2 w-100" 
         style="max-width: 600px; z-index: 1000; display: none; max-height: 70vh; overflow-y: auto;">
        <div class="p-3 text-center text-muted small">Type to search products...</div>
    </div>
    </div>

        <button class="btn btn-success d-flex align-items-center gap-2 shadow-sm"
                data-bs-toggle="modal"
                data-bs-target="#productModal"
                data-type="new">
            <i class="fas fa-box-open"></i>
            <span>Add Product</span>
        </button>

        <button class="btn btn-info text-white d-flex align-items-center gap-2 shadow-sm" onclick="window.location.href='More.php#new-arrivals'"> 
            <i class="fas fa-truck-loading"></i>
            <span>New Arrival</span>
        </button>

        <button class="btn btn-warning d-flex align-items-center gap-2 shadow-sm" onclick="window.location.href='More.php#discount-products'">
            <i class="fas fa-tags"></i>
            <span>Discount</span>
        </button>

        <button class="btn btn-danger d-flex align-items-center gap-2 shadow-sm" onclick="window.location.href='More.php#best-sellers'">
            <i class="fas fa-fire"></i>
            <span>Best Seller</span>
        </button>
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
                                <th>Image</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($products)): ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">No products found. Add one to get started!</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($products as $p): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($p['id']); ?></td>
                                        <td>
                                            <?php if ($p['image']): ?>
                                                <img src="uploads/<?php echo htmlspecialchars($p['image']); ?>" alt="Product Image" class="product-img">
                                            <?php else: ?>
                                                <span class="text-muted">No image</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($p['name']); ?></td>
                                        <td><?php echo htmlspecialchars($p['cat']); ?></td>
                                        <td>$<?php echo number_format($p['price'], 2); ?></td>
                                        <td>
                                            <?php if ($p['stock'] > 0): ?>
                                                <span class="badge bg-success"><?php echo $p['stock']; ?></span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Out of Stock</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="products.php" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#productModal" onclick="editProduct(<?php echo $p['id']; ?>, '<?php echo addslashes($p['name']); ?>', <?php echo $p['category_id']; ?>, <?php echo $p['price']; ?>, <?php echo $p['stock']; ?>, '<?php echo addslashes($p['description']); ?>', '<?php echo addslashes($p['image']); ?>')">
                                                <i class="fas fa-edit me-1"></i>Edit
                                            </a>
                                            <a href="products.php?del=<?php echo $p['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this product? This will also remove the associated image and cannot be undone.');">
                                                <i class="fas fa-trash me-1"></i>Delete
                                            </a>
                                            <div class="btn-group ms-4 ">
                                                <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                       More
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" data-bs-toggle="modal"
                                                            data-bs-target="#discountModal"
                                                            data-type="discount">Discount</a></li>
                                                    <li><a class="dropdown-item"data-bs-toggle="modal"
                                                            data-bs-target="#arrivalModal"
                                                            data-type="arrival" >Arrival</a></li>
                                                    <li><a class="dropdown-item" data-bs-toggle="modal"
                                                                data-bs-target="#bestModal"
                                                                data-type="best">Best Seller</a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li><a class="dropdown-item" href="#">Thank You</a></li>
                                                </ul>
                                                </div>
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

    <!-- Add/Edit Product Modal -->
    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                            <form method="post" id="productForm" enctype="multipart/form-data">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="productModalLabel">Add Product</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="id" id="editId" value="">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Product Name</label>
                                        <input type="text" class="form-control" id="name" name="name" maxlength="255" required placeholder="Enter product name (e.g., Succulent Pot)">
                                    </div>
                                    <div class="mb-3">
                                        <label for="category_id" class="form-label">Category</label>
                                        <select class="form-select" id="category_id" name="category_id" required>
                                            <option value="">Select a category...</option>
                                            <?php foreach ($categories as $c): ?>
                                                <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['name']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="price" class="form-label">Price ($)</label>
                                        <input type="number" class="form-control" id="price" name="price" step="0.01" min="0.01" required placeholder="0.00">
                                    </div>
                                    <div class="mb-3">
                                        <label for="stock" class="form-label">Stock Quantity</label>
                                        <input type="number" class="form-control" id="stock" name="stock" min="0" required placeholder="0">
                                    </div>
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter product description..."></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="image" class="form-label">Product Image</label>
                                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                        <div class="form-text">Upload a JPG/PNG image (max 2MB recommended).</div>
                                        <div id="currentImageContainer" style="display: none;">
                                            <small class="text-muted">Current Image:</small>
                                            <img id="currentImage" src="" alt="Current Product Image" class="current-image">
                                            <div class="form-text">Upload a new image to replace this one.</div>
                                        </div>
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
   <!-- Discount  Product Modal -->
                        <div>
                            <div class="modal fade" id="discountModal" tabindex="-1" aria-labelledby="discountModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                        <form method="post" action="More.php" id="discountForm">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Discount</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <!-- Product -->
                            <div class="mb-3">
                                <label class="form-label">Product</label>
                                <select class="form-select" name="product_id" id="product_id" required>
                                    <option value="">Select product...</option>
                                    <?php foreach ($products as $p): ?>
                                        <option value="<?= $p['id'] ?>" data-price="<?= $p['price'] ?>">
                                            <?= htmlspecialchars($p['name']) ?> ($<?= $p['price'] ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Discount Percent -->
                            <div class="mb-3">
                                <label class="form-label">Discount (%)</label>
                                <input type="number" class="form-control" id="discount_percent"
                                    name="discount_percent" min="1" max="100" step="0.01" required>
                            </div>

                            <!-- Price After Discount -->
                            <div class="mb-3">
                                <label class="form-label">Price After Discount</label>
                                <input type="number" class="form-control" id="price_after_discount"
                                    name="price_after_discount" readonly>
                            </div>

                            <!-- Description -->
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description"></textarea>
                            </div>

                            <!-- Date -->
                            <div class="mb-3">
                                <label class="form-label">Discount Date</label>
                                <input type="date" class="form-control" name="discount_date" required>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" name="save_discount" class="btn btn-primary">Save Discount</button>
                        </div>
                    </form>

                                    </div>
                                </div>
                            </div>
                        </div>
    <!-- Arrival  Product Modal -->
    <div>
        <div class="modal fade" id="arrivalModal" tabindex="-1" aria-labelledby="arrivalModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form method="post" id="arrivalForm" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title" id="arrivalModalLabel">Add New Arrival</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id" id="arrivalId" value="">
                            <div class="mb-3">
                                <label for="arrival_name" class="form-label">Arrival Name</label>
                                <input type="text" class="form-control" id="arrival_name" name="arrival_name" maxlength="255" required placeholder="Enter arrival name (e.g., Spring Collection)">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" name="save_arrival" class="btn btn-primary">Save Arrival</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <!-- Best Seller  Product Modal -->
    <div>
        <div class="modal fade" id="bestModal" tabindex="-1" aria-labelledby="bestModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form method="post" id="bestForm" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title" id="bestModalLabel">Add Best Seller</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id" id="bestId" value="">
                            <div class="mb-3">
                                <label for="best_name" class="form-label">Best Seller Name</label>
                                <input type="text" class="form-control" id="best_name" name="best_name" maxlength="255" required placeholder="Enter best seller name (e.g., Top Picks)">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" name="save_best" class="btn btn-primary">Save Best Seller</button>
                        </div>
                    </form>
                </div>                  
            </div>
        </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        function editProduct(id, name, catId, price, stock, desc, img) {
            document.getElementById('editId').value = id;
            document.getElementById('name').value = name;
            document.getElementById('category_id').value = catId;
            document.getElementById('price').value = price;
            document.getElementById('stock').value = stock;
            document.getElementById('description').value = desc;
            document.getElementById('productModalLabel').textContent = 'Edit Product';

            // Handle current image
            const currentImgContainer = document.getElementById('currentImageContainer');
            const currentImg = document.getElementById('currentImage');
            if (img) {
                currentImg.src = 'uploads/' + img;
                currentImgContainer.style.display = 'block';
            } else {
                currentImgContainer.style.display = 'none';
            }

            new bootstrap.Modal(document.getElementById('productModal')).show();
        }

        // Reset modal on hide
        document.getElementById('productModal').addEventListener('hidden.bs.modal', function () {
            this.querySelector('form').reset();
            document.getElementById('editId').value = '';
            document.getElementById('productModalLabel').textContent = 'Add Product';
            document.getElementById('currentImageContainer').style.display = 'none';
            document.getElementById('category_id').value = '';
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
            editProduct(<?php echo $editId; ?>, <?php echo json_encode($editProduct['name']); ?>, <?php echo $editProduct['category_id']; ?>, <?php echo $editProduct['price']; ?>, <?php echo $editProduct['stock']; ?>, <?php echo json_encode($editProduct['description']); ?>, <?php echo json_encode($editProduct['image']); ?>);
        <?php endif; ?>
        // Discount Modal Price Calculation
            const productSelect = document.getElementById('product_id');
            const discountInput = document.getElementById('discount_percent');
            const priceAfter = document.getElementById('price_after_discount');

            function calculateDiscount() {
                const price = productSelect.selectedOptions[0]?.dataset.price || 0;
                const discount = discountInput.value || 0;

                const finalPrice = price - (price * discount / 100);
                priceAfter.value = finalPrice.toFixed(2);
            }

            productSelect.addEventListener('change', calculateDiscount);
            discountInput.addEventListener('input', calculateDiscount);
    </script>
</body>
</html>

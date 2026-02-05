<?php
include 'plant_admin/auth.php';

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle add to cart
if (isset($_GET['add'])) {
    $product_id = intval($_GET['add']);
    $qty = intval($_GET['qty'] ?? 1);
    if ($qty < 1) $qty = 1;

    // Fetch product details
    $stmt = $pdo->prepare("SELECT id, name, price FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        $_SESSION['cart'][$product_id] = [
            'name' => $product['name'],
            'price' => floatval($product['price']),
            'qty' => $qty
        ];
    }

    // Redirect to avoid resubmit, preserving search/filter params
    $query_string = http_build_query(array_filter($_GET, function($key) { return !in_array($key, ['add', 'qty']); }, ARRAY_FILTER_USE_KEY));
    header("Location: plant_admin/products.php?" . $query_string);
    exit();
}

// Fetch categories for filter (assuming 'category' field in products)
$stmt = $pdo->query("SELECT DISTINCT category FROM products WHERE category IS NOT NULL ORDER BY category");
$categories = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Handle search and filters
$search = trim($_GET['search'] ?? '');
$category = trim($_GET['category'] ?? '');
$min_price = floatval($_GET['min_price'] ?? 0);
$max_price = floatval($_GET['max_price'] ?? 999999);

// Build WHERE clause
$where_conditions = [];
$params = [];

if (!empty($search)) {
    $where_conditions[] = "(name LIKE ? OR description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if (!empty($category)) {
    $where_conditions[] = "category = ?";
    $params[] = $category;
}

if ($min_price > 0) {
    $where_conditions[] = "price >= ?";
    $params[] = $min_price;
}

if ($max_price < 999999) {
    $where_conditions[] = "price <= ?";
    $params[] = $max_price;
}

$where_sql = empty($where_conditions) ? '' : 'WHERE ' . implode(' AND ', $where_conditions);

$sql = "SELECT * FROM products $where_sql ORDER BY id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Products</title>
  <link rel="stylesheet" href="assets/bootstrap.min.css">
</head>
<body>
<?php include ROOT_PATH . '/plant_admin/nav.php'; ?>
<div class="container mt-4">
  <h2>Our Plants</h2>

  <!-- Search and Filters -->
  <form method="get" class="row g-3 mb-4">
    <div class="col-md-4">
      <label for="search" class="form-label">Search Products</label>
      <input type="text" class="form-control" id="search" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Enter name or description">
    </div>
    <div class="col-md-2">
      <label for="category" class="form-label">Category</label>
      <select class="form-select" id="category" name="category">
        <option value="">All Categories</option>
        <?php foreach ($categories as $cat): ?>
          <option value="<?= htmlspecialchars($cat) ?>" <?= ($category === $cat) ? 'selected' : '' ?>><?= htmlspecialchars($cat) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-2">
      <label for="min_price" class="form-label">Min Price</label>
      <input type="number" class="form-control" id="min_price" name="min_price" value="<?= $min_price > 0 ? $min_price : '' ?>" min="0" step="0.01" placeholder="0">
    </div>
    <div class="col-md-2">
      <label for="max_price" class="form-label">Max Price</label>
      <input type="number" class="form-control" id="max_price" name="max_price" value="<?= $max_price < 999999 ? $max_price : '' ?>" min="0" step="0.01" placeholder="No limit">
    </div>
    <div class="col-md-2 d-flex align-items-end">
      <button type="submit" class="btn btn-primary w-100">Filter</button>
    </div>
  </form>

  <?php if (empty($products)): ?>
    <p>No products found matching your criteria.</p>
    <a href="products.php" class="btn btn-secondary">Clear Filters</a>
  <?php else: ?>
    <div class="row">
      <?php foreach ($products as $p): ?>
        <div class="col-md-4 mb-4">
          <div class="card h-100">
            <?php if (!empty($p['image'])): ?>
              <img src="uploads/<?= htmlspecialchars($p['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($p['name']) ?>" style="height: 200px; object-fit: cover;">
            <?php endif; ?>
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($p['name']) ?></h5>
              <p class="card-text"><?= htmlspecialchars($p['description']) ?></p>
              <p class="text-success fw-bold">$<?= number_format($p['price'], 2) ?></p>
              <?php if (!empty($p['category'])): ?>
                <span class="badge bg-secondary"><?= htmlspecialchars($p['category']) ?></span>
              <?php endif; ?>
            </div>
            <div class="card-footer">
              <form method="get" class="d-inline">
                <input type="hidden" name="add" value="<?= $p['id'] ?>">
                <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">
                <input type="hidden" name="category" value="<?= htmlspecialchars($category) ?>">
                <input type="hidden" name="min_price" value="<?= $min_price ?>">
                <input type="hidden" name="max_price" value="<?= $max_price ?>">
                <div class="input-group input-group-sm mb-2">
                  <span class="input-group-text">Qty</span>
                  <input type="number" name="qty" value="1" min="1" max="10" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary btn-sm">Add to Cart</button>
              </form>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="text-center">
      <a href="plant_admin/cart.php" class="btn btn-success btn-lg">View Cart</a>
    </div>
  <?php endif; ?>
</div>
</body>
</html>
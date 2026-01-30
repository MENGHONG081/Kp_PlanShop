 <!-- Product Grid -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
<?php if (!empty($products)): ?>
<div class="row g-4 g-lg-5">
    <?php foreach ($products as $product): ?>
    <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
        <article class="card h-80 shadow-sm border border-light product-card">

            <!-- Image -->
            <div class="ratio ratio-1x1 bg-light overflow-hidden">
                <img src="../plant_admin/uploads/<?= htmlspecialchars($product['image'] ?? 'placeholder.png') ?>"
                     alt="<?= htmlspecialchars($product['name']) ?>"
                     class="card-img-top object-fit-cover product-img"
                     loading="lazy">
            </div>

            <!-- Card Body -->
            <div class="card-body d-flex flex-column p-4">
                <h5 class="card-title fw-bold text-dark mb-2">
                    <?= htmlspecialchars($product['name']) ?>
                </h5>

                <div class="mt-auto">
                    <p class="fs-4 fw-bold text-success mb-3">
                        $<?= number_format($product['price'] / 100, 2) ?>
                    </p>

                    <!-- Buttons -->
                    <div class="d-flex gap-2">
                        <!-- Add to Cart -->
                        <button type="button" onclick="window.location.href='Products.php'"
                                class="btn btn-success flex-fill add-to-cart-btn d-flex align-items-center justify-content-center gap-1"
                                data-product-id="<?= $product['id'] ?>">
                            <span class="material-symbols-outlined add-icon">add_shopping_cart</span>
                            <span class="btn-text"> Order Now</span>
                        </button>

                        <!-- Details -->
                        <a href="Detail.php"
                           class="btn btn-outline-secondary flex-fill d-flex align-items-center justify-content-center gap-1">
                            <span class="material-symbols-outlined">visibility</span>
                            <span>Details</span>
                        </a>
                    </div>
                </div>
            </div>

        </article>
    </div>
    <?php endforeach; ?>
</div>
<?php else: ?>
<div class="text-center py-5 text-muted">
    <p class="fs-4">No plants found.</p>
</div>
<?php endif; ?>
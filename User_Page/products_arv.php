<?php 
if (!empty($newArrivals)): ?>
<div class="row g-4 mb-8">

<?php foreach ($newArrivals as $product): ?>
<div class="col-md-6">
    <div class="card h-80 shadow-sm border-0 product-card">

        <div class="row g-0 h-100">
            <div class="position-absolute top-0 start-0 m-2 z-3">
            <span class="badge bg-success text-white">
            <i class="bi bi-stars"></i> New Arrival
            </span>
            </div>
            <!-- Image -->
            <div class="col-md-4 overflow-hidden">
                <img src="../plant_admin/uploads/<?= htmlspecialchars($product['image'] ?? 'placeholder.png') ?>"
                     alt="<?= htmlspecialchars($product['name']) ?>"
                     class="img-fluid h-100 w-100 object-fit-cover product-img"
                     loading="lazy">
            </div>

            <!-- Content -->
            <div class="col-md-8 d-flex flex-column h-100 ">
                <div class="card-body d-flex flex-column h-100">
                    <h5 class="card-title fw-bold">
                        <?= htmlspecialchars($product['name']) ?>
                    </h5>

                    <p class="card-text text-muted small mb-3">
                        <?= htmlspecialchars($product['description']) ?>
                    </p>

                    <div class="mt-auto">
                        <p class="fw-bold text-success fs-5 mb-3">
                            $<?= number_format($product['price'] , 2) ?>
                        </p>

                        <!-- Buttons -->
                        <div class="d-flex gap-2 justify-content-center">
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button class="btn btn-primary me-md-2" type="button"
                                onclick="window.location.href='/PLANT_PROJECT/User_Page/Products.php?id=<?= $product['id'] ?>&action=add_to_cart'">
                                <span class="material-symbols-outlined align-middle">add_shopping_cart</span>
                                Add</button>
                            <button class="btn btn-primary" type="button" onclick="location.href='/PLANT_PROJECT/User_Page/product_detail.php?id=<?= $product['id'] ?>'">
                                <span class="material-symbols-outlined align-middle">visibility</span>
                                Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<?php endforeach; ?>

</div>
<?php else: ?>
<div class="text-center py-5 text-muted">
    <p class="fs-4">No plants found.</p>
</div>
<?php endif; ?>
<style>
    .product-card {
        border-radius: 0.5rem;
    }
    .product-img {
        transition: transform 0.3s ease;
    }
    .product-img:hover {
        transform: scale(1.05);
    }
</style>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">

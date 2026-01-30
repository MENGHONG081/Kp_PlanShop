<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<?php if (!empty($products)): ?>
<div class="card-group">

<?php foreach ($products as $product): ?>
  <div class="card">
    <img src="../plant_admin/uploads/<?= htmlspecialchars($product['image'] ?? 'placeholder.png') ?>"
         class="card-img-top"
         alt="<?= htmlspecialchars($product['name']) ?>">

    <div class="card-body">
      <h5 class="card-title">
        <?= htmlspecialchars($product['name']) ?>
      </h5>

      <p class="card-text">
        <?= htmlspecialchars($product['description']) ?>
      </p>
        </small>
      </p>
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
.card-group .card {
    margin: 10px;
    border: none;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s;
}
.card-group .card:hover {
    transform: translateY(-5px);
}
.card-group .card-img-top {
    height: 200px;
    object-fit: cover;
}
.card-group .card-body {
    text-align: center;
}
</style>
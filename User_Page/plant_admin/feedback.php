<?php
require __DIR__ . '/auth.php';

// OPTIONAL: Admin protection
// session_start();
// if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
//     die('<div class="alert alert-danger m-5">Access denied. Admins only.</div>');
// }

/* =========================
   TOGGLE VISIBILITY
========================= */
if (isset($_GET['toggle'])) {
    $id = (int) $_GET['toggle'];

    $stmt = $pdo->prepare("SELECT visible FROM customer_feedback WHERE id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $newVisible = $row['visible'] ? 0 : 1;
        $update = $pdo->prepare(
            "UPDATE customer_feedback SET visible = ? WHERE id = ?"
        );
        $update->execute([$newVisible, $id]);
    }

header('Location: ' . BASE_URL . '/feedback.php');
exit;
}

/* =========================
   DELETE FEEDBACK
========================= */
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];

    $del = $pdo->prepare("DELETE FROM customer_feedback WHERE id = ?");
    $del->execute([$id]);

header('Location: ' . BASE_URL . '/feedback.php');
exit;

}

/* =========================
   FETCH FEEDBACK (FIXED)
========================= */
$stmt = $pdo->prepare("
    SELECT 
        f.id,
        f.comments,
        f.rating,
        f.visible,
        f.submitted_at,
        u.email,
        u.fullname
    FROM customer_feedback f
    LEFT JOIN users u ON f.user_id = u.id
    ORDER BY f.submitted_at DESC
");
$stmt->execute();
$feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Feedback Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
      .avatar {
        width: 40px;
        height: 40px;
        background-color: #6c757d;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.2rem;
      }
      .feedback-card {
        transition: transform 0.2s;
      }
      .feedback-card:hover {
        transform: scale(1.02);
      }
    </style>
</head>
<body class="bg-light">
<div class="container mt-5">
  <h2 class="mb-4 fw-bold">🌱 Customer Feedback</h2>

  <?php if (empty($feedbacks)): ?>
    <div class="alert alert-info d-flex align-items-center gap-2">
      <span class="fs-4">🤷‍♂️</span> No feedback yet—be the first!
    </div>
  <?php else: ?>
    <div class="row g-4">
      <?php foreach ($feedbacks as $fb): ?>
        <div class="col-md-6 col-lg-4">
          <div class="card h-100 shadow-sm feedback-card">

            <!-- header -->
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
              <div class="d-flex align-items-center gap-2">
                <span class="avatar"><?= mb_substr($fb['fullname'] ?? 'G', 0, 1) ?></span>
                <div>
                  <div class="fw-bold"><?= htmlspecialchars($fb['fullname'] ?? 'Guest') ?></div>
                  <small class="text-muted"><?= htmlspecialchars($fb['email'] ?? '') ?></small>
                </div>
              </div>
              <div class="text-warning fs-5">
                <?= str_repeat('⭐', (int) ($fb['rating'] ?? 0)) ?>
              </div>
            </div>

            <!-- body -->
            <div class="card-body">
              <p class="mb-0"><?= nl2br(htmlspecialchars($fb['comments'])) ?></p>
            </div>

            <!-- footer -->
            <div class="card-footer bg-white d-flex justify-content-between align-items-center">
              <small class="text-muted">
                🕒 <?= date('M d, Y g:i A', strtotime($fb['submitted_at'])) ?>
              </small>
              <div class="btn-group btn-group-sm">
                <a href="?toggle=<?= $fb['id'] ?>" class="btn btn-outline-warning" title="Toggle visibility">
                  🔒 view
                </a>
                <a href="?delete=<?= $fb['id'] ?>" class="btn btn-outline-danger" title="Delete"
                   onclick="return confirm('Delete this feedback?')">
                  🗑️ Cancle
                </a>
              </div>
            </div>

          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
</body>
</html>

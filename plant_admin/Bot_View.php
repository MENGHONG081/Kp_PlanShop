<?php
declare(strict_types=1);
require __DIR__ . '/config.php';

$rows = $pdo->query("SELECT * FROM payments ORDER BY id DESC LIMIT 200")->fetchAll();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Payments Admin</title>
</head>
<body class="bg-gray-50">
  <div class="max-w-6xl mx-auto p-6">
    <div class="flex items-center justify-between mb-4">
      <h1 class="text-2xl font-bold">Payments Admin</h1>
      <div class="text-sm text-gray-600">Showing last <?= count($rows) ?> records</div>
    </div>

    <div class="bg-white rounded-2xl shadow overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-gray-100 text-gray-700">
          <tr>
            <th class="text-left p-3">ID</th>
            <th class="text-left p-3">Order</th>
            <th class="text-left p-3">Amount</th>
            <th class="text-left p-3">Status</th>
            <th class="text-left p-3">Txn Ref</th>
            <th class="text-left p-3">Date</th>
            <th class="text-left p-3">Reason</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($rows as $r): ?>
          <tr class="border-t">
            <td class="p-3"><?= htmlspecialchars((string)$r['payment_id']) ?></td>
            <td class="p-3"><?= htmlspecialchars((string)$r['order_id']) ?></td>
            <td class="p-3"><?= htmlspecialchars((string)$r['amount']) ?></td>
            <td class="p-3">
              <?php if ($r['payment_status'] === 'SUCCESS'): ?>
                <span class="px-2 py-1 rounded-full bg-green-100 text-green-700">SUCCESS</span>
              <?php else: ?>
                <span class="px-2 py-1 rounded-full bg-red-100 text-red-700">FAILED</span>
              <?php endif; ?>
            </td>
            <td class="p-3"><?= htmlspecialchars((string)$r['transaction_ref']) ?></td>
            <td class="p-3"><?= htmlspecialchars((string)$r['payment_date']) ?></td>
            <td class="p-3 max-w-md">
              <div class="text-gray-600"><?= htmlspecialchars((string)($r['failure_reason'] ?? '')) ?></div>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>

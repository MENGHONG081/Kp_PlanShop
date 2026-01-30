<?php
session_start();
$failReasons = $_SESSION['fail_reasons'] ?? [];
$ai          = $_SESSION['ai_result'] ?? [];
unset($_SESSION['fail_reasons'], $_SESSION['ai_result']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Verification Failed</title>
    <style>
        body { font-family: sans-serif; background:#f8f9fa; padding:40px; }
        .box { background:#fff; padding:25px; border-radius:10px; max-width:700px; margin:auto; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .error { color:#dc3545; }
        pre { background:#111; color:#0f0; padding:15px; border-radius:8px; overflow: auto; }
        .btn { display:inline-block; padding:10px 20px; background:#007bff; color:#fff; text-decoration:none; border-radius:5px; }
    </style>
</head>
<body>
    <div class="box">
        <h2 class="error">Verification Failed</h2>
        <h4>Issues Found:</h4>
        <ul>
            <?php foreach ($failReasons as $r): ?>
                <li><?= htmlspecialchars($r) ?></li>
            <?php endforeach; ?>
        </ul>

        <h4>Gemini AI Data / API Response:</h4>
        <pre><?= json_encode($ai, JSON_PRETTY_PRINT) ?></pre>

        <p>A technical log has been saved to <code>gemini_tell.json</code>.</p>
        <a href="payment.php" class="btn">← Try Again</a>
    </div>
</body>
</html>
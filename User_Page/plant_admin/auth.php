<?php
require __DIR__ . '/config.php';   // session_start() នៅទីនេះ
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
?>
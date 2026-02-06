<?php
require __DIR__ . '/../config.php';   // session_start() នៅទីនេះ
if (!isset($_SESSION['admin_id'])) {
    header('Location: ' . BASE_URL . '/plant_admin/login.php');
    exit;
}
?>
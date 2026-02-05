<?php
require __DIR__ . '/config.php';
if(!isset($_SESSION['admin_id'])){ header('Location: plant_admin/index.php'); exit(); }
?>
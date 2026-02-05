<?php
include 'plant_admin/config.php';
if(!isset($_SESSION['admin_id'])){ header('Location: plant_admin/index.php'); exit(); }
?>
<?php
require __DIR__ . '/config.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

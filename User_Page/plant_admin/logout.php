<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) { session_start(); }
session_unset();
session_destroy();
header('Location: ../login.php'); // redirect to login page
exit();
?>
<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Remove all session data
session_unset();
session_destroy();

// Redirect to public home page
header("Location: index.php");
exit();
?>

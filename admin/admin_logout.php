<?php
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to the admin login page (or any other page you prefer)
header("Location: admin_login.php");
exit();
?>
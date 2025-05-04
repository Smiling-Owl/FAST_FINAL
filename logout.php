<?php
// Start the session
session_start();

// Unset all session variables to log the user out
session_unset();

// Destroy the session
session_destroy();

// Redirect to the login page or home page
header("Location: index.php");
exit;
?>

<?php
$password = "LavenderSunflower2285"; // Replace with the actual password you chose
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
echo "Hashed Password: " . $hashedPassword . "\n";
?>
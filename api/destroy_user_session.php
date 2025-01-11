<?php
session_start();
require_once('config.php');

header('Content-Type: application/json');

// Destroy the session
session_unset();  // Remove session variables
session_destroy();  // Destroy the session

print_response(true, "Logged out successfully.");
?>

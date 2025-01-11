<?php
session_start();

require_once('config.php');

header('Content-Type: application/json');

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $data = [
        'user_id' => $_SESSION['user_id']
    ];

    print_response(true, "User session retrieved successfully.", $data);
} else {
    print_response(false, "No active session found.");
}

?>

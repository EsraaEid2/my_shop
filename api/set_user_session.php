<?php
session_start();
require_once('config.php');

header('Content-Type: application/json');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Decode the input JSON data
    $data = json_decode(file_get_contents('php://input'), true);

    // Extract user details from the JSON data
    $id = $data['id'] ?? null;

    // Validate input
    if (empty($id)) {
        print_response(false, "Email and password are required.");
        exit();
    }

    try {
    
        // Set session variables
        $_SESSION['user_id'] = $id;

        print_response(true, "User session created successfully.");

    } catch (Exception $e) {
        print_response(false, "Error: " . $e->getMessage());
    }
} else {
    print_response(false, "Invalid request method.");
}

?>

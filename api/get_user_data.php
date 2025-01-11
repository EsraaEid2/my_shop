<?php

require_once('config.php');

header('Content-Type: application/json');

// Check if the request method is GET
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    // Get the user ID from the query string
    $id = $_GET['id'] ?? null;
    error_log("Received ID: " . $id); 

    // Validate input
    if (empty($id) || !is_numeric($id)) {
        print_response(false, "Invalid profile ID.");
        exit();
    }

    try {
        // Prepare the SQL statement to include password (if needed)
        $stmt = $conn->prepare("SELECT id, first_name, last_name, email, password, status FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Fetch user details
            $user = $result->fetch_assoc();
            
            // Return the user profile (excluding password if not needed)
            unset($user['password']);  // Don't expose the password unless necessary
            print_response(true, "User profile retrieved successfully.", $user);
        } else {
            // User not found
            print_response(false, "User not found.");
        }
    } catch (Exception $e) {
        // Handle any exceptions and errors
        print_response(false, "Error: " . $e->getMessage());
    }
} else {
    // Handle invalid request method
    print_response(false, "Invalid request method.");
}

?>

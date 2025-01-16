<?php

require_once('config.php');

header('Content-Type: application/json');

// Check if the request method is GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    // Get the user ID from the query string
    $id = $_GET['id'] ?? null;

    // Validate input
    if (empty($id) || !is_numeric($id)) {
        print_response(false, "Invalid profile ID.");
        exit();
    }

    try {
        // Prepare the SQL statement to fetch user data
        $stmt = $conn->prepare("SELECT id, first_name, last_name, email, profile_image, status FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Fetch user details
            $user = $result->fetch_assoc();
            
        // Set default profile image if not available
        $user['profile_image'] = !empty($user['profile_image']) ? 'data:image/png;base64,' . $user['profile_image'] : 'assets/img/user_images/default_profile.png';

            
            // Return the user profile
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
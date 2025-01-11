<?php

require_once('config.php');
header('Content-Type: application/json');

// Ensure the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    print_response(false, "Invalid request method.");
}

// Decode the input JSON
$data = json_decode(file_get_contents('php://input'), true);

// Validate the input
$user_id = $data['id'] ?? null; // Fetch user ID from the request body
error_log("User ID: " . $user_id); // Log the User ID for debugging
$first_name = $data['first_name'] ?? null;
$last_name = $data['last_name'] ?? null;
$email = $data['email'] ?? null;

if (empty($user_id) || !is_numeric($user_id)) {
    print_response(false, "Invalid or missing user ID.");
}

if (empty($first_name) || empty($last_name) || empty($email)) {
    print_response(false, "All fields (first_name, last_name, email) are required.");
}

try {
    // Prepare the SQL statement
    $stmt = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ? WHERE id = ?");
    $stmt->bind_param("sssi", $first_name, $last_name, $email, $user_id);

    if ($stmt->execute()) {
        // Check if any rows were affected
        if ($stmt->affected_rows > 0) {
            print_response(true, "User profile updated successfully.");
        } else {
            print_response(false, "No changes made or user not found.");
        }
    } else {
        print_response(false, "Failed to update user profile.");
    }
} catch (Exception $e) {
    // Catch any exceptions
    print_response(false, "An error occurred: " . $e->getMessage());
}
?>

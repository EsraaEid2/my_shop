<?php

require_once('config.php');

header('Content-Type: application/json');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Get input data from POST
    $data = json_decode(file_get_contents('php://input'), true); // Read JSON data
    $id = $data['id'] ?? null;

    // Validate input
    if (empty($id) || !is_numeric($id)) {
        print_response(false, "Invalid user ID.");
        exit();
    }

    try {
        // Prepare the SQL statement to update status to 0
        $stmt = $conn->prepare("UPDATE users SET status = 0 WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            // Check if any rows were updated
            if ($stmt->affected_rows > 0) {
                print_response(true, "User deleted successfully (soft delete).");
            } else {
                print_response(false, "User not found or already deleted.");
            }
        } else {
            print_response(false, "Failed to delete user.");
        }
    } catch (Exception $e) {
        // Handle exceptions
        print_response(false, "Error: " . $e->getMessage());
    }
} else {
    // Handle invalid request method
    print_response(false, "Invalid request method.");
}

?>
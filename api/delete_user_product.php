<?php

require_once('config.php');

header('Content-Type: application/json');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Get input data from POST
    $data = json_decode(file_get_contents('php://input'), true); // Read JSON data
    $productId = $data['id'] ?? null;

    // Validate input
    if (empty($productId) || !is_numeric($productId)) {
        print_response(false, "Invalid product ID.");
        exit();
    }

    try {
        // Prepare the SQL statement to update is_deleted to 1
        $stmt = $conn->prepare("UPDATE products SET is_deleted = 1 WHERE id = ?");
        $stmt->bind_param("i", $productId);

        if ($stmt->execute()) {
            // Check if any rows were updated
            if ($stmt->affected_rows > 0) {
                print_response(true, "Product deleted successfully (soft delete).");
            } else {
                print_response(false, "Product not found or already deleted.");
            }
        } else {
            print_response(false, "Failed to delete product.");
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
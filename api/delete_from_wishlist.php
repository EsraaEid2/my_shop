<?php

require_once('config.php');

header('Content-Type: application/json');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Get input data from POST
    $data = json_decode(file_get_contents('php://input'), true); // Read JSON data
    $productId = $data['product_id'] ?? null;
    $userId = $data['user_id'] ?? null;

    // Validate input
    if (empty($productId) || !is_numeric($productId) || empty($userId) || !is_numeric($userId)) {
        print_response(false, "Invalid product ID or user ID.");
        exit();
    }

    try {
        // Prepare the SQL statement to update status to 0 (soft delete)
        $stmt = $conn->prepare("UPDATE wishlists SET status = 0 WHERE product_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $productId, $userId);

        if ($stmt->execute()) {
            // Check if any rows were updated
            if ($stmt->affected_rows > 0) {
                print_response(true, "Product removed from wishlist successfully.");
            } else {
                print_response(false, "Product not found in wishlist or already removed.");
            }
        } else {
            print_response(false, "Failed to remove product from wishlist.");
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

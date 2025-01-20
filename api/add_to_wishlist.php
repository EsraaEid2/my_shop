<?php 
require_once('config.php');

header('Content-Type: application/json');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Get the raw POST data and decode it
    $data = json_decode(file_get_contents('php://input'), true); // Read JSON data

    // Validate the data
    $productId = $data['product_id'] ?? null;
    $userId = $data['user_id'] ?? null;
        // Log the received data (for debugging)
        error_log("Received product_id: $productId, user_id: $userId");

    if (!$productId || !$userId) {
        print_response(false, 'User ID and Product ID are required');
        exit();
    }

    // Log the received data (for debugging)
    error_log("Received product_id: $productId, user_id: $userId");

    // Validate product ID
    if (empty($productId) || !is_numeric($productId)) {
        print_response(false, 'Invalid product ID');
        exit();
    }
    // Validate user ID
    if (empty($userId) || !is_numeric($userId)) {
        print_response(false, 'Invalid user ID');
        exit();
    }

    try {
        // Check if the product exists and is not deleted
        $stmt = $conn->prepare("SELECT * FROM products WHERE id = ? AND is_deleted = 0");
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            print_response(false, 'Product not found or is deleted');
            exit();
        }

        // Check if the product is already in the user's wishlist
        $stmt = $conn->prepare("SELECT * FROM wishlists WHERE user_id = ? AND product_id = ? AND status = 1");
        $stmt->bind_param("ii", $userId, $productId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            print_response(false, 'Product already in wishlist');
            exit();
        }

        // Add the product to the wishlist
        $stmt = $conn->prepare("INSERT INTO wishlists (user_id, product_id, created_at) VALUES (?, ?, NOW())");
        $stmt->bind_param("ii", $userId, $productId);

        if ($stmt->execute()) {
            print_response(true, 'Product added to wishlist successfully');
        } else {
            print_response(false, 'Failed to add product to wishlist');
        }
    } catch (Exception $e) {
        // Handle any exceptions
        print_response(false, 'Error: ' . $e->getMessage());
    }
} else {
    // Return error if method is not POST
    print_response(false, 'Invalid request method');
}

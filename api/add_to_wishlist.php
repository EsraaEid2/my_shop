<?php 
require_once('config.php');

// Start the session to access session variables (e.g., user_id)
session_start();

header('Content-Type: application/json');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Get the raw POST data and decode it
    $data = json_decode(file_get_contents('php://input'), true); // Read JSON data

    // Validate the data
    $productId = $data['product_id'] ?? null;

    // Log the received data (for debugging)
    error_log("Received product_id: $productId");

    if (!$productId) {
        print_response(false, 'Product ID is required');
        exit();
    }

    // Validate product ID
    if (empty($productId) || !is_numeric($productId)) {
        print_response(false, 'Invalid product ID');
        exit();
    }

    // Get user ID from session (this should be handled in the front-end as well)
    $userId = $_SESSION['user_id'] ?? null;

    // Log the received data (for debugging)
    error_log("User ID from session: " . $userId); // Corrected the logging statement

    if (!$userId) {
        print_response(false, 'User must be logged in to add a product to the wishlist');
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
?>
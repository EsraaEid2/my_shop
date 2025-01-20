<?php

require_once('config.php');

header('Content-Type: application/json');

// Check if the request method is GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $id = $_GET['id'] ?? null;
    error_log("Received user ID: " . $id); // Log the received ID

    // Validate input
    if (empty($id) || !is_numeric($id)) {
        print_response(false, "Invalid user ID.");
        exit();
    }

    try {
        // Prepare the SQL statement to fetch products from the wishlist
        $stmt = $conn->prepare(
            "SELECT p.id, p.title, p.description, p.price, p.stock_quantity, 
            CONCAT('assets/img/product_images/', p.image_url) AS image_url, 
            p.is_deleted, w.created_at 
            FROM wishlists w
            JOIN products p ON w.product_id = p.id
            WHERE w.user_id = ? AND w.status = 1 AND p.is_deleted = 0"
        );
        $stmt->bind_param("i", $id); // Bind the user ID
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Fetch wishlist items into an array
            $products = [];
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }

            // Return the wishlist products
            print_response(true, "Wishlist retrieved successfully.", $products);
        } else {
            // No wishlist items found
            print_response(false, "No products in wishlist.");
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

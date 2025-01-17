<?php
require_once 'config.php'; // Include database configuration file

header('Content-Type: application/json');

// Check if product_id is passed as a query parameter
if (isset($_GET['product_id'])) {
    $productId = $_GET['product_id'];
    error_log("product_id : ". $productId);
    // Validate the product_id
    if (empty($productId) || !is_numeric($productId)) {
        print_response(false, 'Invalid product ID');
        exit();
    }

    try {
        // Prepare the SQL statement to fetch product details by product_id
        $stmt = $conn->prepare("SELECT * FROM products WHERE id = ? AND is_deleted = 0");
        $stmt->bind_param("i", $productId); // Bind the product_id as an integer
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Fetch product details
            $product = $result->fetch_assoc();

            // Return the product data as JSON
            print_response(true, 'Product retrieved successfully', $product);
        } else {
            // No product found
            print_response(false, 'Product not found');
        }
    } catch (Exception $e) {
        // Handle any exceptions and errors
        print_response(false, 'Error: ' . $e->getMessage());
    }
} else {
    // Product ID is required
    print_response(false, 'Product ID is required');
}
 
?>

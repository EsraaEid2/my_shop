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
        // Prepare the SQL statement to fetch products
        $stmt = $conn->prepare("SELECT id, title, description, price, stock_quantity, CONCAT('assets/img/product_images/', image_url) AS image_url, is_deleted FROM products WHERE user_id = ? AND is_deleted = 0");
        $stmt->bind_param("i", $id); // Bind the user ID
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Fetch products into an array
            $products = [];
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
            
            // Return the products
            print_response(true, "Products retrieved successfully.", $products);
        } else {
            // No products found
            print_response(false, "No products available.");
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

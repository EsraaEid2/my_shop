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
$product_id = $data['id'] ?? null; // Fetch product ID from the request body
$title = $data['title'] ?? null;
$description = $data['description'] ?? null;
$price = $data['price'] ?? null;
$stock_quantity = $data['stock_quantity'] ?? null;

if (empty($product_id) || !is_numeric($product_id)) {
    print_response(false, "Invalid or missing product ID.");
}

if (empty($title) || empty($description) || empty($price) || empty($stock_quantity)) {
    print_response(false, "All fields (title, description, price, stock_quantity) are required.");
}

// Initialize image path variable
$image_path = null;

// Handle the image upload if provided
if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
    // Validate image file (type and size)
    $allowed_extensions = ['image/jpeg', 'image/png'];
    $max_size = 2 * 1024 * 1024; // 2MB max

    $file_type = $_FILES['product_image']['type'];
    $file_size = $_FILES['product_image']['size'];

    if (!in_array($file_type, $allowed_extensions)) {
        print_response(false, "Invalid image format. Allowed formats: JPG, PNG.");
    }

    if ($file_size > $max_size) {
        print_response(false, "File size is too large. Maximum allowed size is 2MB.");
    }

    // Convert the image to Base64
    $file_path = $_FILES['product_image']['tmp_name'];
    $image_base64 = base64_encode(file_get_contents($file_path));

    // Set the image path for database update
    $image_path = $image_base64;
}

try {
    // Prepare the SQL statement for updating product data
    $stmt = $conn->prepare("UPDATE products SET title = ?, description = ?, price = ?, stock_quantity = ? " . ($image_path ? ", image_url = ?" : "") . " WHERE id = ?");
    
    if ($image_path) {
        $stmt->bind_param("ssdisi", $title, $description, $price, $stock_quantity, $image_path, $product_id);
    } else {
        $stmt->bind_param("ssdis", $title, $description, $price, $stock_quantity, $product_id);
    }

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            // Fetch updated product data
            $result = $conn->query("SELECT id, title, description, price, stock_quantity, image_url FROM products WHERE id = $product_id");
            $product = $result->fetch_assoc();
            print_response(true, "Product updated successfully.", $product);
        } else {
            print_response(false, "No changes made or product not found.");
        }
    }
} catch (Exception $e) {
    // Catch any exceptions
    print_response(false, "An error occurred: " . $e->getMessage());
}

?>

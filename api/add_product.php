<?php
require_once('config.php');

header('Content-Type: application/json');

// Ensure no stray output
ob_start();

// Helper function to decode and save a Base64 image
function saveBase64Image($base64Image, $uploadPath) {
    $imageData = base64_decode($base64Image);
    $uniqueName = uniqid('product_', true) . '.png';
    $filePath = $uploadPath . $uniqueName;

    if (file_put_contents($filePath, $imageData)) {
        return $uniqueName; // Return the saved file name
    }
    return false; // Return false on failure
}

// Use absolute path for image storage
$uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/my_shop/assets/img/product_images/';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Parse JSON input
    $data = json_decode(file_get_contents('php://input'), true);

    // Extract the user_id from the received data (passed from the JavaScript API call)
    $user_id = $data['user_id'] ?? null;

    // Validate if user_id is provided
    if (empty($user_id)) {
        print_response(false, "No user_id provided. You must be logged in.");
        exit();
    }

    $title = $data['title'] ?? null;
    $description = $data['description'] ?? null;
    $price = $data['price'] ?? null;
    $stock_quantity = $data['stock_quantity'] ?? null;
    $image_url = $data['image_url'] ?? null;

    // Validate input
    if (empty($title) || empty($description) || empty($price) || empty($stock_quantity) || empty($image_url)) {
        print_response(false, "All fields are required.");
        exit();
    }

    if (!is_numeric($price) || $price <= 0 || !is_numeric($stock_quantity) || $stock_quantity < 1) {
        print_response(false, "Price must be a positive number and stock quantity must be at least 1.");
        exit();
    }

    // Save the image
    $savedImageName = saveBase64Image($image_url, $uploadDir);
    if (!$savedImageName) {
        print_response(false, "Failed to save the product image.");
        exit();
    }

    // Insert product into the database, including user_id
    $stmt = $conn->prepare("INSERT INTO products (title, description, price, stock_quantity, image_url, user_id, is_deleted) VALUES (?, ?, ?, ?, ?, ?, 0)");
    $stmt->bind_param("ssdssi", $title, $description, $price, $stock_quantity, $savedImageName, $user_id);

    if ($stmt->execute()) {
        $product_id = $stmt->insert_id;

        print_response(true, "Product added successfully.", [
            "id" => $product_id,
            "title" => $title,
            "description" => $description,
            "price" => $price,
            "stock_quantity" => $stock_quantity,
            "image_url" => $uploadDir . $savedImageName // Return full image path
        ]);
    } else {
        print_response(false, "Failed to add product.");
    }

    $stmt->close();
} else {
    print_response(false, "Invalid request method.");
}

ob_end_flush();
?>

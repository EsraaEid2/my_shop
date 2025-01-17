<?php
require_once('config.php');

header('Content-Type: application/json');

// Ensure no stray output
ob_start();
error_log("esrsss");

// Helper function to decode and save a Base64 image
function saveBase64Image($base64Image, $uploadPath) {
    // Check if the base64 image string has the correct format
    if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)) {
        $base64Image = substr($base64Image, strpos($base64Image, ',') + 1);
    }

    $imageData = base64_decode($base64Image);
    if ($imageData === false) {
        return false; // Return false if Base64 is invalid
    }

    // Generate a unique name for the image
    $uniqueName = uniqid('product_', true) . '.png'; // You can adjust the extension if needed
    $filePath = $uploadPath . $uniqueName;

    // Save the image to the server
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

    // Extract product details from the data
    $title = $data['title'] ?? null;
    $description = $data['description'] ?? null;
    $price = $data['price'] ?? null;
    $stock_quantity = $data['stock_quantity'] ?? null;
    $image_url = $data['image_url'] ?? null;
    error_log("image url" . $image_url);
    // Validate input fields
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
  var_dump($savedImageName);

    // Insert product into the database, including user_id
    $stmt = $conn->prepare("INSERT INTO products (title, description, price, stock_quantity, image_url, user_id, is_deleted) VALUES (?, ?, ?, ?, ?, ?, 0)");
    $stmt->bind_param("ssdssi", $title, $description, $price, $stock_quantity, $savedImageName, $user_id);

    if ($stmt->execute()) {
        $product_id = $stmt->insert_id;

        // Return a response with product details and the image URL
        print_response(true, "Product added successfully.", [
            "id" => $product_id,
            "title" => $title,
            "description" => $description,
            "price" => $price,
            "stock_quantity" => $stock_quantity,
            "image_url" => '/my_shop/assets/img/product_images/' . $savedImageName // Relative URL to access image
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

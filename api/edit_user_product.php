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
// Validate and sanitize inputs
$title = htmlspecialchars($data['title'], ENT_QUOTES, 'UTF-8');
$description = htmlspecialchars($data['description'], ENT_QUOTES, 'UTF-8');
$price = filter_var($data['price'], FILTER_VALIDATE_FLOAT);
$stock_quantity = filter_var($data['stock_quantity'], FILTER_VALIDATE_INT);

if ($price === false || $stock_quantity === false) {
    print_response(false, "Invalid price or stock quantity.");
}

// Image validation
if (!empty($image_base64)) {
    $decoded_image = base64_decode($image_base64);
    if ($decoded_image === false) {
        print_response(false, "Invalid Base64 image string.");
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_buffer($finfo, $decoded_image);
    finfo_close($finfo);

    if (!in_array($mime_type, ['image/jpeg', 'image/png'])) {
        print_response(false, "Invalid image format.");
    }

    $image_name = 'product_' . uniqid() . '.png';
    $image_path = 'assets/img/product_images/' . $image_name;

    if (file_put_contents($image_path, $decoded_image) === false) {
        print_response(false, "Failed to save the uploaded image.");
    }
}


try {
    // Prepare the SQL statement for updating product data
    $stmt = $conn->prepare(
        "UPDATE products SET title = ?, description = ?, price = ?, stock_quantity = ? " . 
        ($image_path ? ", image_url = ?" : "") . " WHERE id = ?"
    );
    
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
            $product['image_url'] = 'http://localhost/my_shop/assets/img/product_images' . $product['image_url']; // Append base URL to the image path
            print_response(true, "Product updated successfully.", $product);
        } else {
            print_response(false, "No changes made or product not found.");
        }
    } else {
        print_response(false, "Failed to update the product.");
    }
} catch (Exception $e) {
    // Catch any exceptions
    print_response(false, "An error occurred: " . $e->getMessage());
}

?>

<?php
require_once('config.php');
header('Content-Type: application/json');

// Ensure the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    print_response(false, "Invalid request method.");
    exit;
}

// Decode the input JSON
$data = json_decode(file_get_contents('php://input'), true);

// Validate and sanitize inputs
$product_id = $data['id'] ?? null;
$title = htmlspecialchars($data['title'] ?? '', ENT_QUOTES, 'UTF-8');
$description = htmlspecialchars($data['description'] ?? '', ENT_QUOTES, 'UTF-8');
$price = filter_var($data['price'], FILTER_VALIDATE_FLOAT);
$stock_quantity = filter_var($data['stock_quantity'], FILTER_VALIDATE_INT);
$image_base64 = $data['image_base64'] ?? null; // Fetch Base64 image if provided

if (!$product_id || !$title || !$description || $price === false || $stock_quantity === false) {
    print_response(false, "Invalid input. All fields are required.");
    exit;
}

// Image processing
$image_path = null; // Default to null
if ($image_base64) {
    $decoded_image = base64_decode($image_base64);
    if ($decoded_image === false) {
        print_response(false, "Invalid Base64 image string.");
        exit;
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_buffer($finfo, $decoded_image);
    finfo_close($finfo);

    if (!in_array($mime_type, ['image/jpeg', 'image/png'])) {
        print_response(false, "Invalid image format. Only JPEG and PNG are allowed.");
        exit;
    }

    $extension = $mime_type === 'image/jpeg' ? '.jpg' : '.png';
    $image_name = 'product_' . uniqid() . $extension;

    // Use the absolute path for saving the image
    $image_folder = $_SERVER['DOCUMENT_ROOT'] . '/my_shop/assets/img/product_images/';
    if (!is_dir($image_folder)) {
        mkdir($image_folder, 0755, true); // Create the directory if it doesn't exist
    }

    $image_path = $image_folder . $image_name;

    if (file_put_contents($image_path, $decoded_image) === false) {
        print_response(false, "Failed to save the uploaded image.");
        exit;
    }
}

try {
    // Prepare the SQL statement for updating product data
    $query = "UPDATE products SET title = ?, description = ?, price = ?, stock_quantity = ?";
    if ($image_path) {
        $query .= ", image_url = ?";
    }
    $query .= " WHERE id = ?";

    $stmt = $conn->prepare($query);
    
    if ($image_path) {
        $stmt->bind_param("ssdisi", $title, $description, $price, $stock_quantity, $image_name, $product_id);
    } else {
        $stmt->bind_param("ssdis", $title, $description, $price, $stock_quantity, $product_id);
    }

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            // Fetch updated product data
            $result = $conn->query("SELECT id, title, description, price, stock_quantity, image_url FROM products WHERE id = $product_id");
            $product = $result->fetch_assoc();
            if ($product['image_url']) {
                $product['image_url'] = 'http://localhost/my_shop/assets/img/product_images/' . $product['image_url'];
            }
            print_response(true, "Product updated successfully.", $product);
        } else {
            print_response(false, "No changes made or product not found.");
        }
    } else {
        print_response(false, "Failed to update the product.");
    }
} catch (Exception $e) {
    print_response(false, "An error occurred: " . $e->getMessage());
}

?>

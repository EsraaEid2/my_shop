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
$user_id = $data['user_id'] ?? null; // Fetch user ID from the request body
$first_name = $data['first_name'] ?? null;
$last_name = $data['last_name'] ?? null;
$email = $data['email'] ?? null;
$image_base64 = $data['profile_image'] ?? null; // The Base64 encoded image

if (empty($user_id) || !is_numeric($user_id)) {
    print_response(false, "Invalid or missing user ID.");
}

if (empty($first_name) || empty($last_name) || empty($email)) {
    print_response(false, "All fields (first_name, last_name, email) are required.");
}

// Initialize image path variable
$image_path = null; // Default to null
if ($image_base64) {
    // Decode the Base64 image
    $decoded_image = base64_decode($image_base64);
    if ($decoded_image === false) {
        print_response(false, "Invalid Base64 image string.");
        exit;
    }

    // Check image size (Max 5MB)
    $maxSize = 5 * 1024 * 1024; // 5MB
    if (strlen($decoded_image) > $maxSize) {
        print_response(false, "Image exceeds the maximum allowed size of 5MB.");
        exit;
    }

    // Get MIME type of the image
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_buffer($finfo, $decoded_image);
    finfo_close($finfo);

    // Validate MIME type to ensure it's either JPEG or PNG
    if (!in_array($mime_type, ['image/jpeg', 'image/png'])) {
        print_response(false, "Invalid image format. Only JPEG and PNG are allowed.");
        exit;
    }

    // Set the file extension based on MIME type
    $extension = $mime_type === 'image/jpeg' ? '.jpg' : '.png';
    $image_name = 'profile_' . uniqid() . $extension;

    // Use the absolute path for saving the image on the server
    $image_folder = $_SERVER['DOCUMENT_ROOT'] . '/my_shop/assets/img/user_images/';
    if (!is_dir($image_folder)) {
        mkdir($image_folder, 0755, true); // Create the directory if it doesn't exist
    }

    // Set the relative image path
    $image_url = '/my_shop/assets/img/user_images/' . $image_name;

    // Save the image to the directory
    if (file_put_contents($image_folder . $image_name, $decoded_image) === false) {
        print_response(false, "Failed to save the uploaded image.");
        exit;
    }
}

try {
    // Prepare the SQL statement for updating user data
    $sql = "UPDATE users SET first_name = ?, last_name = ?, email = ?";
    if ($image_url) {
        $sql .= ", profile_image = ?";
    }
    $sql .= " WHERE id = ?";

    $stmt = $conn->prepare($sql);
    if ($image_url) {
        $stmt->bind_param("ssssi", $first_name, $last_name, $email, $image_url, $user_id); // Store relative URL
    } else {
        $stmt->bind_param("sssi", $first_name, $last_name, $email, $user_id);
    }

    // Execute the query
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            // Fetch the updated user data
            $result = $conn->query("SELECT id, first_name, last_name, email, profile_image FROM users WHERE id = $user_id");
            $user = $result->fetch_assoc();
            print_response(true, "User profile updated successfully.", $user);
        } else {
            print_response(false, "No changes made or user not found.");
        }
    } else {
        print_response(false, "Failed to update the user profile.");
    }
} catch (Exception $e) {
    // Catch any exceptions
    print_response(false, "An error occurred: " . $e->getMessage());
}
?>
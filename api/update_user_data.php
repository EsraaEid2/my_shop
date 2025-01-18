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
$image_path = null;

// Handle the Base64 image if provided
if ($image_base64) {
    // Validate the Base64 string format (ensure it's a valid image)
    $image_data = base64_decode($image_base64, true);
    if ($image_data === false) {
        print_response(false, "Invalid Base64 image data.");
    }

    // Determine the file extension and validate it
    $image_info = getimagesizefromstring($image_data);
    if ($image_info === false || !in_array($image_info['mime'], ['image/jpeg', 'image/png'])) {
        print_response(false, "Invalid image format. Allowed formats: JPG, PNG.");
    }

    // Set the image path for saving in the desired location
    $image_path = 'my_shop/assets/img/user_images/' . uniqid('profile_') . '.' . pathinfo($image_info['mime'], PATHINFO_EXTENSION);
    file_put_contents($image_path, $image_data); // Save the image to the server
}

try {
    // Prepare the SQL statement for updating user data
    $stmt = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ? " . ($image_path ? ", profile_image = ?" : "") . " WHERE id = ?");
    
    if ($image_path) {
        $stmt->bind_param("ssssi", $first_name, $last_name, $email, $image_path, $user_id);
    } else {
        $stmt->bind_param("sssi", $first_name, $last_name, $email, $user_id);
    }

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            // Fetch updated user data
            $result = $conn->query("SELECT id, first_name, last_name, email, profile_image FROM users WHERE id = $user_id");
            $user = $result->fetch_assoc();
            print_response(true, "User profile updated successfully.", $user);
        } else {
            print_response(false, "No changes made or user not found.");
        }
    }
} catch (Exception $e) {
    // Catch any exceptions
    print_response(false, "An error occurred: " . $e->getMessage());
}
?>

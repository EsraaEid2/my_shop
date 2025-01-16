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
$user_id = $data['id'] ?? null; // Fetch user ID from the request body
$first_name = $data['first_name'] ?? null;
$last_name = $data['last_name'] ?? null;
$email = $data['email'] ?? null;

if (empty($user_id) || !is_numeric($user_id)) {
    print_response(false, "Invalid or missing user ID.");
}

if (empty($first_name) || empty($last_name) || empty($email)) {
    print_response(false, "All fields (first_name, last_name, email) are required.");
}

// Initialize image path variable
$image_path = null;

// Handle the image upload if provided
if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
    // Validate image file (type and size)
    $allowed_extensions = ['image/jpeg', 'image/png'];
    $max_size = 2 * 1024 * 1024; // 2MB max

    $file_type = $_FILES['profile_image']['type'];
    $file_size = $_FILES['profile_image']['size'];

    if (!in_array($file_type, $allowed_extensions)) {
        print_response(false, "Invalid image format. Allowed formats: JPG, PNG.");
    }

    if ($file_size > $max_size) {
        print_response(false, "File size is too large. Maximum allowed size is 2MB.");
    }

    // Convert the image to Base64
    $file_path = $_FILES['profile_image']['tmp_name'];
    $image_base64 = base64_encode(file_get_contents($file_path));

    // Set the image path for database update
    $image_path = $image_base64;
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
}     catch (Exception $e) {
    // Catch any exceptions
    print_response(false, "An error occurred: " . $e->getMessage());
}
?>

<?php
header('Content-Type: application/json');

// Include database connection
include('db_connection.php');

// Check if the user is logged in and if the profile image is set
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['user_id']; // Assuming user ID is stored in session

// Check if a file is uploaded
if (!isset($_FILES['profile_image']) || $_FILES['profile_image']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'Image upload failed']);
    exit;
}

$upload_dir = 'uploads/profile_images/';
$file_name = basename($_FILES['profile_image']['name']);
$file_path = $upload_dir . $file_name;

// Allowable file types and size
$allowed_types = ['image/jpeg', 'image/png'];
$max_size = 2 * 1024 * 1024; // 2MB

// Check file type and size
if (!in_array($_FILES['profile_image']['type'], $allowed_types)) {
    echo json_encode(['success' => false, 'message' => 'Invalid image format. Only JPEG and PNG are allowed.']);
    exit;
}

if ($_FILES['profile_image']['size'] > $max_size) {
    echo json_encode(['success' => false, 'message' => 'Image size exceeds the 2MB limit.']);
    exit;
}

// Move uploaded file to the destination folder
if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $file_path)) {
    // Update the user's profile image in the database
    $query = "UPDATE users SET profile_image = ? WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('si', $file_path, $user_id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Profile image uploaded successfully', 'file_path' => $file_path]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update profile image in database']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Error moving uploaded file']);
}
?>

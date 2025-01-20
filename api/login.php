<?php

require_once('config.php'); // Include database configuration and connection
header('Content-Type: application/json');

// Start session
session_start();

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get input data from POST
    $data = json_decode(file_get_contents('php://input'), true); // Associative array key->value
    $email = $data['email'] ?? null;
    $password = $data['password'] ?? null;

    // Validate input
    if (empty($email) || empty($password)) {
        print_response(false, "Email and password are required.");
        exit();
    }

    try {
        // Check if the email exists and if status is active (1)
        $stmt = $conn->prepare("SELECT id, first_name, last_name, email, password, status FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Email exists, fetch user data
            $stmt->bind_result($id, $first_name, $last_name, $email, $hashed_password, $status);
            $stmt->fetch();

            // Check if the user is active (status = 1)
            if ($status == 1) {
                // Verify password
                if (password_verify($password, $hashed_password)) {
                    // If password matches, set session and return user data
                    $_SESSION['user_id'] = $id; // Store user ID in the session
                    $_SESSION['user_name'] = $first_name . ' ' . $last_name; // Store full name in the session

                    $user = [
                        "id" => $id,
                        "first_name" => $first_name,
                        "last_name" => $last_name,
                        "email" => $email
                    ];

                    $stmt->close();
                    print_response(true, "Login successful.", $user);
                } else {
                    // Invalid password
                    $stmt->close();
                    print_response(false, "Invalid email or password.");
                }
            } else {
                // User is inactive (status = 0)
                $stmt->close();
                print_response(false, "Your account is inactive. Please contact support.");
            }
        } else {
            // Email not found
            $stmt->close();
            print_response(false, "Invalid email or password.");
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

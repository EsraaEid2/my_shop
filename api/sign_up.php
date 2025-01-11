<?php
require_once('config.php');

header('Content-Type: application/json');

// Ensure no stray output
ob_start();

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = json_decode(file_get_contents('php://input'), true);

    $first_name = $data['first_name'] ?? null;
    $last_name = $data['last_name'] ?? null;
    $email = $data['email'] ?? null;
    $password = $data['password'] ?? null;

    // Validate input
    if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
        print_response(false, "All fields are required.");
        exit();
    }

    // Check if the email exists
    $stmt = $conn->prepare("SELECT id, status FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $status);
        $stmt->fetch();

        if ($status === 0) {
            // Reactivate user
            $stmt->close();
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, password = ?, status = 1 WHERE email = ?");
            $stmt->bind_param("ssss", $first_name, $last_name, $hashed_password, $email);

            if ($stmt->execute()) {
                print_response(true, "User reactivated successfully.", [
                    "id" => $user_id,
                    "first_name" => $first_name,
                    "last_name" => $last_name,
                    "email" => $email
                ]);
            } else {
                print_response(false, "Failed to reactivate user.");
            }
        } else {
            print_response(false, "Email is already taken.");
        }
    } else {
        // Register new user
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password, status) VALUES (?, ?, ?, ?, 1)");
        $stmt->bind_param("ssss", $first_name, $last_name, $email, $hashed_password);

        if ($stmt->execute()) {
            $user_id = $stmt->insert_id;

            print_response(true, "User registered successfully.", [
                "id" => $user_id,
                "first_name" => $first_name,
                "last_name" => $last_name,
                "email" => $email
            ]);
        } else {
            print_response(false, "Failed to register user.");
        }
    }

    $stmt->close();
} else {
    print_response(false, "Invalid request method.");
}

ob_end_flush();
?>

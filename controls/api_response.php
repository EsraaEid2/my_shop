<?php

/**
 * Function to print a standardized API response as an object.
 *
 * @param bool $success Whether the request was successful.
 * @param string $message A message describing the result.
 * @param array|null $data Additional data to include in the response (optional).
 */
function print_response(bool $success, string $message, ?array $data = null): void {
    // Create the response object
    $response = (object) [
        'success' => $success,
        'message' => $message,
        'data' => $data
    ];

    // Set content type to JSON
    header('Content-Type: application/json');

    // Print the response as JSON
    echo json_encode($response);

    // Stop further script execution
    exit();
}
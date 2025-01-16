<?php
require_once('config.php');
header('Content-Type: application/json');

$user_id = $_GET['user_id']; // Get the user ID from the request

// Query to fetch the user's products
$query = "SELECT * FROM products WHERE user_id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$user_id]);

// Fetch all products and return as JSON
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($products);
?>
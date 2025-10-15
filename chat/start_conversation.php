<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['id'])) {
    die("You must be logged in to start a conversation.");
}

// Get the buyer_id, seller_id, and product_id from the URL
$buyer_id = $_SESSION['id'];
$seller_id = isset($_GET['seller_id']) ? intval($_GET['seller_id']) : 0;
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;

if ($seller_id <= 0 || $product_id <= 0) {
    die("Invalid conversation details.");
}

// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'cartsy');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start a new conversation by inserting a default message
$sql = "INSERT INTO messages (sender_id, receiver_id, product_id, message) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiis", $buyer_id, $seller_id, $product_id, $message);
$message = "Hello, I am interested in this product!"; // Default message
$stmt->execute();

$conn->close();
?>

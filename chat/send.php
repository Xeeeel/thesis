<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['id'])) {
    die("You need to log in to send a message.");
}

$buyer_id = $_SESSION['id'];  // Logged-in user
$product_id = $_POST['product_id'];  // Get product ID from form
$message = $_POST['message'];  // Get message content

// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'cartsy');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Find the seller ID (assuming only one seller per product)
$sql = "SELECT seller_id FROM products WHERE product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
$seller_id = $product['seller_id'];  // Get the seller ID from the product

// Insert the message into the database
$query = "INSERT INTO messages (sender_id, receiver_id, product_id, message) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("iiis", $buyer_id, $seller_id, $product_id, $message);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => $stmt->error]);
}

$conn->close();
?>

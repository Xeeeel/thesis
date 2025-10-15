<?php
session_start();
if (!isset($_SESSION['id'])) {
    die("You need to log in.");
}

$buyer_id = $_SESSION['id'];  // Logged-in user
$product_id = $_GET['product_id'];  // Product to check for conversation

// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'cartsy');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT COUNT(*) FROM messages WHERE product_id = ? AND 
        ((sender_id = ? AND receiver_id = ?) OR 
         (sender_id = ? AND receiver_id = ?))";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiiii", $product_id, $buyer_id, $seller_id, $seller_id, $buyer_id);
$stmt->execute();
$stmt->bind_result($count);
$stmt->fetch();

$conn->close();

echo json_encode(['conversation_exists' => $count > 0]);
?>

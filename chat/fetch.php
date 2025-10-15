<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['id'])) {
    die("You need to log in to send a message.");
}

$buyer_id = $_SESSION['id'];
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;

// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'cartsy');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch messages for the selected product
$sql = "SELECT messages.*, users.name AS sender_name
        FROM messages
        LEFT JOIN users ON users.id = messages.sender_id
        WHERE messages.product_id = ?
        ORDER BY messages.created_at ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

$conn->close();

// Return messages as JSON
echo json_encode($messages);
?>

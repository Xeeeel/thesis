<?php
$data = json_decode(file_get_contents("php://input"), true);
$conn = new mysqli('localhost', 'root', '', 'cartsy');
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, product_id, message) VALUES (?, ?, ?, ?)");
$stmt->bind_param("iiis", $data['sender_id'], $data['receiver_id'], $data['product_id'], $data['message']);
$stmt->execute();
?>

<?php
session_start();
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;
$receiver_id = isset($_GET['receiver_id']) ? intval($_GET['receiver_id']) : 0;
$sender_id = $_SESSION['id'];

$conn = new mysqli('localhost', 'root', '', 'cartsy');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "
    SELECT m.message, m.timestamp, u.name AS sender_name 
    FROM messages m
    JOIN users u ON m.sender_id = u.id
    WHERE m.product_id = ? AND ((m.sender_id = ? AND m.receiver_id = ?) OR (m.sender_id = ? AND m.receiver_id = ?))
    ORDER BY m.timestamp ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiiii", $product_id, $sender_id, $receiver_id, $receiver_id, $sender_id);
$stmt->execute();
$messages_result = $stmt->get_result();

while ($message = $messages_result->fetch_assoc()) {
    echo "<div class='message'><strong>" . htmlspecialchars($message['sender_name']) . ":</strong><p>" . nl2br(htmlspecialchars($message['message'])) . "</p><small>" . $message['timestamp'] . "</small></div>";
}

$conn->close();
?>

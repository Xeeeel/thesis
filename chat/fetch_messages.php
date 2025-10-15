<?php
$conn = new mysqli('localhost', 'root', '', 'cartsy');
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$user_id = $_GET['id'];
$receiver_id = $_GET['receiver_id'];
$product_id = $_GET['product_id'];

$sql = "SELECT * FROM messages 
        WHERE ((sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?)) 
        AND product_id = ?
        ORDER BY timestamp ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iiiii", $user_id, $receiver_id, $receiver_id, $user_id, $product_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $class = $row['sender_id'] == $user_id ? "text-end text-primary" : "text-start text-dark";
    echo "<p class='$class'><strong>{$row['message']}</strong><br><small>{$row['timestamp']}</small></p>";
}
?>

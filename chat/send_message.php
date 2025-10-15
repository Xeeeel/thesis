<?php
// send_message.php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['id'])) {
    die("Not logged in.");
}

if (isset($_POST['message'], $_POST['product_id'], $_POST['receiver_id'])) {
    $message = $_POST['message'];
    $product_id = $_POST['product_id'];
    $receiver_id = $_POST['receiver_id'];
    $sender_id = $_SESSION['id'];

    $conn = new mysqli('localhost', 'root', '', 'cartsy');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, product_id, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiis", $sender_id, $receiver_id, $product_id, $message);
    $stmt->execute();

    $conn->close();
}
?>

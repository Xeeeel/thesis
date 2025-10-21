<?php
session_start();
require_once __DIR__ . '/../config/db_config.php';
$pdo = db(); // Shared PDO connection

// ✅ Ensure user is logged in
if (empty($_SESSION['user_id'])) {
    header("Location: http://localhost/cartsy/login/login.php");
    exit();
}

$buyer_id  = (int)$_SESSION['user_id'];
$seller_id = isset($_POST['seller_id']) ? (int)$_POST['seller_id'] : 0;
$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;

// ✅ Validate
if ($seller_id <= 0 || $product_id <= 0) {
    $_SESSION['error_msg'] = "Invalid conversation details.";
    header("Location: http://localhost/cartsy/index/beta_index.php");
    exit();
}

// ✅ Prevent messaging yourself
if ($buyer_id === $seller_id) {
    $_SESSION['error_msg'] = "You cannot message yourself.";
    header("Location: http://localhost/cartsy/index/beta_index.php");
    exit();
}

try {
    // ✅ Check if conversation already exists
    $check = $pdo->prepare("
        SELECT message_id FROM messages 
        WHERE product_id = ? 
          AND ((sender_id = ? AND receiver_id = ?) 
            OR (sender_id = ? AND receiver_id = ?))
        LIMIT 1
    ");
    $check->execute([$product_id, $buyer_id, $seller_id, $seller_id, $buyer_id]);
    $existing = $check->fetch();

    // ✅ If no conversation, insert a default message
    if (!$existing) {
        $default_message = "Hello, I am interested in this product!";
        $insert = $pdo->prepare("
            INSERT INTO messages (sender_id, receiver_id, product_id, message, created_at)
            VALUES (?, ?, ?, ?, NOW())
        ");
        $insert->execute([$buyer_id, $seller_id, $product_id, $default_message]);
    }

    // ✅ Redirect to conversation
    header("Location: http://localhost/cartsy/chat/conversation.php?product_id={$product_id}&seller_id={$seller_id}");
    exit();

} catch (PDOException $e) {
    error_log("Conversation Error: " . $e->getMessage());
    $_SESSION['error_msg'] = "Unexpected error occurred. Please try again.";
    header("Location: http://localhost/cartsy/index/beta_index.php");
    exit();
}
?>

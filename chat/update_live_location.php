<?php
session_start();
require_once __DIR__ . '/../config/db_config.php';
$pdo = db();

if (empty($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

$user_id = (int)$_SESSION['user_id'];
$product_id = (int)($_POST['product_id'] ?? 0);
$latitude = $_POST['latitude'] ?? null;
$longitude = $_POST['longitude'] ?? null;

// check if user has an active live_location message
$stmt = $pdo->prepare("SELECT message_id FROM messages 
                       WHERE sender_id = ? AND product_id = ? AND message_type = 'live_location'");
$stmt->execute([$user_id, $product_id]);
$existing = $stmt->fetch(PDO::FETCH_ASSOC);

if ($existing) {
    // update existing live location
    $update = $pdo->prepare("UPDATE messages 
        SET latitude = ?, longitude = ?, message = ?, created_at = NOW() 
        WHERE message_id = ?");
    $msg = "🟢 Live location: https://www.google.com/maps?q=$latitude,$longitude";
    $update->execute([$latitude, $longitude, $msg, $existing['message_id']]);
} else {
    // create new live location message
    $insert = $pdo->prepare("INSERT INTO messages (sender_id, receiver_id, product_id, message, message_type, latitude, longitude)
        VALUES (?, 
            (SELECT seller_id FROM products WHERE product_id = ?), 
            ?, ?, 'live_location', ?, ?)");
    $msg = "🟢 Live location: https://www.google.com/maps?q=$latitude,$longitude";
    $insert->execute([$user_id, $product_id, $product_id, $msg, $latitude, $longitude]);
}

echo json_encode(['success' => true]);
?>

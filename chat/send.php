<?php
session_start();
require_once __DIR__ . '/../config/db_config.php';
$pdo = db();

if (empty($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}

$sender_id   = (int)$_SESSION['user_id'];
$product_id  = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$receiver_id = isset($_POST['receiver_id']) ? (int)$_POST['receiver_id'] : 0;
$message     = trim($_POST['message'] ?? '');
$message_type = $_POST['type'] ?? 'text';
$latitude    = $_POST['latitude'] ?? null;
$longitude   = $_POST['longitude'] ?? null;

if ($product_id <= 0 || $receiver_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid product or receiver']);
    exit;
}

try {
    // ✅ Ensure the product exists
    $stmt = $pdo->prepare("SELECT product_id FROM products WHERE product_id = ?");
    $stmt->execute([$product_id]);
    if (!$stmt->fetch()) {
        echo json_encode(['success' => false, 'error' => 'Product not found']);
        exit;
    }

    // ✅ Insert the message
    $sql = "INSERT INTO messages 
            (sender_id, receiver_id, product_id, message, message_type, latitude, longitude, created_at)
            VALUES (:sender_id, :receiver_id, :product_id, :message, :message_type, :latitude, :longitude, NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':sender_id' => $sender_id,
        ':receiver_id' => $receiver_id,
        ':product_id' => $product_id,
        ':message' => $message,
        ':message_type' => $message_type,
        ':latitude' => $latitude,
        ':longitude' => $longitude
    ]);

    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    error_log("Send Error: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Database error']);
}
?>

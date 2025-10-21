<?php
session_start();
require_once __DIR__ . '/../config/db_config.php';
$pdo = db(); // Shared PDO connection

// ✅ Check login
if (empty($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}

$sender_id = (int)$_SESSION['user_id'];
$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$message = trim($_POST['message'] ?? '');
$message_type = $_POST['type'] ?? 'text';
$latitude = $_POST['latitude'] ?? null;
$longitude = $_POST['longitude'] ?? null;

// ✅ Validate input
if ($product_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid product ID']);
    exit;
}

try {
    // ✅ Get seller ID from products table
    $stmt = $pdo->prepare("SELECT seller_id FROM products WHERE product_id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        echo json_encode(['success' => false, 'error' => 'Product not found']);
        exit;
    }

    $seller_id = (int)$product['seller_id'];

    // ✅ Determine receiver: if sender is seller, receiver must be the buyer who chatted with them
    if ($sender_id === $seller_id) {
        // Find the latest buyer that messaged this product
        $stmt = $pdo->prepare("
            SELECT DISTINCT sender_id 
            FROM messages 
            WHERE product_id = ? AND sender_id != ? 
            ORDER BY created_at DESC 
            LIMIT 1
        ");
        $stmt->execute([$product_id, $sender_id]);
        $lastBuyer = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$lastBuyer) {
            echo json_encode(['success' => false, 'error' => 'No buyer found to message.']);
            exit;
        }

        $receiver_id = (int)$lastBuyer['sender_id'];
    } else {
        // Buyer sending message → receiver is seller
        $receiver_id = $seller_id;
    }

    // ✅ Insert message
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
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>

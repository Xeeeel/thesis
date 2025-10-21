<?php
session_start();
require_once __DIR__ . '/../config/db_config.php';
$pdo = db();

// ✅ Ensure the user is logged in
if (empty($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit();
}

$sender_id = (int)$_SESSION['user_id'];
$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$message = trim($_POST['message'] ?? '');

if ($product_id <= 0 || empty($message)) {
    echo json_encode(['success' => false, 'error' => 'Invalid data']);
    exit();
}

// ✅ Find the seller for this product
$stmt = $pdo->prepare("SELECT seller_id FROM products WHERE product_id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    echo json_encode(['success' => false, 'error' => 'Product not found']);
    exit();
}

$seller_id = (int)$product['seller_id'];
$receiver_id = ($sender_id === $seller_id)
    ? $pdo->query("SELECT sender_id FROM messages WHERE product_id = $product_id ORDER BY created_at DESC LIMIT 1")->fetchColumn()
    : $seller_id;

if (!$receiver_id) {
    echo json_encode(['success' => false, 'error' => 'No valid receiver found']);
    exit();
}

// ✅ Insert text message
$insert = $pdo->prepare("
    INSERT INTO messages (sender_id, receiver_id, product_id, message, message_type, created_at)
    VALUES (?, ?, ?, ?, 'text', NOW())
");
$insert->execute([$sender_id, $receiver_id, $product_id, $message]);

echo json_encode(['success' => true]);
?>

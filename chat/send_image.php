<?php
session_start();
require_once __DIR__ . '/../config/db_config.php';
$pdo = db();

if (empty($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit();
}

$sender_id = (int)$_SESSION['user_id'];
$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;

if ($product_id <= 0 || !isset($_FILES['image'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
    exit();
}

// ✅ Find seller
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

// ✅ Handle image upload
$upload_dir = __DIR__ . '/../uploads/';
if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

$file_name = uniqid('msg_', true) . '.' . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
$target_path = $upload_dir . $file_name;

if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
    // ✅ Save image message
    $insert = $pdo->prepare("
        INSERT INTO messages (sender_id, receiver_id, product_id, image_path, message_type, created_at)
        VALUES (?, ?, ?, ?, 'image', NOW())
    ");
    $insert->execute([$sender_id, $receiver_id, $product_id, $file_name]);

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to upload image']);
}
?>

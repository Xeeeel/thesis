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

if ($product_id <= 0 || $receiver_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid parameters']);
    exit;
}

if (empty($_FILES['image']['name'])) {
    echo json_encode(['success' => false, 'error' => 'No image uploaded']);
    exit;
}

$upload_dir = __DIR__ . '/uploads/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

$file_name = time() . '_' . basename($_FILES['image']['name']);
$target_path = $upload_dir . $file_name;
$image_path = "uploads/" . $file_name;

if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
    try {
        $stmt = $pdo->prepare("
            INSERT INTO messages 
                (sender_id, receiver_id, product_id, message, message_type, image_path, created_at)
            VALUES 
                (:sender_id, :receiver_id, :product_id, '', 'image', :image_path, NOW())
        ");
        $stmt->execute([
            ':sender_id' => $sender_id,
            ':receiver_id' => $receiver_id,
            ':product_id' => $product_id,
            ':image_path' => $image_path
        ]);

        echo json_encode(['success' => true, 'image_path' => $image_path]);

    } catch (PDOException $e) {
        error_log("Send Image Error: " . $e->getMessage());
        echo json_encode(['success' => false, 'error' => 'Database error']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to upload image']);
}
?>

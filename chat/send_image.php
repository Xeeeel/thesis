<?php
session_start();
require_once __DIR__ . '/../config/db_config.php';
$pdo = db(); // Shared PDO connection

header('Content-Type: application/json');

// ✅ Check login
if (empty($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}

$sender_id = (int)$_SESSION['user_id'];
$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;

if ($product_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid product ID']);
    exit;
}

// ✅ Validate image file
if (empty($_FILES['image']['tmp_name'])) {
    echo json_encode(['success' => false, 'error' => 'No image uploaded']);
    exit;
}

$image = $_FILES['image'];
$uploadDir = __DIR__ . '/../uploads/';
$allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
$ext = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));

if (!in_array($ext, $allowed)) {
    echo json_encode(['success' => false, 'error' => 'Invalid file type']);
    exit;
}

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// ✅ Generate unique filename
$filename = uniqid('chat_') . '.' . $ext;
$uploadPath = $uploadDir . $filename;
$imagePathForDB = '/cartsy/uploads/' . $filename;

// ✅ Move file to uploads folder
if (!move_uploaded_file($image['tmp_name'], $uploadPath)) {
    echo json_encode(['success' => false, 'error' => 'Failed to upload image']);
    exit;
}

try {
    // ✅ Get product’s seller
    $stmt = $pdo->prepare("SELECT seller_id FROM products WHERE product_id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        echo json_encode(['success' => false, 'error' => 'Product not found']);
        exit;
    }

    $seller_id = (int)$product['seller_id'];

    // ✅ Determine receiver
    if ($sender_id === $seller_id) {
        // Seller → find last buyer
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
            echo json_encode(['success' => false, 'error' => 'No buyer found to message']);
            exit;
        }

        $receiver_id = (int)$lastBuyer['sender_id'];
    } else {
        // Buyer → seller
        $receiver_id = $seller_id;
    }

    // ✅ Insert into messages table
    $sql = "INSERT INTO messages 
            (sender_id, receiver_id, product_id, message, message_type, image_path, created_at)
            VALUES (:sender_id, :receiver_id, :product_id, '', 'image', :image_path, NOW())";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':sender_id' => $sender_id,
        ':receiver_id' => $receiver_id,
        ':product_id' => $product_id,
        ':image_path' => $imagePathForDB
    ]);

    echo json_encode(['success' => true, 'path' => $imagePathForDB]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>

<?php
session_start();
require_once __DIR__ . '/../config/db_config.php';
$pdo = db(); // Shared PDO connection

// ✅ Ensure the user is logged in
if (empty($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'You must be logged in.']);
    exit();
}

$buyer_id = (int)$_SESSION['user_id'];
$seller_id = isset($_GET['seller_id']) ? (int)$_GET['seller_id'] : 0;
$product_id = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;

// ✅ Validate input
if ($seller_id <= 0 || $product_id <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid seller or product.']);
    exit();
}

// ✅ Check if conversation already exists
$stmt = $pdo->prepare("
    SELECT id 
    FROM messages 
    WHERE buyer_id = :buyer_id 
      AND seller_id = :seller_id 
      AND product_id = :product_id
    LIMIT 1
");
$stmt->execute([
    ':buyer_id' => $buyer_id,
    ':seller_id' => $seller_id,
    ':product_id' => $product_id
]);

$exists = $stmt->fetch() ? true : false;

// ✅ Return JSON
header('Content-Type: application/json');
echo json_encode(['exists' => $exists]);
?>

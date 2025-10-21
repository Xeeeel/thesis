<?php
session_start();
require_once __DIR__ . '/../config/db_config.php';
$pdo = db();

if (empty($_SESSION['user_id'])) {
    die("You must be logged in to view messages.");
}

$user_id = (int)$_SESSION['user_id'];
$product_id = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;

if ($product_id <= 0) {
    die("Invalid product ID.");
}

// ✅ Fetch all messages for this product
$sql = "
    SELECT 
        m.message_id,
        m.sender_id,
        m.receiver_id,
        m.product_id,
        m.message,
        m.image_path,
        m.message_type,
        m.created_at,
        u.name AS sender_name
    FROM messages m
    LEFT JOIN users u ON u.id = m.sender_id
    WHERE m.product_id = ?
    ORDER BY m.created_at ASC
";
$stmt = $pdo->prepare($sql);
$stmt->execute([$product_id]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ✅ Return as JSON
header('Content-Type: application/json');
echo json_encode($messages);
?>

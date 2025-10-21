<?php
session_start();
require_once __DIR__ . '/../config/db_config.php';
$pdo = db();

// ✅ Ensure user is logged in
if (empty($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$user_id = (int)$_SESSION['user_id'];
$product_id = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;

if ($product_id <= 0) {
    echo json_encode([]);
    exit;
}

try {
    // ✅ Only fetch messages where the current user is a participant
    $stmt = $pdo->prepare("
        SELECT 
            m.message_id,
            m.sender_id,
            m.receiver_id,
            m.product_id,
            m.message,
            m.message_type,
            m.latitude,
            m.longitude,
            m.image_path,
            m.created_at,
            u.name AS sender_name
        FROM messages m
        LEFT JOIN users u ON u.id = m.sender_id
        WHERE m.product_id = ?
          AND (m.sender_id = ? OR m.receiver_id = ?)
        ORDER BY m.created_at ASC
    ");
    $stmt->execute([$product_id, $user_id, $user_id]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($messages);
} catch (PDOException $e) {
    error_log("Fetch Error: " . $e->getMessage());
    echo json_encode(['error' => 'Failed to fetch messages.']);
}
?>

<?php
session_start();
require_once __DIR__ . '/../config/db_config.php';
$pdo = db();

header('Content-Type: application/json');

if (empty($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']); exit;
}

$user_id   = (int)$_SESSION['user_id'];
$product_id = (int)($_POST['product_id'] ?? 0);
$lat        = isset($_POST['latitude'])  ? (float)$_POST['latitude']  : null;
$lon        = isset($_POST['longitude']) ? (float)$_POST['longitude'] : null;

if (!$product_id || $lat === null || $lon === null) {
    echo json_encode(['success' => false, 'error' => 'Missing params']); exit;
}

/* Find the other participant for this product (seller or last peer). 
   If your UI already knows receiver_id, POST it and skip this lookup. */
$receiver_id = null;
$st = $pdo->prepare("SELECT seller_id FROM products WHERE product_id = ?");
$st->execute([$product_id]);
if ($row = $st->fetch(PDO::FETCH_ASSOC)) {
    $receiver_id = (int)$row['seller_id'];
}
if ($receiver_id === $user_id) {
    // If sender is the seller, send to most recent other participant on this product
    $t = $pdo->prepare("SELECT sender_id FROM messages WHERE product_id=? AND sender_id<>? ORDER BY created_at DESC, message_id DESC LIMIT 1");
    $t->execute([$product_id, $user_id]);
    if ($r = $t->fetch(PDO::FETCH_ASSOC)) $receiver_id = (int)$r['sender_id'];
}

if (!$receiver_id) { echo json_encode(['success' => false, 'error' => 'No receiver']); exit; }

/* Insert a NEW message each update so SSE sees a new message_id */
$ins = $pdo->prepare("
  INSERT INTO messages (product_id, sender_id, receiver_id, message, message_type, latitude, longitude, created_at)
  VALUES (:pid, :sid, :rid, :msg, 'location', :lat, :lon, NOW())
");
$ins->execute([
  ':pid' => $product_id,
  ':sid' => $user_id,
  ':rid' => $receiver_id,
  ':msg' => '🔵 Live location',
  ':lat' => $lat,
  ':lon' => $lon,
]);

echo json_encode(['success' => true]);

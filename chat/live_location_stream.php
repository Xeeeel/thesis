<?php
session_start();
require_once __DIR__ . '/../config/db_config.php';
$pdo = db();

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('Connection: keep-alive');

if (empty($_SESSION['user_id'])) {
  echo "event: error\ndata: " . json_encode(['error' => 'unauth']) . "\n\n";
  flush(); exit;
}

$user_id    = (int)$_SESSION['user_id'];
$product_id = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;
$other_id   = isset($_GET['other_id']) ? (int)$_GET['other_id'] : 0;

if (!$product_id || !$other_id) {
  echo "event: error\ndata: " . json_encode(['error' => 'missing params']) . "\n\n";
  flush(); exit;
}

session_write_close();
set_time_limit(0);

$last = 0;
while (true) {
  $st = $pdo->prepare("
    SELECT message_id, latitude, longitude
    FROM messages
    WHERE product_id = :pid
      AND sender_id  = :other
      AND message_type = 'location'
      AND latitude IS NOT NULL
      AND longitude IS NOT NULL
    ORDER BY message_id DESC LIMIT 1
  ");
  $st->execute([':pid'=>$product_id, ':other'=>$other_id]);
  if ($row = $st->fetch(PDO::FETCH_ASSOC)) {
    if ($row['message_id'] != $last) {
      $last = $row['message_id'];
      echo "data: " . json_encode($row) . "\n\n";
      flush();
    }
  }
  sleep(2);
}

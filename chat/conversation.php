<?php
session_start();
require_once __DIR__ . '/../config/db_config.php';
$pdo = db();

if (empty($_SESSION['user_id'])) {
    header("Location: http://localhost/cartsy/login/login.php");
    exit();
}

$user_id = (int)$_SESSION['user_id'];

// ✅ Fetch unique conversations (buyer ↔ seller per product)
$sql = "
    SELECT 
        CASE 
            WHEN m.sender_id = ? THEN m.receiver_id
            ELSE m.sender_id
        END AS other_user_id,
        u.name AS other_user_name,
        p.product_id,
        p.product_name,
        pi.image_path AS thumbnail,
        MAX(m.created_at) AS last_message_time
    FROM messages m
    JOIN users u 
        ON u.id = CASE WHEN m.sender_id = ? THEN m.receiver_id ELSE m.sender_id END
    JOIN products p 
        ON p.product_id = m.product_id
    LEFT JOIN product_images pi 
        ON pi.product_id = p.product_id
    WHERE (m.sender_id = ? OR m.receiver_id = ?)
    GROUP BY other_user_id, p.product_id
    ORDER BY last_message_time DESC
";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id, $user_id, $user_id, $user_id]);

$conversations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Cartsy Chat</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <style>
    body { font-family: "Poppins", sans-serif; }
    .chat-item:hover { background-color: #f0f0f0; cursor: pointer; }
    .chat-box { background-color: #e9ecef; height: 80vh; overflow-y: auto; }
    .message { padding: 10px 15px; margin: 5px 0; border-radius: 10px; max-width: 70%; word-wrap: break-word; }
    .sent { background-color: #d1ffd1; align-self: flex-end; text-align: right; }
    .received { background-color: #ffffff; align-self: flex-start; }
    .navbar { background-color: #fff; border-bottom: 1px solid #ddd; padding: 1rem 2rem; }
    .navbar-brand { font-family: "Suranna", serif; font-size: 30px; color: #343a40; }
  </style>
</head>
<body>
<nav class="navbar sticky-top navbar-light">
  <div class="container-fluid">
    <a class="navbar-brand fs-3" href="http://localhost/cartsy/index/beta_index.php">Cartsy</a>
    <form class="d-flex flex-grow-1 mx-3" action="http://localhost/cartsy/search-product/test-4.php" method="GET">
      <div class="input-group">
        <input class="form-control" type="search" name="query" placeholder="Search" required>
        <button class="btn btn-dark" type="submit">Search</button>
      </div>
    </form>
    <a href="http://localhost/cartsy/seller/test-1.php" class="btn btn-outline-dark me-3">Sell</a>
    <a href="http://localhost/cartsy/saved/test-6.php" class="btn btn-outline-danger me-3">
      <i class="bi bi-heart-fill"></i>
    </a>
    <div>
      <a href="http://localhost/cartsy/chat/conversation.php"><i class="bi bi-chat fs-4 me-3"></i></a>
      <a href="http://localhost/cartsy/profile/index-7.php"><i class="bi bi-person-circle fs-4"></i></a>
    </div>
  </div>
</nav>

<div class="container-fluid">
  <div class="row vh-100">
    <!-- Sidebar -->
    <div class="col-3 bg-light border-end p-3">
      <div class="mt-4">
        <h5 class="fw-semibold">Chats</h5>
        <ul class="list-unstyled" id="chat-sidebar">
          <?php foreach ($conversations as $conv): ?>
            <li class="d-flex align-items-center p-2 chat-item"
                data-product-id="<?= htmlspecialchars($conv['product_id']) ?>"
                data-other-id="<?= htmlspecialchars($conv['other_user_id']) ?>">
              <img src="/cartsy/seller/<?= htmlspecialchars($conv['thumbnail'] ?? 'default-thumbnail.jpg') ?>" 
                   class="rounded-circle me-2" 
                   style="width: 40px; height: 40px; object-fit: cover;" />
              <div>
                <div class="fw-bold"><?= htmlspecialchars($conv['other_user_name']) ?></div>
                <small class="text-muted"><?= htmlspecialchars($conv['product_name']) ?></small>
              </div>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>

    <!-- Chat Area -->
    <div class="col-9 d-flex flex-column p-0">
      <div id="chat-box" class="flex-grow-1 p-3 chat-box overflow-auto bg-light-gray">
        <p class="text-center text-muted mt-5">Select a conversation to view messages</p>
      </div>

      <div class="p-3 border-top d-flex align-items-center bg-white">
        <form id="message-form" class="w-100 d-flex align-items-center">
          <div class="dropdown me-2">
            <button class="btn btn-secondary rounded-circle" type="button" id="attachMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-plus-lg"></i>
            </button>
            <ul class="dropdown-menu" aria-labelledby="attachMenuButton">
              <li>
                <button class="dropdown-item d-flex align-items-center" type="button" id="sendLocationBtn">
                  <i class="bi bi-geo-alt me-2 text-danger"></i> Send Location
                </button>
              </li>
              <li>
                <button class="dropdown-item d-flex align-items-center" type="button" id="sendImageBtn">
                  <i class="bi bi-image me-2 text-primary"></i> Send Picture
                </button>
              </li>
            </ul>
          </div>

          <input type="text" name="message" id="message-input" class="form-control me-2" placeholder="Type a message..." required />
          <button type="submit" class="btn btn-dark rounded-circle"><i class="bi bi-send"></i></button>
        </form>
        <input type="file" id="imageInput" accept="image/*" style="display:none;">
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
const chatBox = document.getElementById('chat-box');
const sidebar = document.getElementById('chat-sidebar');
const form = document.getElementById('message-form');
const input = document.getElementById('message-input');
let currentProductId = null;
let currentOtherId = null;
let autoRefreshEnabled = true;

// Scroll behavior
chatBox.addEventListener('scroll', () => {
  const distanceFromBottom = chatBox.scrollHeight - chatBox.scrollTop - chatBox.clientHeight;
  autoRefreshEnabled = distanceFromBottom < 100;
});

// Load messages
function loadMessages(productId, otherId, preserveScroll = false) {
  currentProductId = productId;
  currentOtherId = otherId;
  fetch(`fetch.php?product_id=${productId}&other_id=${otherId}`)
    .then(res => res.json())
    .then(data => {
      chatBox.innerHTML = '';
      data.forEach(msg => {
        const div = document.createElement('div');
        div.className = 'd-flex flex-column ' + 
          (msg.sender_id == <?= $user_id ?> ? 'align-items-end' : 'align-items-start') + ' mb-3';
        let content = '';

        if (msg.message_type === 'image' && msg.image_path) {
          content = `<a href="${msg.image_path}" target="_blank">
                        <img src="${msg.image_path}" class="img-fluid rounded" style="max-width:200px; border:1px solid #ccc;"/>
                     </a>`;
        } else if (msg.message_type === 'location' && msg.latitude && msg.longitude) {
          const mapId = 'map-' + msg.message_id;
          content = `
            <div style="width:200px; height:200px; border:1px solid #ccc; border-radius:10px;" id="${mapId}"></div>
            <small><a href="https://www.openstreetmap.org/?mlat=${msg.latitude}&mlon=${msg.longitude}" target="_blank">View on Map</a></small>
          `;
          setTimeout(() => {
            const map = L.map(mapId, { zoomControl: false }).setView([msg.latitude, msg.longitude], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);
            L.marker([msg.latitude, msg.longitude]).addTo(map);
          }, 300);
        } else {
          content = msg.message;
        }

        div.innerHTML = `
          <div class="message ${msg.sender_id == <?= $user_id ?> ? 'sent' : 'received'}">
            <strong>${msg.sender_id == <?= $user_id ?> ? 'You' : msg.sender_name}:</strong> ${content}
          </div>`;
        chatBox.appendChild(div);
      });
      chatBox.scrollTop = chatBox.scrollHeight;
    });
}

// Auto-refresh
setInterval(() => {
  if (currentProductId && currentOtherId && autoRefreshEnabled) loadMessages(currentProductId, currentOtherId, true);
}, 3000);

// Sidebar click
sidebar.addEventListener('click', e => {
  const item = e.target.closest('.chat-item');
  if (!item) return;
  const productId = item.getAttribute('data-product-id');
  const otherId = item.getAttribute('data-other-id');
  loadMessages(productId, otherId);
});

// Send text
form.addEventListener('submit', e => {
  e.preventDefault();
  if (!currentProductId || !currentOtherId) return;
  const message = input.value.trim();
  if (!message) return;

  fetch('send.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `product_id=${currentProductId}&receiver_id=${currentOtherId}&message=${encodeURIComponent(message)}`
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      input.value = '';
      loadMessages(currentProductId, currentOtherId);
    }
  });
});

// Image upload
document.getElementById('sendImageBtn').addEventListener('click', () => {
  document.getElementById('imageInput').click();
});

document.getElementById('imageInput').addEventListener('change', function () {
  const file = this.files[0];
  if (!file || !currentProductId || !currentOtherId) return;

  const formData = new FormData();
  formData.append('image', file);
  formData.append('product_id', currentProductId);
  formData.append('receiver_id', currentOtherId);

  fetch('send_image.php', { method: 'POST', body: formData })
    .then(res => res.json())
    .then(data => {
      if (data.success) loadMessages(currentProductId, currentOtherId);
      else alert('Failed to send image');
    });
});

// Send dynamic location
document.getElementById('sendLocationBtn').addEventListener('click', () => {
  if (!navigator.geolocation) {
    alert("Geolocation not supported.");
    return;
  }
  navigator.geolocation.getCurrentPosition(pos => {
    const lat = pos.coords.latitude;
    const lon = pos.coords.longitude;
    fetch('send.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `product_id=${currentProductId}&receiver_id=${currentOtherId}&message=${encodeURIComponent('📍 Shared Location')}&latitude=${lat}&longitude=${lon}&type=location`
    })
    .then(res => res.json())
    .then(() => loadMessages(currentProductId, currentOtherId));
  });
});
</script>
</body>
</html>

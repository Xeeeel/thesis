<?php
session_start();
require_once __DIR__ . '/../config/db_config.php';
$pdo = db();

// ✅ Ensure the user is logged in
if (empty($_SESSION['user_id'])) {
    header("Location: http://localhost/cartsy/login/login.php");
    exit();
}

$user_id = (int)$_SESSION['user_id'];

// ✅ Fetch all conversations
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
        u.name AS sender_name,
        p.product_name,
        p.product_id,
        pi.image_path AS thumbnail
    FROM messages m
    LEFT JOIN users u ON u.id = m.sender_id
    LEFT JOIN products p ON p.product_id = m.product_id
    LEFT JOIN product_images pi ON pi.product_id = p.product_id
    WHERE (m.sender_id = ? OR m.receiver_id = ?)
    GROUP BY p.product_id
    ORDER BY m.created_at DESC
";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id, $user_id]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ✅ Organize conversations by product_id
$conversations = [];
foreach ($messages as $row) {
    $pid = $row['product_id'];
    if (!isset($conversations[$pid])) {
        $conversations[$pid] = [
            'product_id' => $pid,
            'product_name' => $row['product_name'] ?? 'Unknown Product',
            'thumbnail' => $row['thumbnail'] ?? 'default-thumbnail.jpg',
            'messages' => []
        ];
    }
    $conversations[$pid]['messages'][] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Cartsy Chat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet" />
    <style>
        body { font-family: "Poppins", sans-serif; }
        .chat-item:hover { background-color: #f0f0f0; cursor: pointer; }
        .chat-box { background-color: #e9ecef; height: 80vh; overflow-y: scroll; }
        .message { padding: 10px 15px; margin: 5px 0; border-radius: 10px; max-width: 70%; word-wrap: break-word; }
        .sent { background-color: #d1ffd1; align-self: flex-end; text-align: right; }
        .received { background-color: #ffffff; align-self: flex-start; }
        .message img { max-width: 250px; border-radius: 8px; display: block; margin-top: 5px; }
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
                    <?php foreach ($conversations as $pid => $conv): ?>
                        <li class="d-flex align-items-center p-2 chat-item" data-product-id="<?= $pid ?>">
                            <img src="/cartsy/seller/<?= htmlspecialchars($conv['thumbnail']) ?>" 
                                 class="rounded-circle me-2" 
                                 style="width: 40px; height: 40px; object-fit: cover;" />
                            <span><?= htmlspecialchars($conv['product_name']) ?></span>
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

            <!-- ✅ Chat Input -->
            <div class="p-3 border-top d-flex align-items-center bg-white">
                <form id="message-form" class="w-100 d-flex align-items-center">
                    <!-- ✅ Dropdown for attachments -->
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
                    <button type="submit" class="btn btn-dark rounded-circle">
                        <i class="bi bi-send"></i>
                    </button>
                </form>

                <!-- Hidden File Input -->
                <input type="file" id="imageInput" accept="image/*" style="display: none;">
            </div>
        </div>
    </div>
</div>

<!-- ✅ Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
const chatBox = document.getElementById('chat-box');
const sidebar = document.getElementById('chat-sidebar');
const form = document.getElementById('message-form');
const input = document.getElementById('message-input');
let currentProductId = null;

// ✅ Load messages
function loadMessages(productId) {
    currentProductId = productId;
    fetch(`fetch.php?product_id=${productId}`)
        .then(res => res.json())
        .then(data => {
            chatBox.innerHTML = '';
            data.forEach(msg => {
                const div = document.createElement('div');
                div.className = 'd-flex flex-column ' + (msg.sender_id == <?= $user_id ?> ? 'align-items-end' : 'align-items-start') + ' mb-3';

                let content = '';
                if (msg.message_type === 'image' && msg.image_path) {
                    content = `<img src="/cartsy/uploads/${msg.image_path}" alt="Image" class="img-fluid rounded">`;
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

// ✅ Sidebar click event
sidebar.addEventListener('click', function (e) {
    const productId = e.target.closest('.chat-item')?.getAttribute('data-product-id');
    if (productId) loadMessages(productId);
});

// ✅ Send text message
form.addEventListener('submit', function (e) {
    e.preventDefault();
    if (!currentProductId) return;
    const message = input.value.trim();
    if (!message) return;

    fetch('send.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `product_id=${currentProductId}&message=${encodeURIComponent(message)}`
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            input.value = '';
            loadMessages(currentProductId);
        }
    });
});

// ✅ Send image
document.getElementById('sendImageBtn').addEventListener('click', () => {
    document.getElementById('imageInput').click();
});
document.getElementById('imageInput').addEventListener('change', function () {
    const file = this.files[0];
    if (!file || !currentProductId) return;

    const formData = new FormData();
    formData.append('image', file);
    formData.append('product_id', currentProductId);

    fetch('send_image.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) loadMessages(currentProductId);
        else alert('Image upload failed: ' + (data.error || 'Unknown error'));
    });
});

// ✅ Send location
document.getElementById('sendLocationBtn').addEventListener('click', () => {
    if (!navigator.geolocation) {
        alert("Geolocation is not supported by your browser.");
        return;
    }

    navigator.geolocation.getCurrentPosition(position => {
        const latitude = position.coords.latitude;
        const longitude = position.coords.longitude;
        const locationMessage = `📍 My location: https://www.google.com/maps?q=${latitude},${longitude}`;

        fetch('send.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `product_id=${currentProductId}&message=${encodeURIComponent(locationMessage)}`
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) loadMessages(currentProductId);
        });
    }, () => alert("Unable to retrieve your location."));
});
</script>
</body>
</html>

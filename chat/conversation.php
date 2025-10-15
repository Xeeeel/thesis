<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['id'])) {
    die("You need to log in to view this page.");
}

$buyer_id = $_SESSION['id'];  // Logged-in user

// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'cartsy');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve all conversations for the logged-in user, including product thumbnail
$sql = "SELECT messages.*, users.name AS sender_name, products.product_name, products.product_id, product_images.image_path AS thumbnail
        FROM messages
        LEFT JOIN users ON users.id = messages.sender_id
        LEFT JOIN products ON products.product_id = messages.product_id
        LEFT JOIN product_images ON product_images.product_id = products.product_id
        WHERE (messages.sender_id = ? OR messages.receiver_id = ?)
        ORDER BY messages.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $buyer_id, $buyer_id);
$stmt->execute();
$result = $stmt->get_result();

// Store conversations in an array with unique product_id as a key
$conversations = [];
while ($row = $result->fetch_assoc()) {
    $product_id = $row['product_id'];
    if (!isset($conversations[$product_id])) {
        $conversations[$product_id] = [
            'product_id' => $product_id,
            'messages' => [],
            'product_name' => $row['product_name'],
            'thumbnail' => $row['thumbnail']
        ];
    }
    $conversations[$product_id]['messages'][] = $row;
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Cartsy Chat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }
        .chat-item:hover {
            background-color: #f0f0f0;
            cursor: pointer;
        }
        .chat-box {
            background-color: #d4d4d4;
            height: 80vh;
            overflow-y: scroll;
        }
        .bg-light-gray {
            background-color: #d4d4d4 !important;
        }
        .message {
            padding: 10px 15px;
            margin: 5px 0;
            border-radius: 10px;
            max-width: 70%;
            word-wrap: break-word;
        }
        .sent {
            background-color: #d1ffd1;
            align-self: flex-end;
            text-align: right;
        }
        .received {
            background-color: #ffffff;
            align-self: flex-start;
        }
        .navbar { background-color: #ffffff; border-bottom: 1px solid #e0e0e0; padding: 1rem 2rem; }
        .navbar-brand { color: #343a40; font-family: "Suranna", serif; font-size: 30px; }

    </style>
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"
    />
</head>
<body>
<nav class="navbar sticky-top navbar-light">
        <div class="container-fluid">
            <!-- Brand -->
            <a class="navbar-brand fs-3" href="http://localhost/cartsy/index/test-9.php">Cartsy</a>

            <!-- Search Bar with Button Inside -->
            <form class="d-flex flex-grow-1 mx-3" action="http://localhost/cartsy/search-product/test-4.php" method="GET">
                <div class="input-group">
                    <input class="form-control" type="search" name="query" placeholder="Search" required>
                    <button class="btn btn-dark" type="submit">Search</button>
                </div>
            </form>

            <!-- Sell Button -->
            <a href="<?php echo $has_approved_product ? 'http://localhost/cartsy/seller/test-1-0.php' : 'http://localhost/cartsy/seller/test-1.php'; ?>" class="btn btn-outline-dark me-3">Sell</a>

            <!-- Saved Products Button -->
            <a href="http://localhost/cartsy/saved/test-6.php" class="btn btn-outline-danger me-3">
                <i class="bi bi-heart-fill"></i>
            </a>

            <!-- Chat and Profile Icons -->
            <div>
                <a href="http://localhost/cartsy/chat/conversation.php">
                    <i class="bi bi-chat fs-4 me-3"></i>
                </a>
                <a href="http://localhost/cartsy/profile/index-7.php">
                    <i class="bi bi-person-circle fs-4"></i>
                </a>
            </div>
        </div>
    </nav>
    <!-- Navigation Bar (not modified) -->
    <div class="container-fluid">
        <div class="row vh-100">
            <!-- Sidebar -->
            <div class="col-3 bg-light border-end p-3">
                <div class="mt-4">
                    <h5 class="fw-semibold">Chats</h5>
                    <ul class="list-unstyled" id="chat-sidebar">
                        <?php foreach ($conversations as $product_id => $conversation): ?>
                            <li class="d-flex align-items-center p-2 chat-item" data-product-id="<?= $product_id ?>">
                                <img src="/cartsy/seller/<?= htmlspecialchars($conversation['thumbnail'] ?: 'default-thumbnail.jpg') ?>" class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;" />
                                <span><?= htmlspecialchars($conversation['product_name']) ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <!-- Chat Area -->
            <div class="col-9 d-flex flex-column p-0">
                <div id="chat-box" class="flex-grow-1 p-3 chat-box overflow-auto bg-light-gray">
                    <!-- Messages will be loaded here based on user selection -->
                </div>

                <!-- Message Input -->
                <div class="p-3 border-top d-flex align-items-center bg-white">
                    <form id="message-form" class="w-100 d-flex">
                        <input type="text" name="message" id="message-input" class="form-control me-2" placeholder="Type a message..." required />
                        <button type="submit" class="btn btn-dark rounded-circle">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-send" viewBox="0 0 16 16">
                                <path d="M15.964.686a.5.5 0 0 1 .003.707L1.935 15.425a.5.5 0 0 1-.811-.447l.897-4.698 4.7-2.35-4.7-2.35L1.124.72A.5.5 0 0 1 1.935.275L15.964.686Z"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const chatBox = document.getElementById('chat-box');
        const sidebar = document.getElementById('chat-sidebar');
        const form = document.getElementById('message-form');
        const input = document.getElementById('message-input');
        
        let currentProductId = null;

        // Function to load messages based on product_id
        function loadMessages(productId) {
            currentProductId = productId;

            fetch(`fetch.php?product_id=${productId}`)
                .then(res => res.json())
                .then(data => {
                    chatBox.innerHTML = '';  // Clear previous messages
                    data.forEach(msg => {
                        const div = document.createElement('div');
                        div.className = 'd-flex flex-column ' + (msg.sender_id == <?= $buyer_id ?> ? 'align-items-end' : 'align-items-start') + ' mb-3';
                        div.innerHTML = `<div class="message ${msg.sender_id == <?= $buyer_id ?> ? 'sent' : 'received'}"><strong>${msg.sender_id == <?= $buyer_id ?> ? 'You' : msg.sender_name}:</strong> ${msg.message}</div>`;
                        chatBox.appendChild(div);
                    });
                    chatBox.scrollTop = chatBox.scrollHeight;
                });
        }

        // Sidebar click event
        sidebar.addEventListener('click', function (e) {
            const productId = e.target.closest('.chat-item')?.getAttribute('data-product-id');
            if (productId) {
                loadMessages(productId);
            }
        });

        // Send message
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            if (!currentProductId) return;

            const message = input.value;
            fetch('send.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `buyer_id=<?= $buyer_id ?>&product_id=${currentProductId}&message=${encodeURIComponent(message)}`
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    input.value = '';
                    loadMessages(currentProductId);
                }
            });
        });
    </script>
</body>
</html>

<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'cartsy');
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$current_user_id = $_SESSION['id'];
$current_user_type = $_SESSION['user_type'];

$seller_id = $_GET['seller_id'];
$product_id = $_GET['product_id'];

if ($current_user_type === 'seller') {
    $receiver_id = getBuyerId($conn, $seller_id, $product_id);
} else {
    $receiver_id = $seller_id;
}

function getBuyerId($conn, $seller_id, $product_id) {
    $sql = "SELECT DISTINCT sender_id FROM messages WHERE receiver_id = ? AND product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $seller_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) return $row['sender_id'];
    return null;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Chat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        #chat-box {
            height: 500px;
            overflow-y: scroll;
            border: 1px solid #ddd;
            padding: 10px;
            background: #fefefe;
        }
    </style>
</head>
<body class="p-4">
    <h3>Chat</h3>
    <div id="chat-box" class="mb-3"></div>
    <form id="chat-form">
        <div class="input-group">
            <input type="text" id="message" class="form-control" placeholder="Type your message..." required />
            <button class="btn btn-primary" type="submit">Send</button>
        </div>
    </form>

    <script>
        const chatBox = document.getElementById("chat-box");
        const chatForm = document.getElementById("chat-form");
        const messageInput = document.getElementById("message");

        const currentUserId = <?= json_encode($current_user_id) ?>;
        const receiverId = <?= json_encode($receiver_id) ?>;
        const productId = <?= json_encode($product_id) ?>;

        function loadMessages() {
            fetch(`/cartsy/chat/fetch_messages.php?user_id=${currentUserId}&receiver_id=${receiverId}&product_id=${productId}`)
                .then(res => res.text())
                .then(data => {
                    chatBox.innerHTML = data;
                    chatBox.scrollTop = chatBox.scrollHeight;
                });
        }

        chatForm.addEventListener("submit", e => {
            e.preventDefault();
            const msg = messageInput.value.trim();
            if (msg !== "") {
                fetch("/cartsy/chat/send_message-1.php", {
                    method: "POST",
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ sender_id: currentUserId, receiver_id: receiverId, product_id: productId, message: msg })
                }).then(() => {
                    messageInput.value = "";
                    loadMessages();
                });
            }
        });

        setInterval(loadMessages, 1000);
        loadMessages();
    </script>
</body>
</html>

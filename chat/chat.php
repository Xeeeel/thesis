<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Get product and seller information
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;
$seller_id = isset($_GET['seller_id']) ? intval($_GET['seller_id']) : 0;
$user_id = $_SESSION['id'];  // Assuming user ID is stored in session

// Check if the product exists
$conn = new mysqli('localhost', 'root', '', 'cartsy');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$product_sql = "SELECT * FROM products WHERE product_id = ?";
$stmt = $conn->prepare($product_sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$product_result = $stmt->get_result();

if ($product_result->num_rows <= 0) {
    die("Product not found.");
}

// Fetch existing messages between buyer and seller
$sql = "
    SELECT m.message, m.timestamp, u.name AS sender_name 
    FROM messages m
    JOIN users u ON m.sender_id = u.id
    WHERE m.product_id = ? AND ((m.sender_id = ? AND m.receiver_id = ?) OR (m.sender_id = ? AND m.receiver_id = ?))
    ORDER BY m.timestamp ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiiii", $product_id, $user_id, $seller_id, $seller_id, $user_id);
$stmt->execute();
$messages_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Chat with Seller</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="chat.css" />
</head>
<body>
    <div class="container">
        <div class="chat-box">
            <h3>Chat with Seller</h3>

            <div class="message-list" id="message-list">
                <?php while ($message = $messages_result->fetch_assoc()) { ?>
                    <div class="message">
                        <strong><?php echo htmlspecialchars($message['sender_name']); ?>:</strong>
                        <p><?php echo nl2br(htmlspecialchars($message['message'])); ?></p>
                        <small><?php echo $message['timestamp']; ?></small>
                    </div>
                <?php } ?>
            </div>

            <div class="message-form">
                <textarea id="message" class="form-control" placeholder="Type your message..."></textarea>
                <button id="sendMessage" class="btn btn-primary mt-2">Send</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Load the messages using AJAX
        function loadMessages() {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "load_messages.php?product_id=" + <?php echo $product_id; ?> + "&receiver_id=" + <?php echo $seller_id; ?>, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    document.getElementById("message-list").innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        }

        // Send message using AJAX
        document.getElementById("sendMessage").addEventListener("click", function() {
            var message = document.getElementById("message").value;
            var product_id = <?php echo $product_id; ?>;
            var receiver_id = <?php echo $seller_id; ?>;

            if (message.trim() === "") {
                alert("Please type a message.");
                return;
            }

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "send_message.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    document.getElementById("message").value = ""; // Clear the input field
                    loadMessages(); // Reload messages
                }
            };
            xhr.send("product_id=" + product_id + "&receiver_id=" + receiver_id + "&message=" + encodeURIComponent(message));
        });

        // Load messages every 2 seconds (real-time chat update)
        setInterval(loadMessages, 2000);
    </script>
</body>
</html>

<?php
$conn->close();
?>

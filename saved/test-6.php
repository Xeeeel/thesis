<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cartsy";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['id'];

// Handle deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_saved_id'])) {
    $delete_id = $_POST['delete_saved_id'];
    $delete_stmt = $conn->prepare("DELETE FROM saved_products WHERE saved_id = ? AND user_id = ?");
    $delete_stmt->bind_param("ii", $delete_id, $user_id);
    $delete_stmt->execute();
    $delete_stmt->close();
}

// Query to get all saved products for the logged-in user
$query = "
    SELECT sp.saved_id, p.product_id, p.product_name, p.price, p.thumbnail, 
           p.status, p.location, p.seller_id, u.name AS seller_name
    FROM saved_products sp
    JOIN products p ON sp.product_id = p.product_id
    LEFT JOIN users u ON p.seller_id = u.id
    WHERE sp.user_id = ?
    GROUP BY p.product_id
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Saved Products - Cartsy</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
  <style>
    .product-card {
      border: 1px solid #e0e0e0;
      border-radius: 8px;
      padding: 15px;
      margin-bottom: 15px;
      transition: box-shadow 0.2s ease;
      align-items: center;
    }

    .product-card:hover {
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .product-image {
      width: 100px;
      height: 100px;
      object-fit: cover;
      border-radius: 8px;
      margin-right: 15px;
    }

    .product-info {
      flex-grow: 1;
    }

    .product-title {
      font-weight: 600;
      font-size: 1.1rem;
    }

    .product-location {
      color: #6c757d;
    }

    .seller-name {
      font-size: 1.2rem;
      font-weight: bold;
      margin: 20px 0 10px 0;
    }

    .navbar {
      background-color: #ffffff;
      border-bottom: 1px solid #e0e0e0;
      padding: 1rem 2rem;
    }

    .navbar-brand {
      color: #343a40;
      font-family: "Suranna", serif;
      font-size: 30px;
    }
  </style>
</head>
<body>

<nav class="navbar sticky-top navbar-light">
    <div class="container-fluid">
        <a class="navbar-brand fs-3" href="/cartsy/index/test-9.php">Cartsy</a>
        <form class="d-flex flex-grow-1 mx-3" action="/cartsy/search-product/test-4.php" method="GET">
            <div class="input-group">
                <input class="form-control" type="search" name="query" placeholder="Search" required>
                <button class="btn btn-dark" type="submit">Search</button>
            </div>
        </form>
        <a href="/cartsy/seller/test-1.php" class="btn btn-outline-dark me-3">Sell</a>
        <a href="/cartsy/saved/test-6.php" class="btn btn-outline-danger me-3"><i class="bi bi-heart-fill"></i></a>
        <div>
            <a href="/cartsy/chat/conversation.php"><i class="bi bi-chat fs-4 me-3"></i></a>
            <a href="/cartsy/profile/index-7.php"><i class="bi bi-person-circle fs-4"></i></a>
        </div>
    </div>
</nav>

<div class="container mt-4">
  <h5>Saved Products</h5>
  <div class="d-md-flex product-card fw-bold bg-light text-dark">
    <div class="col-md-6 d-flex align-items-center">Product</div>
    <div class="col-md-2 text-center">Unit Price</div>
    <div class="col-md-2 text-center">Actions</div>
    <div class="col-md-2 text-center">Contact</div>
  </div>

  <?php while ($row = $result->fetch_assoc()): ?>
    <?php
      $seller_name = $row['seller_name'] ?? 'Unknown Seller';
      $location = $row['location'] ?? 'Location not available';
      $thumbnail = !empty($row['thumbnail']) ? "/cartsy/seller/" . htmlspecialchars($row['thumbnail']) : "/cartsy/assets/default-image.jpg";
    ?>
    <div class="seller-name"><?php echo htmlspecialchars($seller_name); ?></div>
    <div class="d-md-flex product-card align-items-center">
      <div class="col-md-6 d-flex align-items-center">
        <img src="<?php echo htmlspecialchars($thumbnail); ?>" alt="Product" class="product-image">
        <div class="product-info">
          <div class="product-title"><?php echo htmlspecialchars($row['product_name']); ?></div>
          <div class="product-location"><?php echo htmlspecialchars($location); ?></div>
        </div>
      </div>
      <div class="col-md-2 text-center">₱<?php echo number_format($row['price'], 2); ?></div>
      <div class="col-md-2 text-center">
        <!-- Delete Button -->
        <form method="POST" onsubmit="return confirm('Are you sure you want to delete this saved product?');">
          <input type="hidden" name="delete_saved_id" value="<?php echo $row['saved_id']; ?>">
          <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
        </form>
      </div>
      <div class="col-md-2 text-center">
        <!-- Message Button -->
        <button class="btn btn-outline-primary btn-sm" onclick="startChat(<?php echo $row['seller_id']; ?>, <?php echo $row['product_id']; ?>)">Message</button>

      </div>
    </div>
  <?php endwhile; ?>
</div>
<script>

    function startChat(sellerId, productId) {
        var buyerId = <?php echo isset($_SESSION['id']) ? $_SESSION['id'] : 'null'; ?>;
        if (buyerId !== null) {
            fetch(`/cartsy/chat/check_conversation.php?buyer_id=${buyerId}&seller_id=${sellerId}&product_id=${productId}`)
                .then(res => res.json())
                .then(data => {
                    const url = `/cartsy/chat/conversation.php?product_id=${productId}&seller_id=${sellerId}&buyer_id=${buyerId}`;
                    if (data.exists) {
                        window.location.href = url;
                    } else {
                        fetch(`/cartsy/chat/start_conversation.php?buyer_id=${buyerId}&seller_id=${sellerId}&product_id=${productId}`)
                            .then(() => window.location.href = url);
                    }
                });
        } else {
            alert("You need to log in to chat.");
        }
    }
</script>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>

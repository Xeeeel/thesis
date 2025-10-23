<?php
session_start();
require_once __DIR__ . '/../config/db_config.php'; // PDO config
$pdo = db();

// Ensure user is logged in
if (empty($_SESSION['user_id'])) {
    header("Location: http://localhost/cartsy/login/login.php");
    exit();
}
$user_id = (int)$_SESSION['user_id'];

// Handle deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_saved_id'])) {
    $delete_id = (int)$_POST['delete_saved_id'];
    $delete_stmt = $pdo->prepare("DELETE FROM saved_products WHERE saved_id = ? AND user_id = ?");
    $delete_stmt->execute([$delete_id, $user_id]);
}

// Saved products for this user
$query = "
    SELECT sp.saved_id,
           p.product_id,
           p.product_name,
           p.price,
           p.thumbnail,
           p.status,
           p.location,
           p.seller_id,
           u.name AS seller_name
    FROM saved_products sp
    JOIN products p ON sp.product_id = p.product_id
    LEFT JOIN users u ON p.seller_id = u.id
    WHERE sp.user_id = ?
    GROUP BY p.product_id
";
$stmt = $pdo->prepare($query);
$stmt->execute([$user_id]);
$savedProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Helper
function e($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
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
    .product-card:hover { box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); }
    .product-image {
      width: 100px; height: 100px; object-fit: cover;
      border-radius: 8px; margin-right: 15px;
    }
    .product-info { flex-grow: 1; }
    .product-title { font-weight: 600; font-size: 1.1rem; }
    .product-location { color: #6c757d; }
    .seller-name { font-size: 1.2rem; font-weight: bold; margin: 20px 0 10px 0; }
    .navbar {
      background-color: #ffffff; border-bottom: 1px solid #e0e0e0; padding: 1rem 2rem;
    }
    .navbar-brand { color: #343a40; font-family: "Suranna", serif; font-size: 30px; }
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

  <?php if (empty($savedProducts)): ?>
    <div class="text-center text-muted mt-4">No saved products yet.</div>
  <?php else: ?>
    <?php foreach ($savedProducts as $row): ?>
      <?php
        $seller_name = $row['seller_name'] ?? 'Unknown Seller';
        $location = $row['location'] ?? 'Location not available';
        $thumbRel = !empty($row['thumbnail']) ? '/cartsy/seller/' . $row['thumbnail'] : '/cartsy/assets/default-image.jpg';
      ?>
      <div class="seller-name"><?= e($seller_name) ?></div>

      <div class="d-md-flex product-card align-items-center">
        <div class="col-md-6 d-flex align-items-center">
          <img src="<?= e($thumbRel) ?>" alt="Product" class="product-image"
               onerror="this.src='/cartsy/assets/default-image.jpg'">
          <div class="product-info">
            <div class="product-title"><?= e($row['product_name']) ?></div>
            <div class="product-location"><?= e($location) ?></div>
          </div>
        </div>

        <div class="col-md-2 text-center">
          ₱<?= number_format((float)$row['price'], 2) ?>
        </div>

        <div class="col-md-2 text-center">
          <!-- Delete -->
          <form method="POST" onsubmit="return confirm('Delete this saved product?');" class="d-inline">
            <input type="hidden" name="delete_saved_id" value="<?= (int)$row['saved_id'] ?>">
            <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
          </form>
        </div>

        <div class="col-md-2 text-center">
          <!-- ✅ Message (same as view_product: POST to start_conversation.php) -->
          <form method="POST" action="/cartsy/chat/start_conversation.php" class="d-inline">
            <input type="hidden" name="seller_id" value="<?= (int)$row['seller_id'] ?>">
            <input type="hidden" name="product_id" value="<?= (int)$row['product_id'] ?>">
            <button type="submit" class="btn btn-outline-primary btn-sm">Message</button>
          </form>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

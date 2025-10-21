<?php
session_start();
require_once __DIR__ . '/../config/db_config.php';
$pdo = db();

// Ensure the user is logged in
if (empty($_SESSION['user_id'])) {
    header("Location: http://localhost/cartsy/login/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Validate product ID
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($product_id <= 0) {
    die("<p>Invalid product ID.</p>");
}

// Fetch product details
$stmt = $pdo->prepare("
    SELECT p.*, u.name AS seller_name, u.profile_picture
    FROM products p
    JOIN users u ON p.seller_id = u.id
    WHERE p.product_id = :id
");
$stmt->execute([':id' => $product_id]);
$product = $stmt->fetch();

if (!$product) {
    die("<p>Product not found.</p>");
}

$seller_id = $product['seller_id'];
$profile_picture = !empty($product['profile_picture']) ? $product['profile_picture'] : 'default.png';

// Fetch product images
$stmt_images = $pdo->prepare("SELECT image_path FROM product_images WHERE product_id = :id");
$stmt_images->execute([':id' => $product_id]);
$images = $stmt_images->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($product['product_name']) ?> | Cartsy</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Suranna&display=swap" rel="stylesheet" />
  <style>
    body { font-family: "Poppins", sans-serif; background-color: #f8f9fa; }
    .navbar { background-color: #fff; border-bottom: 1px solid #e0e0e0; }
    .navbar-brand { color: #343a40; font-family: "Suranna", serif; font-size: 30px; }
    .product-card { background-color: white; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); padding: 40px; max-width: 1200px; margin: 30px auto; }
    .product-image { width: 100%; height: 100%; object-fit: contain; border-radius: 8px; }
    .product-title { font-weight: 700; font-size: 1.8rem; color: #333; }
    .product-price { color: #ff5555; font-weight: bold; font-size: 1.6rem; margin-top: 5px; }
    .product-location { color: #666; font-size: 1rem; margin-bottom: 15px; }
    .save-heart { border: none; background: none; color: #ff5555; font-size: 1.8rem; padding: 5px; transition: transform 0.3s; }
    .save-heart:hover { transform: scale(1.3); }
    .details-section { margin-top: 30px; }
    .seller-info { background-color: #fff3cd; padding: 20px; border-radius: 8px; display: flex; align-items: center; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    .seller-icon { background-color: #fbc02d; width: 60px; height: 60px; border-radius: 50%; overflow: hidden; margin-right: 15px; }
  </style>
</head>
<body>
  <nav class="navbar sticky-top navbar-light">
    <div class="container-fluid">
      <a class="navbar-brand fs-3" href="http://localhost/cartsy/index/test-9.php">Cartsy</a>
      <form class="d-flex flex-grow-1 mx-3" action="http://localhost/cartsy/search-product/test-4.php" method="GET">
        <div class="input-group">
          <input class="form-control" type="search" name="query" placeholder="Search" required>
          <button class="btn btn-dark" type="submit">Search</button>
        </div>
      </form>
      <a href="http://localhost/cartsy/seller/test-1.php" class="btn btn-outline-dark me-3">Sell</a>
      <a href="http://localhost/cartsy/saved/test-6.php" class="btn btn-outline-danger me-3"><i class="bi bi-heart-fill"></i></a>
      <div>
        <a href="http://localhost/cartsy/chat/conversation.php"><i class="bi bi-chat fs-4 me-3"></i></a>
        <a href="http://localhost/cartsy/profile/index-7.php"><i class="bi bi-person-circle fs-4"></i></a>
      </div>
    </div>
  </nav>

  <div class="container">
    <div class="product-card">
      <div class="row g-4 align-items-center">
        <div class="col-md-6">
          <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
              <?php
              $active = "active";
              foreach ($images as $image) {
                  echo "
                  <div class='carousel-item $active'>
                    <img src='/cartsy/seller/" . htmlspecialchars($image['image_path']) . "' class='d-block w-100 product-image' alt='Product Image'>
                  </div>";
                  $active = "";
              }
              ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
            </button>
          </div>
        </div>

        <div class="col-md-6">
          <div class="d-flex justify-content-between align-items-start">
            <h2 class="product-title"><?= htmlspecialchars($product['product_name']); ?></h2>
            <button class="save-heart"><i class="bi bi-heart"></i><i class="bi bi-heart-fill"></i></button>
          </div>
          <p class="product-price">₱<?= number_format($product['price'], 2); ?></p>
          <p class="product-location"><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($product['location']); ?></p>

          <div class="d-flex gap-2 mb-3">
            <!-- Secure server-side Message form -->
            <form method="POST" action="/cartsy/chat/start_conversation.php" class="flex-grow-1">
              <input type="hidden" name="seller_id" value="<?= htmlspecialchars($seller_id) ?>">
              <input type="hidden" name="product_id" value="<?= htmlspecialchars($product_id) ?>">
              <button type="submit" class="btn btn-warning text-white w-100">Message</button>
            </form>

            <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#reportModal">Report</button>
          </div>

          <div class="details-section">
            <h5>Details</h5>
            <p><strong>Category:</strong> <?= htmlspecialchars($product['category']); ?></p>
            <p><strong>Condition:</strong> <?= htmlspecialchars($product['condition_name']); ?></p>
            <p><strong>Deal Option:</strong> <?= htmlspecialchars($product['deal']); ?></p>
            <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($product['description'])); ?></p>
          </div>

          <div class="details-section mt-4">
            <h5>Seller Information</h5>
            <div class="seller-info">
              <div class="seller-icon">
                <img src="/cartsy/profile/<?= htmlspecialchars($profile_picture); ?>" alt="Seller Profile" style="width:100%;height:100%;object-fit:cover;">
              </div>
              <div>
                <span class="fw-bold"><?= htmlspecialchars($product['seller_name']); ?></span>
                <p class="text-muted mb-0">Trusted Seller</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

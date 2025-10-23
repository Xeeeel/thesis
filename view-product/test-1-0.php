<?php
session_start();
require_once __DIR__ . '/../config/db_config.php';
$pdo = db();

// Use your current session key only
if (empty($_SESSION['user_id'])) {
    header("Location: http://localhost/cartsy/login/login.php");
    exit();
}
$user_id = (int)$_SESSION['user_id'];

header('Content-Type: text/html; charset=UTF-8');

// ---------------------------
// Toggle Save (plain POST)
// ---------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_save'])) {
    $pid = (int)($_POST['product_id'] ?? 0);

    if ($pid > 0) {
        // (optional) ensure the product exists and block saving your own product
        $chkProd = $pdo->prepare("SELECT product_id, seller_id FROM products WHERE product_id = ? LIMIT 1");
        $chkProd->execute([$pid]);
        $prodRow = $chkProd->fetch(PDO::FETCH_ASSOC);

        if ($prodRow && (int)$prodRow['seller_id'] !== $user_id) {
            // Toggle
            $chk = $pdo->prepare("SELECT 1 FROM saved_products WHERE user_id = ? AND product_id = ? LIMIT 1");
            $chk->execute([$user_id, $pid]);

            if ($chk->fetchColumn()) {
                $del = $pdo->prepare("DELETE FROM saved_products WHERE user_id = ? AND product_id = ?");
                $del->execute([$user_id, $pid]);
            } else {
                // ✅ No created_at column in your table — insert only the columns that exist
                $ins = $pdo->prepare("INSERT INTO saved_products (user_id, product_id) VALUES (?, ?)");
                $ins->execute([$user_id, $pid]);
            }
        }
    }

    // PRG: Redirect so refresh won't resubmit
    header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $pid);
    exit();
}

// ---------------------------
// Validate product ID (GET)
// ---------------------------
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($product_id <= 0) {
    die("<p>Invalid product ID.</p>");
}

// ---------------------------
// Fetch product
// ---------------------------
$stmt = $pdo->prepare("
    SELECT p.*, u.name AS seller_name, u.profile_picture
    FROM products p
    JOIN users u ON p.seller_id = u.id
    WHERE p.product_id = :id
");
$stmt->execute([':id' => $product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    die("<p>Product not found.</p>");
}

$seller_id = (int)$product['seller_id'];
$profile_picture = !empty($product['profile_picture']) ? $product['profile_picture'] : 'default.png';

// ---------------------------
// Fetch images (uses image_id)
// ---------------------------
$stmt_images = $pdo->prepare("
    SELECT image_path
    FROM product_images
    WHERE product_id = :id
    ORDER BY image_id ASC
");
$stmt_images->execute([':id' => $product_id]);
$images = $stmt_images->fetchAll(PDO::FETCH_ASSOC);

// ---------------------------
// Saved state for this user
// ---------------------------
$stmt_saved = $pdo->prepare("SELECT 1 FROM saved_products WHERE user_id = :uid AND product_id = :pid LIMIT 1");
$stmt_saved->execute([':uid' => $user_id, ':pid' => $product_id]);
$isSaved = (bool)$stmt_saved->fetchColumn();

// Helper
function e($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= e($product['product_name']) ?> | Cartsy</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Suranna&display=swap" rel="stylesheet" />
  <style>
    body { font-family: "Poppins", sans-serif; background-color: #f8f9fa; }
    .navbar { background-color: #fff; border-bottom: 1px solid #e0e0e0; }
    .navbar-brand { color: #343a40; font-family: "Suranna", serif; font-size: 30px; }
    .product-card { background-color: #fff; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); padding: 40px; max-width: 1200px; margin: 30px auto; }
    .product-image { width: 100%; height: 100%; object-fit: contain; border-radius: 8px; background: #fafafa; }
    .product-title { font-weight: 700; font-size: 1.8rem; color: #333; }
    .product-price { color: #ff5555; font-weight: bold; font-size: 1.6rem; margin-top: 5px; }
    .product-location { color: #666; font-size: 1rem; margin-bottom: 15px; }
    .details-section { margin-top: 30px; }
    .seller-info { background-color: #fff3cd; padding: 20px; border-radius: 8px; display: flex; align-items: center; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    .seller-icon { background-color: #fbc02d; width: 60px; height: 60px; border-radius: 50%; overflow: hidden; margin-right: 15px; }
    .save-heart { border: none; background: none; padding: 4px; line-height: 1; display: inline-flex; align-items: center; gap: 6px; cursor: pointer; color: #ff5555; }
    .save-heart i { font-size: 1.35rem; }
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
      <a href="http://localhost/cartsy/saved/saved.php" class="btn btn-outline-danger me-3"><i class="bi bi-heart-fill"></i></a>
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
              if (!empty($images)) {
                  $active = "active";
                  foreach ($images as $image) {
                      echo "
                      <div class='carousel-item $active'>
                        <img src='/cartsy/seller/" . e($image['image_path']) . "' class='d-block w-100 product-image' alt='Product Image'>
                      </div>";
                      $active = "";
                  }
              } else {
                  echo "
                  <div class='carousel-item active'>
                    <img src='/cartsy/assets/default-image.jpg' class='d-block w-100 product-image' alt='No image available'>
                  </div>";
              }
              ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Next</span>
            </button>
          </div>
        </div>

        <div class="col-md-6">
          <div class="d-flex justify-content-between align-items-start">
            <h2 class="product-title"><?= e($product['product_name']); ?></h2>

            <!-- SIMPLE SAVE/UNSAVE: POST to THIS page -->
            <form method="POST" action="" class="m-0">
              <input type="hidden" name="product_id" value="<?= (int)$product_id ?>">
              <button type="submit" name="toggle_save" class="save-heart" title="<?= $isSaved ? 'Unsave' : 'Save' ?>">
                <?php if ($isSaved): ?>
                  <i class="bi bi-heart-fill"></i> <span class="label">Saved</span>
                <?php else: ?>
                  <i class="bi bi-heart"></i> <span class="label">Save</span>
                <?php endif; ?>
              </button>
            </form>
          </div>

          <p class="product-price">₱<?= number_format((float)$product['price'], 2); ?></p>
          <p class="product-location"><i class="bi bi-geo-alt"></i> <?= e($product['location']); ?></p>

          <div class="d-flex gap-2 mb-3">
            <form method="POST" action="/cartsy/chat/start_conversation.php" class="flex-grow-1">
              <input type="hidden" name="seller_id" value="<?= (int)$seller_id ?>">
              <input type="hidden" name="product_id" value="<?= (int)$product_id ?>">
              <button type="submit" class="btn btn-warning text-white w-100">Message</button>
            </form>

            <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#reportModal">Report</button>
          </div>

          <div class="details-section">
            <h5>Details</h5>
            <p><strong>Category:</strong> <?= e($product['category']); ?></p>
            <p><strong>Condition:</strong> <?= e($product['condition_name'] ?? '—'); ?></p>
            <p><strong>Deal Option:</strong> <?= e($product['deal']); ?></p>
            <p><strong>Description:</strong> <?= nl2br(e($product['description'])); ?></p>
          </div>

          <div class="details-section mt-4">
            <h5>Seller Information</h5>
            <div class="seller-info">
              <div class="seller-icon">
                <img src="/cartsy/profile/<?= e($profile_picture); ?>" alt="Seller Profile" style="width:100%;height:100%;object-fit:cover;">
              </div>
              <div>
                <span class="fw-bold"><?= e($product['seller_name']); ?></span>
                <p class="text-muted mb-0">Trusted Seller</p>
              </div>
            </div>
          </div>
        </div><!-- /col -->
      </div><!-- /row -->
    </div><!-- /card -->
  </div><!-- /container -->

  <!-- Report Modal (skeleton) -->
  <div class="modal fade" id="reportModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Report Listing</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Please describe the issue with this listing.</p>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

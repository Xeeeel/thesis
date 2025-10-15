<?php
// Start session
session_start();

// Database connection
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'cartsy';

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$product = null;
$images = [];

// Check if product ID is passed
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $product_id = intval($_GET['id']);

    // Fetch product info with seller name
    $query = "SELECT p.*, u.name AS seller_name FROM products p
              JOIN users u ON p.seller_id = u.id
              WHERE p.product_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();

        // Fetch product images
        $imgQuery = "SELECT image_path FROM product_images WHERE product_id = ?";
        $imgStmt = $conn->prepare($imgQuery);
        $imgStmt->bind_param("i", $product_id);
        $imgStmt->execute();
        $imgResult = $imgStmt->get_result();
        while ($img = $imgResult->fetch_assoc()) {
            $images[] = $img['image_path'];
        }
        $imgStmt->close();
    }

    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Seller Product Review</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f9f9f9;
    }
    .card {
      border-radius: 1rem;
    }
    hr {
      border-top: 1px solid #dee2e6;
    }
    .img-fluid {
      border-radius: 10px;
    }
  </style>
</head>
<body>
  <div class="container my-5">
    <div class="card shadow-lg p-4">
      <h3 class="mb-4 text-primary">🧾 Product Review</h3>

      <?php if ($product): ?>
        <!-- Product Info -->
        <div class="mb-4">
          <h5 class="text-secondary">Product Information</h5>
          <hr />
          <div class="row">
            <div class="col-md-6">
              <p><strong>Product Name:</strong> <?= htmlspecialchars($product['product_name']) ?></p>
              <p><strong>Category:</strong> <?= htmlspecialchars($product['category']) ?></p>
              <p><strong>Price:</strong> ₱<?= number_format($product['price'], 2) ?></p>
            </div>
            <div class="col-md-6">
              <p><strong>Condition:</strong> <?= htmlspecialchars($product['condition_name']) ?></p>
              <p><strong>Deal Option:</strong> <?= htmlspecialchars($product['deal']) ?></p>
              <p><strong>Location:</strong> <?= htmlspecialchars($product['location']) ?></p>
            </div>
          </div>
        </div>

        <!-- Description -->
        <div class="mb-4">
          <h5 class="text-secondary">Product Description</h5>
          <hr />
          <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
        </div>

        <!-- Images -->
        <div class="mb-4">
          <h5 class="text-secondary">Product Images</h5>
          <hr />
          <div class="row">
            <?php if ($images): ?>
              <?php foreach ($images as $image): ?>
                <div class="col-md-4 mb-3">
                  <img src="/cartsy/seller/<?= htmlspecialchars($image) ?>" class="img-fluid" alt="Product Image">
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <p>No images available for this product.</p>
            <?php endif; ?>
          </div>
        </div>

        <!-- Status -->
        <div class="mb-4">
          <p><strong>Review Status:</strong>
            <span class="badge 
              <?= $product['product_status'] === 'approved' ? 'bg-success' : 
                   ($product['product_status'] === 'rejected' ? 'bg-danger' : 'bg-warning') ?>">
              <?= htmlspecialchars(ucfirst($product['product_status'])) ?>
            </span>
          </p>
        </div>

        <!-- Actions -->
        <form method="post" action="update_product_status.php">
          <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
          <div class="d-flex justify-content-end gap-2">
            <button type="submit" name="status" value="approved" class="btn btn-success">✅ Approve</button>
            <button type="submit" name="status" value="rejected" class="btn btn-danger">❌ Reject</button>
          </div>
        </form>

      <?php else: ?>
        <div class="alert alert-warning">Product not found.</div>
      <?php endif; ?>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
session_start();
require_once __DIR__ . '/../config/db_config.php';
$pdo = db(); // Use your shared PDO config

// Ensure the user is logged in; if not, redirect to the login page
if (empty($_SESSION['user_id'])) {
    header("Location: http://localhost/cartsy/login/login.php"); // Redirect to login if not authenticated
    exit();
}

$sellerId = $_SESSION['user_id'];

// ✅ Handle AJAX requests (status update or deletion)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    // ✅ Update product status
    if (isset($_POST['product_id'], $_POST['status'])) {
        $stmt = $pdo->prepare("UPDATE products SET status = :status WHERE product_id = :product_id AND seller_id = :seller_id");
        $stmt->execute([
            ':status' => $_POST['status'],
            ':product_id' => $_POST['product_id'],
            ':seller_id' => $sellerId
        ]);
        echo json_encode(['status' => 'success']);
        exit();
    }

    // ✅ Delete product
    if (isset($_POST['delete_product_id'])) {
        $stmt = $pdo->prepare("DELETE FROM products WHERE product_id = :product_id AND seller_id = :seller_id");
        $stmt->execute([
            ':product_id' => $_POST['delete_product_id'],
            ':seller_id' => $sellerId
        ]);
        echo json_encode(['status' => 'success']);
        exit();
    }
}

// ✅ Fetch approved products for this seller
$stmt = $pdo->prepare("
    SELECT product_id, product_name, price, thumbnail, condition_name, status
    FROM products
    WHERE seller_id = :seller_id AND product_status = 'approved'
");
$stmt->execute([':seller_id' => $sellerId]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cartsy Selling Page</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

  <style>
    body {
      font-family: "Poppins", sans-serif;
      background-color: #f5f5f5;
    }
    .navbar { background-color: #fff; border-bottom: 1px solid #e0e0e0; padding: 1rem 2rem; }
    .navbar-brand { color: #343a40; font-family: "Suranna", serif; font-size: 30px; }
    .sidebar { width: 250px; background: #fff; padding: 20px; height: 100vh; border-right: 1px solid #ddd; position: fixed; }
    .main-content { margin-left: 250px; padding: 20px; background: #e3e3e3; min-height: 100vh; }
    .card { border-radius: 10px; box-shadow: 0 3px 6px rgba(0,0,0,0.1); transition: transform .2s; }
    .card:hover { transform: scale(1.05); }
    .card img { height: 200px; object-fit: cover; border-radius: 5px; }
    .delete-product { cursor: pointer; }
  </style>
</head>

<body>
  <!-- Navbar -->
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
      <a href="http://localhost/cartsy/saved/test-6.php" class="btn btn-outline-danger me-3">
        <i class="bi bi-heart-fill"></i>
      </a>
      <div>
        <a href="http://localhost/cartsy/chat/conversation.php"><i class="bi bi-chat fs-4 me-3"></i></a>
        <a href="http://localhost/cartsy/profile/index-7.php"><i class="bi bi-person-circle fs-4"></i></a>
      </div>
    </div>
  </nav>

  <div class="d-flex">
    <aside class="sidebar">
      <button class="btn btn-light w-100 text-danger fw-bold" onclick="window.location.href='beta_post.php'">+ Create new listing</button>
      <button class="btn btn-secondary w-100 mt-2"><i class="bi bi-bag"></i> Your Listing</button>

      <div class="filters mt-3">
        <p class="text-muted d-flex justify-content-between">Filters <span class="text-danger">Clear</span></p>
        <p class="mb-1">Sort by <i class="bi bi-chevron-down"></i></p>
        <p id="status-toggle" style="cursor: pointer;">Status <i class="bi bi-chevron-up"></i></p>
        <div id="status-options">
          <label><input type="radio" name="status" value="all" checked> All</label><br>
          <label><input type="radio" name="status" value="available"> Available & in stock</label><br>
          <label><input type="radio" name="status" value="sold"> Sold & out of stock</label><br>
          <label><input type="radio" name="status" value="draft"> Draft</label>
        </div>
      </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content" style="width: 100%;>
      <div class="container">
        <div class="row g-4">
          <?php if ($products): ?>
            <?php foreach ($products as $product): ?>
              <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card position-relative">
                  <i class="bi bi-trash text-secondary position-absolute top-0 end-0 m-2 fs-5 delete-product" data-product-id="<?= $product['product_id'] ?>"></i>
                  <img src="<?= htmlspecialchars($product['thumbnail']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['product_name']) ?>">
                  <div class="card-body">
                    <h6 class="fw-bold"><?= htmlspecialchars($product['product_name']) ?></h6>
                    <p class="text-muted"><?= htmlspecialchars($product['condition_name']) ?></p>
                    <p class="text-danger fw-bold fs-5">₱<?= number_format($product['price'], 2) ?></p>

                    <?php
                      $status = $product['status'];
                      $newStatus = ($status === 'Sold') ? 'Available' : 'Sold';
                      $buttonText = ($status === 'Sold') ? 'Mark as Available' : 'Mark as Sold';
                    ?>

                    <div class="d-flex justify-content-between">
                      <button class="btn btn-dark mark-status"
                        data-product-id="<?= $product['product_id'] ?>"
                        data-status="<?= $newStatus ?>"><?= $buttonText ?></button>
                      <a href="post-1-6.php?product_id=<?= $product['product_id'] ?>" class="btn btn-warning">Edit</a>
                    </div>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p>No products found for this seller.</p>
          <?php endif; ?>
        </div>
      </div>
    </main>
  </div>

  <script>
    // ✅ Delete product
    document.querySelectorAll('.delete-product').forEach(icon => {
      icon.addEventListener('click', function() {
        const productId = this.dataset.productId;
        if (confirm("Are you sure you want to delete this product?")) {
          fetch('', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'delete_product_id=' + productId
          }).then(res => res.json()).then(data => {
            if (data.status === 'success') this.closest('.col-lg-3').remove();
          });
        }
      });
    });

    // ✅ Toggle product status
    document.querySelectorAll('.mark-status').forEach(button => {
      button.addEventListener('click', function() {
        const productId = this.dataset.productId;
        const newStatus = this.dataset.status;
        const btn = this;
        fetch('', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: 'product_id=' + productId + '&status=' + newStatus
        }).then(res => res.json()).then(() => {
          btn.textContent = (newStatus === 'Sold') ? 'Mark as Available' : 'Mark as Sold';
          btn.dataset.status = (newStatus === 'Sold') ? 'Available' : 'Sold';
        });
      });
    });
  </script>
</body>
</html>

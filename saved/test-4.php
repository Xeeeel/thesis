<?php
session_start(); // Start the session to access user data
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cartsy";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['id']; // Assuming 'id' is stored in the session and represents the logged-in user

// Query to get all saved products for the logged-in user, including product images and status
$query = "
    SELECT p.product_id, p.product_name, p.price, p.thumbnail, p.status, 
           p.location, u.name AS seller_name, pi.image_path 
    FROM saved_products sp
    JOIN products p ON sp.product_id = p.product_id
    LEFT JOIN product_images pi ON p.product_id = pi.product_id
    LEFT JOIN users u ON p.seller_id = u.id
    WHERE sp.user_id = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id); // Bind the user ID to the query
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

    .header-row {
      font-weight: bold;
      padding: 10px 15px;
      border-bottom: 2px solid #dee2e6;
    }

    .header-row > div {
      flex: 1;
      text-align: center;
    }

    .product-card > .price-col,
    .product-card > .action-col,
    .product-card > .contact-col {
      text-align: center;
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

    .btn-dark:hover {
      background-color: #343a40;
    }
  </style>
</head>
<body>

<nav class="navbar sticky-top navbar-light">
  <div class="container-fluid">
    <a class="navbar-brand fs-3" href="#">Cartsy</a>

    <form class="d-flex flex-grow-1 mx-3" action="#" method="GET">
      <div class="input-group">
        <input class="form-control" type="search" name="query" placeholder="Search" required>
        <button class="btn btn-dark" type="submit">Search</button>
      </div>
    </form>

    <a href="#" class="btn btn-outline-dark me-3">Sell</a>

    <a href="#" class="btn btn-outline-danger me-3">
      <i class="bi bi-heart-fill"></i>
    </a>

    <div>
      <i class="bi bi-chat fs-4 me-3"></i>
      <i class="bi bi-person-circle fs-4"></i>
    </div>
  </div>
</nav>

<div class="container mt-4">
  <h5>Saved Products</h5>
  <!-- Header Row -->
  <div class="d-md-flex product-card fw-bold bg-light text-dark">
    <div class="col-md-6 d-flex align-items-center">
      Product
    </div>
    <div class="col-md-2 text-center">
      Unit Price
    </div>
    <div class="col-md-2 text-center">
      Actions
    </div>
    <div class="col-md-2 text-center">
      Contact
    </div>
  </div>

  <?php while ($row = $result->fetch_assoc()): ?>
    <?php
      $seller_name = isset($row['seller_name']) ? $row['seller_name'] : 'Unknown Seller';
      $location = isset($row['location']) ? $row['location'] : 'Location not available';

      // Check if thumbnail exists, if not, use a default image
      $thumbnail = !empty($row['thumbnail']) ? "/cartsy/seller/" . htmlspecialchars($row['thumbnail']) : "/cartsy/assets/default-image.jpg"; // Fallback image
    ?>
    <div class="seller-name"><?php echo htmlspecialchars($seller_name); ?></div>
    <div class="d-md-flex product-card align-items-center">
      <div class="col-md-6 d-flex align-items-center">
        <!-- Product Image -->
        <img src="<?php echo htmlspecialchars($thumbnail); ?>" alt="Product" class="product-image">
        <div class="product-info">
          <div class="product-title"><?php echo htmlspecialchars($row['product_name']); ?></div>
          <div class="product-location"><?php echo htmlspecialchars($location); ?></div>
        </div>
      </div>
      <div class="col-md-2 text-center">₱<?php echo number_format($row['price'], 2); ?></div>
      <div class="col-md-2 text-center">
        <button class="btn btn-outline-danger btn-sm">Delete</button>
      </div>
      <div class="col-md-2 text-center">
        <button class="btn btn-outline-primary btn-sm">Message</button>
      </div>
    </div>
  <?php endwhile; ?>
</div>

</body>
</html>

<?php
// Close the database connection
$stmt->close();
$conn->close();
?>

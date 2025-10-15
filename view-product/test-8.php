<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cartsy Product Page</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="index.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Suranna&display=swap" rel="stylesheet" />

    <style>
       body {
           font-family: "Poppins", sans-serif;
           background-color: #f8f9fa;
       }

       .navbar {
           background-color: #ffffff;
           border-bottom: 1px solid #e0e0e0;
       }

       .navbar-brand {
           color: #343a40;
           font-family: "Suranna", serif;
           font-size: 30px;
       }

       .product-card {
           background-color: white;
           border-radius: 10px;
           box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
           padding: 40px;
           max-width: 1200px;
           margin: 30px auto;
       }
/* Add this to your CSS to make the chat icon clickable */
.bi-chat {
    position: relative;
    z-index: 10; /* Ensure it's on top */
    cursor: pointer; /* Change the cursor to indicate it's clickable */
}
       .image-wrapper {
           padding: 10px;
           background-color: #f5f5f5;
           border-radius: 8px;
           text-align: center;
           height: 500px;
           display: flex;
           justify-content: center;
           align-items: center;
           overflow: hidden;
       }

       .product-image {
           width: 100%;
           height: 100%;
           object-fit: contain;
           border-radius: 8px;
       }

       .product-title {
           font-weight: 700;
           font-size: 1.8rem;
           color: #333;
       }

       .product-price {
           color: #ff5555;
           font-weight: bold;
           font-size: 1.6rem;
           margin-top: 5px;
       }

       .product-location {
           color: #666;
           font-size: 1rem;
           margin-bottom: 15px;
       }

       .btn-warning {
           background-color: #d6a842;
           border-color: #d6a842;
       }

       .btn-warning:hover {
           background-color: #c0933a;
       }

       .details-section {
           margin-top: 30px;
       }

       .details-section h5 {
           font-size: 1.3rem;
           color: #343a40;
           font-weight: bold;
       }

       .seller-info {
           background-color: #fff3cd;
           padding: 20px;
           border-radius: 8px;
           display: flex;
           align-items: center;
           box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
       }

       .seller-icon {
           background-color: #fbc02d;
           width: 60px;
           height: 60px;
           border-radius: 50%;
           display: flex;
           align-items: center;
           justify-content: center;
           color: white;
           font-size: 2rem;
           margin-right: 15px;
       }

       .save-heart {
    border: none;
    background: none;
    color: #ff5555;
    font-size: 1.8rem;
    padding: 5px;
    transition: transform 0.3s ease-in-out;
  }

  .save-heart:hover {
    transform: scale(1.3);
  }

  .save-heart .bi-heart-fill {
    display: none;
  }

  .save-heart.active .bi-heart {
    display: none;
  }

  .save-heart.active .bi-heart-fill {
    display: inline;
  }
    </style>
</head>
<body>
    <nav class="navbar sticky-top navbar-light">
      <div class="container-fluid">
        <!-- Brand -->
        <a class="navbar-brand fs-3" href="http://localhost/cartsy/index/test-8.php">Cartsy</a>

        <!-- Search Bar with Button Inside -->
        <form class="d-flex flex-grow-1 mx-3" action="http://localhost/cartsy/search-product/test-4.php" method="GET">
              <div class="input-group">
                  <input class="form-control" type="search" name="query" placeholder="Search" required>
                  <button class="btn btn-dark" type="submit">Search</button>
              </div>
          </form>

        <!-- Sell Button -->
        <button class="btn btn-outline-dark me-3">Sell</button>

        <!-- Saved Products Button -->
          <a href="http://localhost/cartsy/saved-products.php" class="btn btn-outline-danger me-3">
            <i class="bi bi-heart-fill"></i>
          </a>

        <!-- Chat and Profile Icons -->
        <!-- Chat Icon in Navbar -->
          <div>
            <i class="bi bi-chat fs-4 me-3" onclick="startChat(<?php echo $product['seller_id']; ?>, <?php echo $product['product_id']; ?>)"></i>
            <i class="bi bi-person-circle fs-4"></i>
          </div>

      </div>
    </nav>

    <div class="container">
        <div class="product-card">
            <?php
$conn = new mysqli('localhost', 'root', '', 'cartsy');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Validate product_id to prevent SQL injection
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($product_id <= 0) {
    die("<p>Invalid product ID.</p>");
}

// Fetch product details along with the seller's profile picture
$product_sql = "
    SELECT p.*, u.name AS seller_name, u.profile_picture 
    FROM products p
    JOIN users u ON p.seller_id = u.id
    WHERE p.product_id = ?";
$stmt = $conn->prepare($product_sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$product_result = $stmt->get_result();

if ($product_result->num_rows > 0) {
    $product = $product_result->fetch_assoc();

    // Fetch product images
    $image_sql = "SELECT image_path FROM product_images WHERE product_id = ?";
    $stmt_images = $conn->prepare($image_sql);
    $stmt_images->bind_param("i", $product_id);
    $stmt_images->execute();
    $image_result = $stmt_images->get_result();

    // Handle profile picture
    $profile_picture = !empty($product['profile_picture']) ? $product['profile_picture'] : 'default.png';

    echo "
    <div class='row g-4 align-items-center'>
      <div class='col-md-6'>
        <div id='productCarousel' class='carousel slide' data-bs-ride='carousel'>
          <div class='carousel-inner'>";

    $active = "active"; // Set the first image as active
    while ($image = $image_result->fetch_assoc()) {
        echo "
          <div class='carousel-item $active'>
            <img src='/cartsy/seller/" . htmlspecialchars($image['image_path']) . "' class='d-block w-100 product-image' alt='Product Image'>
          </div>";
        $active = ""; // Remove active class after the first image
    }

    echo "
          </div>
          <button class='carousel-control-prev' type='button' data-bs-target='#productCarousel' data-bs-slide='prev'>
            <span class='carousel-control-prev-icon' aria-hidden='true'></span>
          </button>
          <button class='carousel-control-next' type='button' data-bs-target='#productCarousel' data-bs-slide='next'>
            <span class='carousel-control-next-icon' aria-hidden='true'></span>
          </button>
        </div>
      </div>

      <div class='col-md-6'>
        <div class='d-flex justify-content-between align-items-start'>
          <h2 class='product-title'>" . htmlspecialchars($product['product_name']) . "</h2>
          <button class='save-heart'>
            <i class='bi bi-heart'></i><i class='bi bi-heart-fill'></i>
          </button>
        </div>
        <p class='product-price'>₱" . number_format($product['price'], 2) . "</p>
        <p class='product-location'><i class='bi bi-geo-alt'></i> " . htmlspecialchars($product['location']) . "</p>

        <div class='d-flex gap-2 mb-3'>
          <button class='btn btn-warning text-white flex-grow-1' onclick='startChat(" . $product['seller_id'] . ", " . $product['product_id'] . ")'>Message</button>
          

          <button class='btn btn-dark'>Report</button>
        </div>

        <div class='details-section'>
          <h5>Details</h5>
          <p><strong>Category:</strong> " . htmlspecialchars($product['category']) . "</p>
          <p><strong>Condition:</strong> " . htmlspecialchars($product['condition_name']) . "</p>
          <p><strong>Location:</strong> " . htmlspecialchars($product['location']) . "</p>
          <p><strong>Deal Option:</strong> " . htmlspecialchars($product['deal']) . "</p>
          <p><strong>Description:</strong> " . nl2br(htmlspecialchars($product['description'])) . "</p>
        </div>

        <div class='details-section mt-4'>
          <h5>Seller Information</h5>
          <div class='seller-info'>
            <div class='seller-icon' style='width: 60px; height: 60px; overflow: hidden; border-radius: 50%;'>
              <img src='/cartsy/profile/" . htmlspecialchars($profile_picture) . "' 
                   alt='Seller Profile' 
                   style='width: 100%; height: 100%; object-fit: cover; border-radius: 50%;'>
            </div>
            <div>
              <span class='fw-bold'>" . htmlspecialchars($product['seller_name']) . "</span>
              <p class='text-muted mb-0'>Trusted Seller</p>
            </div>
          </div>
        </div>
      </div>
    </div>";
} else {
    echo "<p>Product not found.</p>";
}

$conn->close();
?>



        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.js"></script>

    <script>
      document.querySelectorAll(".save-heart").forEach((button) => {
          button.addEventListener("click", function () {
              this.classList.toggle("active");
          });
      });
    </script>

    <script>
      function startChat(sellerId, productId) {
        // Redirect to the chat page with seller and product info
        window.location.href = `/cartsy/chat/chat-1.php?seller_id=${sellerId}&product_id=${productId}`;
      }

    </script>

</body>
</html>

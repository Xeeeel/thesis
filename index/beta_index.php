<?php
session_start();
require_once __DIR__ . '/../config/db_config.php'; // Include the database configuration
$pdo = db(); // Database connection from db_config.php

// Ensure the user is logged in; if not, redirect to the login page
if (empty($_SESSION['user_id'])) {
    header("Location: http://localhost/cartsy/login/login.php"); // Redirect to login if not authenticated
    exit();
}

// Fetch user data based on the user ID from the session
$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT id, email, seller_status, name FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();


if (!$user) {
    // If the user does not exist in the database (shouldn't happen if session is valid)
    unset($_SESSION['user_id']);
    header("Location: http://localhost/cartsy/login/login.php");
    exit();
}

// Default to 'pending' if seller_status is not set (fallback)
$seller_status = isset($user['seller_status']) ? $user['seller_status'] : 'pending';



// Check if the user has an approved product
$has_approved_product_stmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE user_id = ? AND product_status = 'approved'");
$has_approved_product_stmt->execute([$userId]);
$has_approved_product = $has_approved_product_stmt->fetchColumn() > 0;

// Modify the query to select products with "approved" status and "Available" status
$sql = "SELECT product_id, product_name, price, thumbnail, condition_name FROM products WHERE product_status = 'approved' AND status = 'Available'";  
$stmt = $pdo->prepare($sql);
$stmt->execute();
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cartsy | Home</title>

    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Suranna&display=swap"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"
    />

    <style>
      body {
        font-family: Arial, sans-serif;
      }
      .navbar-brand {
        font-family: "Suranna", serif;
        font-size: 30px;
      }
      .banner {
        display: flex;
        margin: 40px auto 60px auto;
        width: 90%;
        overflow: hidden;
        border-radius: 20px;
        background-color: #2f3b14;
      }

      /* Image Section (Left Half) */
      .banner-image {
        flex: 1;
        border-top-left-radius: 250px;
        border-bottom-left-radius: 250px;
        overflow: hidden;
      }

      .banner-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
      }

      /* Text Section (Right Half) */
      .banner-text {
        flex: 1;
        color: white;
        padding: 40px 0 40px 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
      }

      .banner-text h2 {
        font-size: 1.8rem;
        margin-bottom: 15px;
      }

      .banner-text p {
        margin-bottom: 20px;
      }

      .featured-categories img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 8px;
      }

      .featured-categories .col-6 {
        padding: 0 5px;
      }

      .navbar { background-color: #ffffff; border-bottom: 1px solid #e0e0e0; padding: 1rem 2rem; }
        .navbar-brand { color: #343a40; font-family: "Suranna", serif; font-size: 30px; }

      .featured-categories p {
        margin-top: 15px;
      }

      .product-grid-section {
        background-color: white;
        padding: 100px 30px 30px 30px;
      }
      .card {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        position: relative;
      }
      .card img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 8px;
      }
      .card-body {
        padding: 10px;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
      }
      .card-title {
        font-size: 1rem;
        font-weight: bold;
      }

      .product-card {
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        border-radius: 10px;
      }
      .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
      }
      .price-text {
        font-size: 1rem;
        font-weight: bold;
        color: #333;
        margin-top: auto;
      }
      .product-condition {
        font-size: 0.9rem;
        color: gray;
        margin-bottom: 10px;
      }
      .save-heart {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: rgba(255, 255, 255, 0);
        border: none;
        border-radius: 50%;
        padding: 5px;
        cursor: pointer;
      }
      .save-heart i {
        color: #ff5252;
        font-size: 18px;
      }

      .product-grid h3 {
        text-align: center;
        margin-bottom: 30px;
      }
      .about-section {
        padding: 30px;
        background-color: #f8f9fa;
        text-align: center;
        margin-top: 30px;
      }
      .about-section h4 {
        font-size: 1.5rem;
        margin-bottom: 10px;
      }
    </style>
  </head>
  <body class="bg-light">
    <!-- Navbar -->
    <nav class="navbar sticky-top navbar-light">
        <div class="container-fluid">
            <!-- Brand -->
            <a class="navbar-brand fs-3" href="http://localhost/cartsy/index/test-9.php">Cartsy</a>

            <!-- Search Bar with Button Inside -->
            <form class="d-flex flex-grow-1 mx-3" action="http://localhost/cartsy/search-product/test-4.php" method="GET">
                <div class="input-group">
                    <input class="form-control" type="search" name="query" placeholder="Search" required>
                    <button class="btn btn-dark" type="submit">Search</button>
                </div>
            </form>

            <!-- Sell Button -->
            <?php
            // Conditionally set the URL based on the user's seller status
            if ($seller_status === 'pending') {
                // Redirect to verification page if seller_status is 'pending'
                $sell_url = 'http://localhost/cartsy/seller/identity-1.php'; // Update this with your verification page URL
            } else {
                // Otherwise, link to the regular selling page
                $sell_url = 'http://localhost/cartsy/seller/test-1-0.php'; // Update this URL as per your create listing page
            }
            ?>
            <a href="<?= $sell_url ?>" class="btn btn-outline-dark me-3">Sell</a>

            <!-- Saved Products Button -->
            <a href="http://localhost/cartsy/saved/saved.php" class="btn btn-outline-danger me-3">
                <i class="bi bi-heart-fill"></i>
            </a>

            <!-- Chat and Profile Icons -->
            <div>
                <a href="http://localhost/cartsy/chat/conversation.php">
                    <i class="bi bi-chat fs-4 me-3"></i>
                </a>
                <a href="http://localhost/cartsy/profile/profile_v1.php">
                    <i class="bi bi-person-circle fs-4"></i>
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="banner">
      <div class="banner-text">
        <h2>
          Upgrade your lifestyle with top-quality items at unbeatable prices.
        </h2>
        <p>
          From stylish footwear and fitness gear to essential home tools, we’ve
          got something for everyone. Browse our latest collections and grab the
          best deals today!
        </p>
        <button
          class="btn btn-warning"
          style="width: 50%; margin: auto; border-radius: 20px; font-size: 500"
          onclick="document.getElementById('products').scrollIntoView({ behavior: 'smooth' })"
        >
          Shop now
        </button>
      </div>
      <div class="banner-image">
        <img src="./image/banner.webp" alt="Banner Products" />
      </div>
    </div>

    <!-- Categories -->
    <section id="categories" class="container featured-categories">
  <h3 class="mb-4">Categories</h3>
  <div class="row text-center">
    <div class="col-6 col-md-3 mb-4">
      <a href="http://localhost/cartsy/search-product/test-4.php?category=Women%27s%20Apparel">
        <img
          src="./image/categories/Categories/Womans_Apparel.webp"
          class="img-fluid"
          alt="Women's Apparel"
        />
        <p>Women's Apparel</p>
      </a>
    </div>
    <div class="col-6 col-md-3 mb-4">
      <a href="http://localhost/cartsy/search-product/test-4.php?category=Men%27s%20Apparel">
        <img
          src="./image/categories/Categories/Mens_Apparel.jpg"
          class="img-fluid"
          alt="Men's Apparel"
        />
        <p>Men's Apparel</p>
      </a>
    </div>
    <div class="col-6 col-md-3 mb-4">
      <a href="http://localhost/cartsy/search-product/test-4.php?category=Laptops%20%26%20Computers">
        <img
          src="./image/categories/Categories/Laptopss_&_Computers.jpg"
          class="img-fluid"
          alt="Laptops & Computers"
        />
        <p>Laptops & Computers</p>
      </a>
    </div>
    <div class="col-6 col-md-3 mb-4">
      <a href="http://localhost/cartsy/search-product/test-4.php?category=School%20%26%20Office%20Supplies">
        <img
          src="./image/categories/Categories/Schools_&_Office_Supplies.jpg"
          class="img-fluid"
          alt="School & Office Supplies"
        />
        <p>School & Office Supplies</p>
      </a>
    </div>
    <div class="col-6 col-md-3 mb-4">
      <a href="http://localhost/cartsy/search-product/test-4.php?category=Home%20%26%20Living">
        <img
          src="./image/categories/Categories/Home_&_Living.jpg"
          class="img-fluid"
          alt="Home & Living"
        />
        <p>Home & Living</p>
      </a>
    </div>
    <div class="col-6 col-md-3 mb-4">
      <a href="http://localhost/cartsy/search-product/test-4.php?category=Home%20Appliances">
        <img
          src="./image/categories/Categories/Home_Appliances.jpg"
          class="img-fluid"
          alt="Home Appliances"
        />
        <p>Home Appliances</p>
      </a>
    </div>
    <div class="col-6 col-md-3 mb-4">
      <a href="http://localhost/cartsy/search-product/test-4.php?category=Health%20%26%20Personal%20Care">
        <img
          src="./image/categories/Categories/Healths_&_Personal_Care.jpg"
          class="img-fluid"
          alt="Health & Personal Care"
        />
        <p>Health & Personal Care</p>
      </a>
    </div>
    <div class="col-6 col-md-3 mb-4">
      <a href="http://localhost/cartsy/search-product/test-4.php?category=Books%20%26%20Magazines">
        <img
          src="./image/categories/Categories/Books_&_Magazines.jpg"
          class="img-fluid"
          alt="Books & Magazines"
        />
        <p>Books & Magazines</p>
      </a>
    </div>
  </div>
</section>

    <!-- Product Grid -->
    <section id="products" class="container-fluid product-grid-section">
      <div class="container">
        <h5 class="text-center" style="border-bottom: 5px solid #e3bf69; padding-bottom: 15px; margin-bottom: 30px;">
          DAILY DISCOVER
        </h5>

        <div class="row row-cols-2 row-cols-sm-3 row-cols-lg-4 row-cols-xl-5 g-4">
          <?php
          if ($products && count($products) > 0) {
              foreach ($products as $product) {
                  $product_id = htmlspecialchars($product['product_id']);
                  $product_name = htmlspecialchars($product['product_name']);
                  $price = htmlspecialchars($product['price']);
                  $condition_name = htmlspecialchars($product['condition_name']);
                  $thumbnail = !empty($product['thumbnail']) ? "/cartsy/seller/" . htmlspecialchars($product['thumbnail']) : "/cartsy/assets/default-image.jpg"; // Fallback image

                  // Heart button click form
                  echo "
                  <div class='col'>
                      <a href='/cartsy/view-product/test-1-0.php?id={$product_id}' class='text-decoration-none'>
                          <div class='card product-card'>
                              <div class='position-relative'>
                                  <form method='POST' action=''>
                                      <input type='hidden' name='product_id' value='{$product_id}' />
                                      <button type='submit' class='save-heart' name='save_product'>
                                          <i class='bi bi-heart'></i>
                                      </button>
                                  </form>
                                  <img src='{$thumbnail}' alt='{$product_name}' class='card-img-top'/>
                              </div>
                              <div class='card-body'>
                                  <h5 class='card-title'>{$product_name}</h5>
                                  <p class='product-condition'>{$condition_name}</p>
                                  <p class='price-text'>₱{$price}</p>
                              </div>
                          </div>
                      </a>
                  </div>";
              }
          } else {
              echo "<p>No products found</p>";
          }
          ?>
        </div>
      </div>
    </section>

    <!-- About Section -->
    <section class="about-section">
      <h4>About Cartsy</h4>
      <p>
        Cartsy is an online platform where users can discover a wide variety of products, from fashion to technology, and make purchases with ease.
      </p>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>

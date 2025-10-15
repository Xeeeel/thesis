<?php
// Connect to database
$servername = "localhost";
$username = "root"; // Change if needed
$password = ""; // Change if needed
$dbname = "cartsy"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check for errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get search query and filter parameters
$search_query = isset($_GET['query']) ? $_GET['query'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';
$price_min = isset($_GET['price_min']) ? $_GET['price_min'] : '';
$price_max = isset($_GET['price_max']) ? $_GET['price_max'] : '';
$location = isset($_GET['location']) ? $_GET['location'] : '';
$condition = isset($_GET['condition']) ? $_GET['condition'] : '';
$deal = isset($_GET['deal']) ? $_GET['deal'] : '';

// Prepare the base SQL query
// Prepare the base SQL query
// Prepare the base SQL query
$sql = "SELECT products.*, users.name as user_name 
        FROM products
        JOIN users ON products.seller_id = users.id 
        WHERE products.product_name LIKE ? and
        product_status = 'approved' and status = 'Available'";

// Add conditions based on filters
if ($category != '') {
    $sql .= " AND products.category = ?";
}
if ($price_min != '' || $price_max != '') {
    if ($price_min != '' && $price_max != '') {
        $sql .= " AND products.price BETWEEN ? AND ?";
    } else {
        if ($price_min != '') {
            $sql .= " AND products.price >= ?";
        }
        if ($price_max != '') {
            $sql .= " AND products.price <= ?";
        }
    }
}
if ($location != '') {
    $sql .= " AND products.location LIKE ?";
}
if ($condition != '') {
    $sql .= " AND products.condition_name LIKE ?";
}
if ($deal != '') {
    $sql .= " AND products.deal LIKE ?";
}

// Prepare the statement
$stmt = $conn->prepare($sql);

// Bind parameters to the prepared statement
$params = [];
$types = "s"; // For the search query parameter (string)

$params[] = "%" . $search_query . "%"; // Bind the search query

if ($category != '') {
    $types .= "s";  // Add string type for category
    $params[] = $category; // Bind the category filter
}

if ($price_min != '' || $price_max != '') {
    if ($price_min != '' && $price_max != '') {
        $types .= "ii"; // Integer type for price
        $params[] = $price_min;
        $params[] = $price_max;
    } else {
        if ($price_min != '') {
            $types .= "i";
            $params[] = $price_min;
        }
        if ($price_max != '') {
            $types .= "i";
            $params[] = $price_max;
        }
    }
}

if ($location != '') {
    $types .= "s";
    $params[] = "%" . $location . "%";
}

if ($condition != '') {
    $types .= "s";
    $params[] = "%" . $condition . "%";
}

if ($deal != '') {
    $types .= "s";
    $params[] = "%" . $deal . "%";
}

// Dynamically bind the parameters to the prepared statement
$stmt->bind_param($types, ...$params);

// Execute the statement
$stmt->execute();
$result = $stmt->get_result();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cartsy - Product Listing</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="styles.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Suranna&display=swap" rel="stylesheet">
    <style>
        /* Ensure the card body has a fixed height for consistency */
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

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
}

/* Product image styling to ensure consistency */
.product-image {
    width: 100%;
    height: 220px;
    object-fit: cover;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
    background-color: #f8f8f8;
}

/* Ensuring the card body takes remaining space */


.product-title {
    font-size: 1rem;
    font-weight: bold;
    margin-bottom: 10px;
}

.product-price {
    font-size: 1.1rem;
    font-weight: bold;
    color: #d9534f;
    margin-bottom: 10px;
}



        .navbar { background-color: #ffffff; border-bottom: 1px solid #e0e0e0; padding: 1rem 2rem; }
        .navbar-brand { color: #343a40; font-family: "Suranna", serif; font-size: 30px; }
        .btn-dark:hover { background-color: #343a40; }
        .footer-bg { background-color: #f8f9fa; }
    </style>
</head>
<body>
<!-- Navbar -->
<nav class="navbar sticky-top navbar-expand-lg navbar-white bg-white">
    <div class="container">
      <a class="navbar-brand" href="http://localhost/cartsy/normal/normal_home.php">Cartsy</a>
      <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarContent"
        aria-controls="navbarContent"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarContent">
        <form class="d-flex flex-grow-1 mx-3" action="http://localhost/cartsy/search-product/search_normal.php" method="GET">
            <div class="input-group">
                <input class="form-control" type="search" name="query" placeholder="Search" required>
                <button class="btn btn-dark" type="submit">Search</button>
            </div>
        </form>
        <ul class="navbar-nav ms-3">
          <li class="nav-item">
            <a class="nav-link d-flex align-items-center" href="http://localhost/cartsy/login/login.php">Sell</a>
          </li>
          <li class="nav-item" style="width: 85px">
            <a class="nav-link d-flex align-items-center" href="http://localhost/cartsy/sign-up/sign-up.php">Sign Up</a>
          </li>
          <li class="nav-item" style="width: 75px">
            <a class="nav-link d-flex align-items-center" href="http://localhost/cartsy/login/login.php">Sign In</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

<div class="container mt-4">
    <div class="row">
        <!-- Sidebar -->
        <aside class="col-lg-3 col-md-4 col-sm-12 mb-4 position-sticky" style="top: 110px; height: fit-content;">
            <div class="p-3 border rounded bg-white">
                <h5 class="fw-bold">Filter</h5>
                <form action="http://localhost/cartsy/search-product/search_normal.php" method="GET">
                    <!-- Category Filter -->
                    <h6 class="mt-3">Category</h6>
                    <select name="category" class="form-control mb-2">
                        <option value="">Select Category</option>
                            <option value="Women's Apparel">Women's Apparel</option>
                            <option value="Men's Apparel">Men's Apparel</option>
                            <option value="Laptops & Computers">Laptops & Computers</option>
                            <option value="School & Office Supplies">School & Office Supplies</option>
                            <option value="Home & Living">Home & Living</option>
                            <option value="Home Appliances">Home Appliances</option>
                            <option value="Health & Personal Care">Health & Personal Care</option>
                            <option value="Books & Magazines">Books & Magazines</option>
                    </select>

                    <!-- Price Range Filter -->
                    <h6 class="mt-3">Price Range</h6>
                    <div class="d-flex mb-2">
                        <input type="number" name="price_min" class="form-control me-2" placeholder="Min Price">
                        <input type="number" name="price_max" class="form-control" placeholder="Max Price">
                    </div>

                    <!-- Location Filter -->
                    <h6 class="mt-3">Location</h6>
                    <input type="text" name="location" class="form-control mb-2" placeholder="Location">

                    <!-- Item Condition Filter -->
                    <h6 class="mt-3">Item Condition</h6>
                    <select name="condition" class="form-control mb-2">
                        <option value="">Select Condition</option>
                        <option value="New">New</option>
                        <option value="Used - Like New">Used - Like New</option>
                        <option value="Used - Good">Used - Good</option>
                        <option value="Used - Fair">Used - Fair</option>
                    </select>

                    <!-- Deal Option Filter -->
                    <h6 class="mt-3">Deal Option</h6>
                    <select name="deal" class="form-control mb-2">
                        <option value="">Select Deal Option</option>
                        <option value="Meetup">Meetup</option>
                        <option value="Delivery">Delivery</option>
                    </select>

                    <button class="btn btn-warning w-100">Apply</button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="col-lg-9 col-md-8 col-sm-12">
            <h3 class="mb-3">Search Results for "<?php echo htmlspecialchars($search_query); ?>"</h3>
            <div class="row g-3">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $thumbnail = !empty($row['thumbnail']) ? "/cartsy/seller/" . htmlspecialchars($row['thumbnail']) : "/cartsy/assets/default-image.jpg"; // Fallback image
                        echo '<div class="col-lg-3 col-md-4 col-sm-6">
                            <!-- Wrap the whole card with the <a> tag to make it clickable -->
                            <a href="/cartsy/view-product/normal_view.php?id=' . $row["product_id"] . '" class="text-decoration-none">
                                <div class="card shadow-sm border-0 position-relative product-card">
                                    <img src="' . $thumbnail . '" alt="' . htmlspecialchars($row["product_name"]) . '" class="card-img-top"/>
                                    <i class="bi bi-heart-fill position-absolute top-0 end-0 m-2"></i>
                                    <div class="card-body">
                                        <h5 class="card-title product-title">' . htmlspecialchars($row["product_name"]) . '</h5>
                                        <p class="card-text">' . htmlspecialchars($row["description"]) . '</p>
                                        <p class="product-price">₱' . number_format($row["price"], 2) . '</p>
                                    </div>
                                </div>
                            </a>
                        </div>';
                    }
                } else {
                    echo '<div class="col-12">No results found.</div>';
                }
                ?>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="footer-bg mt-5 py-4">
    <div class="container">
        <p class="text-center m-0">© 2025 Cartsy. All rights reserved.</p>
    </div>
</footer>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

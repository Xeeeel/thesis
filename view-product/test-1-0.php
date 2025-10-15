<?php
session_start();

// Session ID Debugging
if (isset($_SESSION['id'])) {
} else {
    echo "Session ID not set.";
}

// Ensure the user is logged in before proceeding
if (!isset($_SESSION['id'])) {
    die("You need to log in to view this page.");
}

// Get the user ID from session
$user_id = $_SESSION['id']; // Assuming the user ID is stored in the session after login

// Database connection
$conn = new mysqli('localhost', 'root', '', 'cartsy');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch product details
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($product_id <= 0) {
    die("<p>Invalid product ID.</p>");
}

$product_sql = "
    SELECT p.*, u.name AS seller_name, u.profile_picture 
    FROM products p
    JOIN users u ON p.seller_id = u.id
    WHERE p.product_id = ?";
$stmt = $conn->prepare($product_sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$product_result = $stmt->get_result();

// Check if product data was fetched
if ($product_result->num_rows > 0) {
    $product = $product_result->fetch_assoc();
    $seller_id = $product['seller_id'];
    $product_id = $product['product_id']; // Ensure you have the correct product_id
} else {
    echo "<p>Product not found.</p>";
    exit;
}

// Fetch product images
$image_sql = "SELECT image_path FROM product_images WHERE product_id = ?";
$stmt_images = $conn->prepare($image_sql);
$stmt_images->bind_param("i", $product_id);
$stmt_images->execute();
$image_result = $stmt_images->get_result();

// Handle profile picture
$profile_picture = !empty($product['profile_picture']) ? $product['profile_picture'] : 'default.png';

$conn->close();
?>
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

       .bi-chat {
           position: relative;
           z-index: 10;
           cursor: pointer;
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
    </style>
</head>
<body>
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
            <a href="http://localhost/cartsy/seller/test-1.php" class="btn btn-outline-dark me-3">Sell</a>

            <!-- Saved Products Button -->
            <a href="http://localhost/cartsy/saved/test-6.php" class="btn btn-outline-danger me-3">
                <i class="bi bi-heart-fill"></i>
            </a>

            <!-- Chat and Profile Icons -->
            <div>
                <a href="http://localhost/cartsy/chat/conversation.php">
                    <i class="bi bi-chat fs-4 me-3"></i>
                </a>
                <a href="http://localhost/cartsy/profile/index-7.php">
                    <i class="bi bi-person-circle fs-4"></i>
                </a>
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
                            while ($image = $image_result->fetch_assoc()) {
                                echo "
                                    <div class='carousel-item $active'>
                                        <img src='/cartsy/seller/" . htmlspecialchars($image['image_path']) . "' class='d-block w-100 product-image' alt='Product Image'>
                                    </div>";
                                $active = ""; // Remove active class after the first image
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
                        <h2 class="product-title"><?php echo htmlspecialchars($product['product_name']); ?></h2>
                        <button class="save-heart">
                            <i class="bi bi-heart"></i><i class="bi bi-heart-fill"></i>
                        </button>
                    </div>
                    <p class="product-price">₱<?php echo number_format($product['price'], 2); ?></p>
                    <p class="product-location"><i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($product['location']); ?></p>

                    <div class="d-flex gap-2 mb-3">
                        <button class="btn btn-warning text-white flex-grow-1" onclick="startChat(<?php echo $product['seller_id']; ?>, <?php echo $product['product_id']; ?>)">Message</button>
                        <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#reportModal">Report</button>
                    </div>

                    <div class="details-section">
                        <h5>Details</h5>
                        <p><strong>Category:</strong> <?php echo htmlspecialchars($product['category']); ?></p>
                        <p><strong>Condition:</strong> <?php echo htmlspecialchars($product['condition_name']); ?></p>
                        <p><strong>Location:</strong> <?php echo htmlspecialchars($product['location']); ?></p>
                        <p><strong>Deal Option:</strong> <?php echo htmlspecialchars($product['deal']); ?></p>
                        <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                    </div>

                    <div class="details-section mt-4">
                        <h5>Seller Information</h5>
                        <div class="seller-info">
                            <div class="seller-icon">
                                <img src="/cartsy/profile/<?php echo htmlspecialchars($profile_picture); ?>" alt="Seller Profile" class="rounded-circle" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <div>
                                <span class="fw-bold"><?php echo htmlspecialchars($product['seller_name']); ?></span>
                                <p class="text-muted mb-0">Trusted Seller</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Form Modal (Bootstrap) -->
    <div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reportModalLabel">Report Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="reportForm" action="submit_report.php" method="POST">
                        <!-- Product Information (Hidden) -->
                        <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                        <input type="hidden" name="buyer_id" value="<?php echo $_SESSION['id']; ?>">

                        <!-- Reason for Reporting -->
                        <div class="mb-3">
                            <label class="form-label">Reason for Reporting</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="report_reason" value="Fraud" required>
                                <label class="form-check-label">Fraud/Scam</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="report_reason" value="Item Not Received">
                                <label class="form-check-label">Item Not Received</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="report_reason" value="Item Not As Described">
                                <label class="form-check-label">Item Not As Described</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="report_reason" value="Poor Communication">
                                <label class="form-check-label">Poor Communication</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="report_reason" value="Other" id="otherReason" onchange="toggleOtherReasonField()">
                                <label class="form-check-label">Other</label>
                            </div>
                        </div>

                        <!-- Additional Description (only shown if "Other" is selected) -->
                        <div id="otherReasonField" class="mb-3" style="display: none;">
                            <label for="other_description" class="form-label">Describe the issue</label>
                            <textarea class="form-control" id="other_description" name="other_description" rows="3" placeholder="Provide details about the issue"></textarea>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex justify-content-end mt-3">
                            <button type="submit" class="btn btn-danger">Submit Report</button>
                        </div>
                    </form>
                </div>
            </div>
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

        function startChat(sellerId, productId) {
            var buyerId = <?php echo isset($_SESSION['id']) ? $_SESSION['id'] : 'null'; ?>;
            if (buyerId !== null) {
                // First, check if a conversation exists for this buyer, seller, and product
                fetch(`/cartsy/chat/check_conversation.php?buyer_id=${buyerId}&seller_id=${sellerId}&product_id=${productId}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.exists) {
                            // If the conversation exists, redirect to the conversation page
                            window.location.href = `/cartsy/chat/conversation.php?product_id=${productId}&seller_id=${sellerId}&buyer_id=${buyerId}`;
                        } else {
                            // If no conversation exists, create a new conversation
                            fetch(`/cartsy/chat/start_conversation.php?buyer_id=${buyerId}&seller_id=${sellerId}&product_id=${productId}`)
                                .then(() => {
                                    // After creating the conversation, redirect to it
                                    window.location.href = `/cartsy/chat/conversation.php?product_id=${productId}&seller_id=${sellerId}&buyer_id=${buyerId}`;
                                });
                        }
                    });
            } else {
                alert("You need to log in to chat.");
            }
        }

    </script>
</body>
</html>

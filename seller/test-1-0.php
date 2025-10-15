<?php
session_start();  // Move session_start() to the top

if (!isset($_SESSION['id'])) {
    echo "<p>You must be logged in to view your products.</p>";
    exit();
}

$userId = $_SESSION['id'];
$conn = new mysqli("localhost", "root", "", "cartsy");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Update the product's status when the AJAX request is received
if (isset($_POST['product_id']) && isset($_POST['status'])) {
    $productId = $_POST['product_id'];
    $newStatus = $_POST['status'];
    
    // Update the product status in the database
    $updateSql = "UPDATE products SET status = ? WHERE product_id = ?";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("si", $newStatus, $productId);
    $stmt->execute();
    $stmt->close();
    
    // Respond with success
    echo json_encode(['status' => 'success']);
    exit();
}

// Handle the delete request for product
if (isset($_POST['delete_product_id'])) {
    $productIdToDelete = $_POST['delete_product_id'];

    // Delete the product from the database
    $deleteSql = "DELETE FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($deleteSql);
    $stmt->bind_param("i", $productIdToDelete);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete product']);
    }
    $stmt->close();
    exit();
}

$sql = "SELECT product_id, product_name AS product_name, price, thumbnail AS thumbnail, condition_name, status 
        FROM products 
        WHERE seller_id = ? AND product_status = 'approved'";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cartsy Selling Page</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <style>
        body {
            font-family: "Poppins", sans-serif;
            background-color: #f5f5f5;
            margin: 0;
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

        .sidebar {
            width: 250px;
            background: #ffffff;
            padding: 20px;
            height: 100vh;
            border-right: 1px solid #ddd;
            position: fixed;
        }

        .sidebar .btn {
            padding: 10px;
            text-align: left;
            width: 100%;
            margin-bottom: 10px;
        }

        .filters label {
            font-size: 14px;
            color: #444;
        }

        .main-content {
            margin-left: 250px;  /* Adjust to make room for the sidebar */
            padding: 20px;
            background: #e3e3e3;
            flex-grow: 1;
        }

        .card {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%; /* Ensures the card stretches to fill available height */
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease-in-out;
            border-radius: 10px; /* Rounded corners */
        }

        .card:hover {
            transform: scale(1.05); /* Slightly larger hover effect */
        }

        .card-body {
            flex-grow: 1;
            padding: 15px; /* Adjust padding for better spacing */
            display: flex;
            flex-direction: column;
        }

        .card img {
            height: 200px; /* Fixed height */
            object-fit: cover; /* Ensures the image fits without stretching */
            border-radius: 5px;
        }

        .card h6, .card p {
            font-size: 14px; /* Adjust font size to fit better */
            margin-bottom: 5px; /* Reduce spacing between text elements */
        }

        .card .text-danger {
            font-size: 16px; /* Adjust price size for better visual hierarchy */
        }

        .btn-dark {
            background-color: #343a40;
            border-color: #343a40;
        }

        .btn-dark:hover {
            background-color: #212529;
        }

        .btn-light {
            background-color: #f8f9fa;
            border-color: #f8f9fa;
        }

        .btn-light:hover {
            background-color: #e2e6ea;
        }

        .bi-heart-fill, .bi-chat, .bi-person-circle {
            color: #343a40;
        }

        .bi-heart-fill:hover, .bi-chat:hover, .bi-person-circle:hover {
            color: #dc3545;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .navbar .container-fluid {
                flex-direction: column;
                align-items: flex-start;
            }

            .main-content {
                margin-left: 0;  /* Remove margin on smaller screens */
            }

            .sidebar {
                position: static;  /* Let the sidebar flow with the content */
                width: 100%;
            }

            .card {
                height: auto; /* Cards will adapt to content height on small screens */
            }
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

    <div class="d-flex">
        <aside class="sidebar">
            <button class="btn btn-light w-100 text-danger fw-bold" onclick="window.location.href='redirect.php'">+ Create new listing</button>

            <button class="btn btn-secondary">
                <i class="bi bi-bag"></i> Your Listing
            </button>

            <div class="filters mt-3">
                <p class="text-muted d-flex justify-content-between">Filters <span class="text-danger">Clear</span></p>

                <p class="mb-1">Sort by <i class="bi bi-chevron-down"></i></p>
                <p id="status-toggle" style="cursor: pointer;">
                    Status <i class="bi bi-chevron-up"></i>
                </p>

                <div id="status-options" style="display: block;">
                    <label><input type="radio" name="status" value="all" checked> All</label><br>
                    <label><input type="radio" name="status" value="available"> Available & in stock</label><br>
                    <label><input type="radio" name="status" value="sold"> Sold & out of stock</label><br>
                    <label><input type="radio" name="status" value="draft"> Draft</label>
                </div>
            </div>
        </aside>

        <main class="main-content">
            <div class="container">
                <div class="row g-4">
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<div class="col-lg-3 col-md-4 col-sm-6">';
                            echo '<div class="card position-relative">';
                            echo '<i class="bi bi-trash text-secondary position-absolute top-0 end-0 m-2 fs-5 delete-product" data-product-id="' . $row['product_id'] . '"></i>';
                            echo '<img src="' . htmlspecialchars($row['thumbnail']) . '" class="card-img-top" alt="' . htmlspecialchars($row['product_name']) . '">';
                            echo '<div class="card-body">';
                            echo '<h6 class="fw-bold">' . htmlspecialchars($row['product_name']) . '</h6>';
                            echo '<p class="text-muted">' . htmlspecialchars($row['condition_name']) . '</p>';
                            echo '<p class="text-danger fw-bold fs-5">₱' . number_format($row['price'], 2) . '</p>';
                            
                            // Button logic based on product status
                            $status = $row['status'];
                            if ($status == 'Sold') {
                                $buttonText = 'Mark as Available';
                                $newStatus = 'Available';
                            } else {
                                $buttonText = 'Mark as Sold';
                                $newStatus = 'Sold';
                            }

                            echo '<div class="d-flex justify-content-between">';
                            echo '<button class="btn btn-dark mark-status" data-product-id="' . $row['product_id'] . '" data-status="' . $newStatus . '">' . $buttonText . '</button>';
                            echo '<a href="post-1-6.php?product_id=' . $row['product_id'] . '" class="btn btn-warning">Edit</a>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo 'No products found for this seller.';
                    }
                    ?>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Event listener for the delete trash icon
        document.querySelectorAll('.delete-product').forEach(icon => {
            icon.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                const icon = this;

                // Show confirmation dialog before deleting
                if (confirm("Are you sure you want to delete this product?")) {
                    // Send an AJAX request to delete the product
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', '', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            const response = JSON.parse(xhr.responseText);
                            if (response.status === 'success') {
                                // Remove the product card from the DOM
                                icon.closest('.col-lg-3').remove();
                            } else {
                                alert('Error: ' + response.message);
                            }
                        }
                    };
                    xhr.send('delete_product_id=' + productId);
                }
            });
        });

        // Event listener for the mark-status button
        document.querySelectorAll('.mark-status').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                const newStatus = this.getAttribute('data-status');
                const button = this;
                
                const xhr = new XMLHttpRequest();
                xhr.open('POST', '', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        // Update button text
                        if (newStatus === 'Sold') {
                            button.innerText = 'Mark as Available';
                            button.setAttribute('data-status', 'Available');
                        } else {
                            button.innerText = 'Mark as Sold';
                            button.setAttribute('data-status', 'Sold');
                        }
                    }
                };
                xhr.send('product_id=' + productId + '&status=' + newStatus);
            });
        });
    </script>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>

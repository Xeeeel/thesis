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

$sql = "SELECT product_id, product_name AS product_name, price, thumbnail AS thumbnail, condition_name 
        FROM products 
        WHERE seller_id = ?";
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
        width: 100%;
        background: white;
        padding: 5px; /* Reduce padding */
        border-radius: 8px; /* Reduce border radius for tighter appearance */
        box-shadow: 1px 1px 5px rgba(0, 0, 0, 0.1); /* Lighter shadow */
        margin: 5px; /* Reduce margin to bring cards closer */
        transition: transform 0.2s ease;
        display: flex;
        flex-direction: column;
        height: auto; /* Let cards adapt to content height */
    }

    .card:hover {
        transform: scale(1.02); /* Slightly smaller hover effect */
        box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.2);
    }

    .card-body {
        flex-grow: 1;
        padding: 8px; /* Reduce padding for compactness */
    }

    .card img {
        width: 100%;
        border-radius: 8px;
        object-fit: cover; /* Ensures the image fits without stretching */
    }

    .card button {
        width: 48%;
        padding: 5px 10px; /* Adjust padding to make buttons more compact */
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
        <a class="navbar-brand fs-3" href="http://localhost/cartsy/index/test-8.php">Cartsy</a>

        <!-- Search Bar with Button Inside -->
        <form class="d-flex flex-grow-1 mx-3" action="http://localhost/cartsy/search-product/test-4.php" method="GET">
            <div class="input-group">
                <input class="form-control" type="search" name="query" placeholder="Search" required>
                <button class="btn btn-dark" type="submit">Search</button>
            </div>
        </form>

        <!-- Sell Button -->
       <a class="nav-link d-flex align-items-center" href="http://localhost/cartsy/profile/index-6.php">Sell</a>

        <!-- Saved Products Button -->
        <a href="http://localhost/cartsy/saved-products.php" class="btn btn-outline-danger me-3">
            <i class="bi bi-heart-fill"></i>
        </a>

        <!-- Chat and Profile Icons -->
        <div>
            <i class="bi bi-chat fs-4 me-3"></i>
            <i class="bi bi-person-circle fs-4"></i>
        </div>
    </div>
</nav>

    <div class="d-flex">
        <!-- Sidebar -->
        <aside class="sidebar">
            <button class="btn btn-light text-danger fw-bold">+ Create new listing</button>
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
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $product_id = htmlspecialchars($row['product_id']);
                        $product_name = htmlspecialchars($row['product_name']);
                        $price = htmlspecialchars($row['price']);
                        $condition_name = htmlspecialchars($row['condition_name']);
                        $thumbnail = htmlspecialchars($row['thumbnail']);

                        echo '<div class="col-md-4">';
                        echo '    <div class="card">';
                        echo '        <img src="' . $thumbnail . '" alt="' . $product_name . '" class="card-img-top">';
                        echo '        <div class="card-body">';
                        echo '            <h6 class="fw-bold">' . $product_name . '</h6>';
                        echo '            <p class="text-muted mb-1">Condition: ' . $condition_name . '</p>';
                        echo '            <p class="text-danger fw-semibold">₱' . number_format($price, 2) . '</p>';
                        echo '            <div class="d-flex justify-content-between">';
                        echo '                <a href="edit-product.php?id=' . $product_id . '" class="btn btn-dark btn-sm">Edit</a>';
                        echo '                <a href="delete-product.php?id=' . $product_id . '" class="btn btn-light btn-sm">Delete</a>';
                        echo '            </div>';
                        echo '        </div>';
                        echo '    </div>';
                        echo '</div>';
                    }
                } else {
                    echo "<p class='text-muted'>You haven't listed any products yet.</p>";
                }

                $stmt->close();
                $conn->close();
                ?>
            </div>
        </div>
    </main>
    </div>

    <script>
        document.getElementById("status-toggle").addEventListener("click", function() {
            let statusOptions = document.getElementById("status-options");
            let arrowIcon = this.querySelector("i");

            // Toggle visibility
            if (statusOptions.style.display === "none") {
                statusOptions.style.display = "block";
                arrowIcon.classList.remove("bi-chevron-down");
                arrowIcon.classList.add("bi-chevron-up");
            } else {
                statusOptions.style.display = "none";
                arrowIcon.classList.remove("bi-chevron-up");
                arrowIcon.classList.add("bi-chevron-down");
            }
        });
    </script>

</body>
</html>

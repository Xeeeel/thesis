<?php
// Connect to database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cartsy";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check for errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get search query
$search_query = isset($_GET['query']) ? $_GET['query'] : '';

// Fetch products that match the search query
$sql = "SELECT * FROM products WHERE name LIKE ?";
$stmt = $conn->prepare($sql);
$search_term = "%" . $search_query . "%";
$stmt->bind_param("s", $search_term);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cartsy - Product Listing</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        .product-card {
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            border-radius: 10px;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
        }
        .product-image {
            width: 100%;
            height: 220px;
            object-fit: cover;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            background-color: #f8f8f8;
        }
        .bi-heart-fill {
            font-size: 1.2rem;
            color: red;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h3 class="mb-3">Search Results for "<?php echo htmlspecialchars($search_query); ?>"</h3>
        <div class="row">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <div class="card shadow-sm border-0 position-relative product-card">
                            <img src="/cartsy/' . htmlspecialchars($row["image_path"]) . '" class="product-image" alt="Product Image">
                            <i class="bi bi-heart-fill position-absolute top-0 end-0 m-2"></i>
                            <div class="card-body">
                                <h6 class="card-title">' . htmlspecialchars($row["name"]) . '</h6>
                                <p class="text-muted small">' . htmlspecialchars($row["condition_name"]) . '</p>
                                <p class="text-danger fw-bold">₱' . htmlspecialchars($row["price"]) . '</p>
                            </div>
                        </div>
                    </div>';
                }
            } else {
                echo '<p class="text-muted">No products found.</p>';
            }
            ?>
        </div>
    </div>
</body>
</html>
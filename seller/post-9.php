<?php
session_start();

$host = "localhost";
$user = "root"; 
$password = ""; 
$database = "cartsy";

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$seller_name = "Guest Seller";
$seller_image = "https://via.placeholder.com/50"; 

if (isset($_SESSION["id"])) {
    $user_id = $_SESSION["id"];
    $stmt = $conn->prepare("SELECT name, profile_picture FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($name, $profile_picture);
    
    if ($stmt->fetch()) {
        $seller_name = $name;
        if (!empty($profile_picture)) {
            $seller_image = "http://localhost/cartsy/profile/" . $profile_picture;
        }
    }
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "<pre>";
    var_dump($deal); // Check if the deal value is properly captured
    var_dump($_POST); // Check all posted data
    echo "</pre>";
    die(); // To halt execution and see the output

    if (
        isset($_POST["title"], $_POST["price"], $_POST["category"], $_POST["condition"], 
              $_POST["location"], $_POST["deal"], $_POST["description"])
    ) {
        $title = trim($_POST["title"]);
        $price = floatval($_POST["price"]);
        $category = trim($_POST["category"]);
        $condition_status = trim($_POST["condition"]);
        $location = trim($_POST["location"]);
        $deal = isset($_POST["deal"]) ? trim($_POST["deal"]) : NULL;
        $description = trim($_POST["description"]);

        var_dump($title, $price, $category, $condition_status, $location, $description, $deal, $user_id);
        die();


        $sql = "INSERT INTO products (product_name, price, category, condition_name, location, description, deal, seller_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("sdsssssi", $title, $price, $category, $condition_status, $location, $description, $deal, $user_id);


            if ($stmt->execute()) {
                echo "<script>alert('Product added successfully!');</script>";
            } else {
                echo "<script>alert('Error: " . $stmt->error . "');</script>";
            }
            $stmt->close();
        } else {
            die("SQL Error: " . $conn->error);
        }
    } else {
        die("Error: Missing form fields!");
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cartsy - Item for Sale</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Suranna&display=swap" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .navbar { background-color: #ffffff; border-bottom: 1px solid #e0e0e0; padding: 1rem 2rem; }
        .navbar-brand { font-family: "Suranna", serif; font-size: 30px; color: #343a40; }
        .sidebar, .preview-box { background: white; padding: 25px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
        .btn-custom { background-color: #d4af37; border: none; transition: 0.3s; }
        .btn-custom:hover { background-color: #b8962e; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg sticky-top navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Cartsy</a>
            <form class="d-flex flex-grow-1 mx-3" action="#" method="GET">
                <div class="input-group">
                    <input class="form-control" type="search" name="query" placeholder="Search" required>
                    <button class="btn btn-dark" type="submit">Search</button>
                </div>
            </form>
            <button class="btn btn-outline-dark me-3">Sell</button>
            <a href="#" class="btn btn-outline-danger me-3"><i class="bi bi-heart-fill"></i></a>
            <i class="bi bi-chat fs-4 me-3"></i>
            <i class="bi bi-person-circle fs-4"></i>
        </div>
    </nav>
    <div class="container mt-4">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="sidebar">
                    <h4>Item for Sale</h4>
                    <div class="d-flex align-items-center mb-3">
                        <img src="<?= htmlspecialchars($seller_image) ?>" class="rounded-circle me-2" alt="User" width="50">
                        <div><strong><?= htmlspecialchars($seller_name) ?></strong><br><small>Listing to Cartsy</small></div>
                    </div>
                    <div class="mb-3 border p-4 text-center bg-light rounded">
                        <p class="mb-0">Add Photos</p>
                    </div>
                    <form method="POST">
                        <input type="text" class="form-control mb-3" name="title" placeholder="Title" required>
                        <input type="number" class="form-control mb-3" name="price" placeholder="Price" required>
                        <select class="form-select mb-3" name="category" required>
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
                        <select class="form-select mb-3" name="condition" required>
                            <option value="">Select Condition</option>
                            <option value="New">New</option>
                            <option value="Used - Like New">Used - Like New</option>
                            <option value="Used - Good">Used - Good</option>
                            <option value="Used - Fair">Used - Fair</option>
                        </select>
                        <input type="text" class="form-control mb-3" name="location" placeholder="Location" required>
                        <select class="form-select mb-3" name="deal" required>
                            <option value="">Select Deal Option</option>
                            <option value="Meetup">Meetup</option>
                            <option value="Delivery">Delivery</option>
                        </select>
                        <textarea class="form-control mb-3" name="description" placeholder="Enter product description" rows="4" required></textarea>
                        <button type="submit" class="btn btn-warning btn-custom w-100">Publish</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

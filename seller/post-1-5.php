<?php
session_start();

$host = "localhost";
$user = "root";
$password = "";
$database = "cartsy";

// Create database connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$product_id = isset($_GET['product_id']) ? $_GET['product_id'] : 0;

// Fetch product details if updating an existing product
$product_details = null;
if ($product_id > 0) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product_details = $result->fetch_assoc();
    }
     // Fetch associated product images
    $images = [];
    $stmt = $conn->prepare("SELECT image_path FROM product_images WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->bind_result($image_path);
    while ($stmt->fetch()) {
        $images[] = $image_path;
    }
    $stmt->close();
}

// Initialize seller details
$seller_name = "Guest Seller";
$seller_image = "https://via.placeholder.com/50"; // Default profile picture

// Check if user is logged in
if (isset($_SESSION["id"])) {
    $user_id = $_SESSION["id"];

    // Fetch user details from the database
    $stmt = $conn->prepare("SELECT name, profile_picture FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($name, $profile_picture);

    if ($stmt->fetch()) {
        $seller_name = $name;
        if (!empty($profile_picture)) {
            $seller_image = "http://localhost/cartsy/profile/" . $profile_picture;
        }
    } else {
        echo "<script>console.log('Failed to fetch user details');</script>";
    }
    $stmt->close();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $price = $_POST["price"];
    $category = $_POST["category"];
    $condition_status = $_POST["condition"];
    $location = $_POST["location"];
    $deal = $_POST["deal"];
    $description = $_POST["description"];
    $seller_id = $_SESSION["id"]; 

    // If we are updating an existing product
    if ($product_id > 0) {
        // Update existing product
        $sql = "UPDATE products SET product_name = ?, price = ?, category = ?, condition_name = ?, location = ?, description = ?, deal = ? WHERE product_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdsssssi", $title, $price, $category, $condition_status, $location, $description, $deal, $product_id);
    } else {
        // Insert new product
        $sql = "INSERT INTO products (product_name, price, category, condition_name, location, description, seller_id, deal) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdssssss", $title, $price, $category, $condition_status, $location, $description, $seller_id, $deal);
    }

    if ($stmt->execute()) {
        $product_id = $stmt->insert_id ?: $product_id; // Get new product ID if it's an insert

        // Handle multiple image uploads
        if (!empty($_FILES['images']['name'][0])) {
            $target_dir = "uploads/";
            foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
                if (!empty($_FILES['images']['name'][$key])) {
                    $image_name = basename($_FILES["images"]["name"][$key]);
                    $image_path = $target_dir . $image_name;
                    move_uploaded_file($tmp_name, $image_path);

                    // Use the first image as a thumbnail
                    if ($key === 0) {
                        $thumbnail = $image_path;

                        // Update the product record with the thumbnail
                        $update_sql = "UPDATE products SET thumbnail = ? WHERE product_id = ?";
                        $update_stmt = $conn->prepare($update_sql);
                        $update_stmt->bind_param("si", $thumbnail, $product_id);
                        $update_stmt->execute();
                        $update_stmt->close();
                    }

                    // Insert image path into database
                    $stmt = $conn->prepare("INSERT INTO product_images (product_id, image_path) VALUES (?, ?)");
                    $stmt->bind_param("is", $product_id, $image_path);
                    $stmt->execute();
                }
            }
        }

        echo "<script>alert('Product updated successfully!'); window.location.href='http://localhost/cartsy/seller/test-9.php';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }
    $stmt->close();
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
        .upload-box {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 120px;
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            background-color: #f8f9fa;
            cursor: pointer;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        .upload-box:hover {
            background-color: #f1f3f5;
        }

        .upload-box p {
            margin: 0;
            color: #6c757d;
            font-size: 16px;
        }

        input[type="file"] {
            display: none;
        }

        .preview-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }

        .preview-container img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
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

                    <?php if ($product_details): ?>
    <form method="POST" enctype="multipart/form-data">
        <label class="upload-box" for="file-upload">
                            <p>Add Photos</p>
                        </label>
                        <input type="file" id="file-upload" name="images[]" accept="image/*" multiple onchange="previewImages(event)">
                        <div class="preview-container" id="preview-container"></div>
    <input type="text" class="form-control mb-3" name="title" value="<?= htmlspecialchars($product_details['product_name']) ?>" required>
    <input type="number" class="form-control mb-3" name="price" value="<?= htmlspecialchars($product_details['price']) ?>" required>
    
    <select class="form-select mb-3" name="category" required>
        <option value="Women's Apparel" <?= ($product_details['category'] == "Women's Apparel") ? 'selected' : '' ?>>Women's Apparel</option>
        <option value="Men's Apparel" <?= ($product_details['category'] == "Men's Apparel") ? 'selected' : '' ?>>Men's Apparel</option>
        <option value="Laptops & Computers" <?= ($product_details['category'] == "Laptops & Computers") ? 'selected' : '' ?>>Laptops & Computers</option>
        <option value="School & Office Supplies" <?= ($product_details['category'] == "School & Office Supplies") ? 'selected' : '' ?>>School & Office Supplies</option>
        <option value="Home & Living" <?= ($product_details['category'] == "Home & Living") ? 'selected' : '' ?>>Home & Living</option>
        <option value="Home Appliances" <?= ($product_details['category'] == "Home Appliances") ? 'selected' : '' ?>>Home Appliances</option>
        <option value="Health & Personal Care" <?= ($product_details['category'] == "Health & Personal Care") ? 'selected' : '' ?>>Health & Personal Care</option>
        <option value="Books & Magazines" <?= ($product_details['category'] == "Books & Magazines") ? 'selected' : '' ?>>Books & Magazines</option>
    </select>

    <select class="form-select mb-3" name="condition" required>
        <option value="New" <?= ($product_details['condition_name'] == "New") ? 'selected' : '' ?>>New</option>
        <option value="Used - Like New" <?= ($product_details['condition_name'] == "Used - Like New") ? 'selected' : '' ?>>Used - Like New</option>
        <option value="Used - Good" <?= ($product_details['condition_name'] == "Used - Good") ? 'selected' : '' ?>>Used - Good</option>
        <option value="Used - Fair" <?= ($product_details['condition_name'] == "Used - Fair") ? 'selected' : '' ?>>Used - Fair</option>
    </select>

    <input type="text" class="form-control mb-3" name="location" value="<?= htmlspecialchars($product_details['location']) ?>" required>
    
    <select class="form-select mb-3" name="deal" required>
        <option value="Meetup" <?= ($product_details['deal'] == "Meetup") ? 'selected' : '' ?>>Meetup</option>
        <option value="Delivery" <?= ($product_details['deal'] == "Delivery") ? 'selected' : '' ?>>Delivery</option>
    </select>

    <textarea class="form-control mb-3" name="description" rows="4" required><?= htmlspecialchars($product_details['description']) ?></textarea>

    <button type="submit" class="btn btn-warning btn-custom w-100">Update</button>
</form>

<?php else: ?>
    <p>Product not found.</p>
<?php endif; ?>

                </div>
            </div>
            <div class="col-lg-8">
                <script>
    // Function to update the preview in real-time
    function updatePreview() {
        document.getElementById("preview-title").innerText = document.querySelector("input[name='title']").value || "Product Title";
        document.getElementById("preview-price").innerText = "₱" + (document.querySelector("input[name='price']").value || "0");
        document.getElementById("preview-description").innerText = document.querySelector("textarea[name='description']").value || "Product Description";
        document.getElementById("preview-category").innerText = document.querySelector("select[name='category']").value || "Category";
        document.getElementById("preview-condition").innerText = document.querySelector("select[name='condition']").value || "Condition";
        document.getElementById("preview-location").innerText = document.querySelector("input[name='location']").value || "Location";
        document.getElementById("preview-deal-option").innerText = document.querySelector("select[name='deal']").value || "Deal Option";
    }
    
    // Function to preview images in real-time
    function previewImages(event) {
        const previewContainer = document.getElementById("preview-image");
        previewContainer.src = "https://via.placeholder.com/300"; // Default image
        
        if (event.target.files.length > 0) {
            const reader = new FileReader();
            reader.onload = function (e) {
                previewContainer.src = e.target.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    }
    
    // Attach event listeners for real-time preview updates
    document.querySelector("input[name='title']").addEventListener("input", updatePreview);
    document.querySelector("input[name='price']").addEventListener("input", updatePreview);
    document.querySelector("textarea[name='description']").addEventListener("input", updatePreview);
    document.querySelector("select[name='category']").addEventListener("change", updatePreview);
    document.querySelector("select[name='condition']").addEventListener("change", updatePreview);
    document.querySelector("input[name='location']").addEventListener("input", updatePreview);
    document.querySelector("select[name='deal']").addEventListener("change", updatePreview);
    document.querySelector("input[name='images[]']").addEventListener("change", previewImages);
</script>

<!-- Updated preview box -->
<div class="preview-box">
    <h5>Preview</h5>
    <div class="row">
        <div class="col-md-6">
            <img id="preview-image" src="<?= !empty($images) ? htmlspecialchars($images[0]) : 'https://via.placeholder.com/300' ?>" class="img-fluid rounded" alt="Product">
        </div>
        <div class="col-md-6">
            <h4 id="preview-title"><?= htmlspecialchars($product_details['product_name']) ?? 'Product Title' ?></h4>
            <h5 id="preview-price" class="text-danger">₱<?= htmlspecialchars($product_details['price']) ?? '0' ?></h5>
            <p>No local taxes included</p>
            <button class="btn btn-warning btn-custom mb-2"><i class="bi bi-chat-dots"></i> Message</button>
            <button class="btn btn-dark mb-2"><i class="bi bi-flag"></i> Report</button>
            <h6>Category: <span id="preview-category"><?= htmlspecialchars($product_details['category']) ?? 'Category' ?></span></h6>
            <h6>Condition: <span id="preview-condition"><?= htmlspecialchars($product_details['condition_name']) ?? 'Condition' ?></span></h6>
            <h6>Location: <span id="preview-location"><?= htmlspecialchars($product_details['location']) ?? 'Location' ?></span></h6>
            <h6>Deal Option: <span id="preview-deal-option"><?= htmlspecialchars($product_details['deal']) ?? 'Deal Option' ?></span></h6>
            
            <h6 class="mt-3">Product Description</h6>
            <p id="preview-description"><?= htmlspecialchars($product_details['description']) ?? 'Product Description' ?></p>
        </div>
    </div>
</div>

            </div>
        </div>
    </div>

    <script>
        function previewImages(event) {
            const previewContainer = document.getElementById("preview-container");
            previewContainer.innerHTML = "";

            for (let file of event.target.files) {
                let reader = new FileReader();
                reader.onload = function (e) {
                    let img = document.createElement("img");
                    img.src = e.target.result;
                    previewContainer.appendChild(img);
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>
</html>

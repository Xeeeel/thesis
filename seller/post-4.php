<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cartsy";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch product details
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$product = null;
$images = [];

if ($product_id) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();

    // Fetch existing images
    $stmt = $conn->prepare("SELECT * FROM product_images WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $images[] = $row;
    }
    $stmt->close();
}

// Handle update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = !empty($_POST['title']) ? $_POST['title'] : null;
    $price = !empty($_POST['price']) ? $_POST['price'] : null;
    $category = !empty($_POST['category']) ? $_POST['category'] : null;
    $condition = !empty($_POST['condition']) ? $_POST['condition'] : null;
    $location = !empty($_POST['location']) ? $_POST['location'] : null;
    $deal_option = !empty($_POST['deal_option']) ? $_POST['deal_option'] : null;
    $description = !empty($_POST['description']) ? $_POST['description'] : null;

    // Update product
    $stmt = $conn->prepare("UPDATE products SET name=?, price=?, category=?, condition_name=?, location=?, deal_option=?, description=? WHERE id=?");
    $stmt->bind_param("sdsssssi", $title, $price, $category, $condition, $location, $deal_option, $description, $product_id);
    
    if ($stmt->execute()) {
        $stmt->close();

        // Handle new images
        if (!empty($_FILES['images']['name'][0])) {
            $target_dir = "uploads/";

            foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
                if (!empty($_FILES['images']['name'][$key])) {
                    $image_path = $target_dir . basename($_FILES["images"]["name"][$key]);
                    move_uploaded_file($tmp_name, $image_path);

                    // Insert new image path
                    $stmt = $conn->prepare("INSERT INTO product_images (product_id, image_path) VALUES (?, ?)");
                    $stmt->bind_param("is", $product_id, $image_path);
                    $stmt->execute();
                    $stmt->close();
                }
            }
        }

        echo "<script>alert('Product updated successfully!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Error updating product!');</script>";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
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
        input[type="file"] { display: none; }
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
    <div class="container mt-4">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="p-4 bg-white rounded shadow">
                    <h4>Edit Product</h4>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <label class="upload-box" for="file-upload">
                            <p>Add Photos</p>
                        </label>
                        <input type="file" id="file-upload" name="images[]" accept="image/*" multiple onchange="previewImages(event)">
                        <div class="preview-container" id="preview-container">
                            <?php foreach ($images as $img): ?>
                                <img src="<?= $img['image_path'] ?>" alt="Product Image">
                            <?php endforeach; ?>
                        </div>
                        <input type="text" name="title" class="form-control mb-3" placeholder="Title" value="<?= htmlspecialchars($product['name'] ?? '') ?>" required>
                        <input type="number" name="price" class="form-control mb-3" placeholder="Price" value="<?= htmlspecialchars($product['price'] ?? '') ?>" required>
                        <select name="category" class="form-select mb-3" required>
                            <option value="">Category</option>
                            <option value="Women's Apparel" <?= ($product['category'] ?? '') === "Women's Apparel" ? "selected" : "" ?>>Women's Apparel</option>
                            <option value="Men's Apparel" <?= ($product['category'] ?? '') === "Men's Apparel" ? "selected" : "" ?>>Men's Apparel</option>
                            <option value="Laptops & Computers" <?= ($product['category'] ?? '') === "Laptops & Computers" ? "selected" : "" ?>>Laptops & Computers</option>
                        </select>
                        <select name="condition" class="form-select mb-3" required>
                            <option value="">Condition</option>
                            <option value="New" <?= ($product['condition_name'] ?? '') === "New" ? "selected" : "" ?>>New</option>
                            <option value="Used - Like New" <?= ($product['condition_name'] ?? '') === "Used - Like New" ? "selected" : "" ?>>Used - Like New</option>
                        </select>
                        <input type="text" name="location" class="form-control mb-3" placeholder="Location" value="<?= htmlspecialchars($product['location'] ?? '') ?>" required>
                        <select name="deal_option" class="form-select mb-3" required>
                            <option value="Meetup" <?= ($product['deal_option'] ?? '') === "Meetup" ? "selected" : "" ?>>Meetup</option>
                            <option value="Delivery" <?= ($product['deal_option'] ?? '') === "Delivery" ? "selected" : "" ?>>Delivery</option>
                        </select>
                        <textarea name="description" class="form-control mb-3" placeholder="Enter product description" rows="4" required><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
                        <button type="submit" class="btn btn-warning w-100">Save Changes</button>
                    </form>
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

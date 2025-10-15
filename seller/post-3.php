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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = !empty($_POST['title']) ? $_POST['title'] : null;
    $price = !empty($_POST['price']) ? $_POST['price'] : null;
    $category = !empty($_POST['category']) ? $_POST['category'] : null;
    $condition = !empty($_POST['condition']) ? $_POST['condition'] : null;
    $location = !empty($_POST['location']) ? $_POST['location'] : null;
    $deal_option = !empty($_POST['deal_option']) ? $_POST['deal_option'] : null;
    $description = !empty($_POST['description']) ? $_POST['description'] : null;

    // Insert product
    $stmt = $conn->prepare("INSERT INTO products (name, price, category, condition_name, location, deal_option, description) 
                            VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sdssssss", $title, $price, $category, $condition, $location, $deal_option, $description);
    
    if ($stmt->execute()) {
        $product_id = $stmt->insert_id; // Get last inserted ID
        $stmt->close();

        // Handle multiple image uploads
        if (!empty($_FILES['images']['name'][0])) {
            $target_dir = "uploads/";
            foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
                if (!empty($_FILES['images']['name'][$key])) {
                    $image_path = $target_dir . basename($_FILES["images"]["name"][$key]);
                    move_uploaded_file($tmp_name, $image_path);

                    // Insert image path
                    $stmt = $conn->prepare("INSERT INTO product_images (product_id, image_path) VALUES (?, ?)");
                    $stmt->bind_param("is", $product_id, $image_path);
                    $stmt->execute();
                    $stmt->close();
                }
            }
        }

        echo "<script>alert('Product added successfully!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Error adding product!');</script>";
    }
}
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cartsy - Add Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        .upload-box {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 120px; /* Increased height */
            padding: 15px; /* Added padding */
            border: 2px solid #e0e0e0;
            border-radius: 10px; /* Slightly more rounded */
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
    <div class="container mt-4">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="p-4 bg-white rounded shadow">
                    <h4>Add Product</h4>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <label class="upload-box" for="file-upload">
                            <p>Add Photos</p>
                        </label>
                        <input type="file" id="file-upload" name="images[]" accept="image/*" multiple onchange="previewImages(event)">
                        <div class="preview-container" id="preview-container"></div>
                        <input type="text" name="title" class="form-control mb-3" placeholder="Title" required>
                        <input type="number" name="price" class="form-control mb-3" placeholder="Price" required>
                        <select name="category" class="form-select mb-3" required>
                            <option value="">Category</option>
                            <option value="Women's Apparel">Women's Apparel</option>
                            <option value="Men's Apparel">Men's Apparel</option>
                            <option value="Laptops & Computers">Laptops & Computers</option>
                            <option value="School & Office Supplies">School & Office Supplies</option>
                            <option value="Home & Living">Home & Living</option>
                            <option value="Home Appliances">Home Appliances</option>
                            <option value="Health & Personal Care">Health & Personal Care</option>
                            <option value="Books & Magazines">Books & Magazines</option>
                        </select>
                        <select name="condition" class="form-select mb-3" required>
                            <option value="">Condition</option>
                            <option value="New">New</option>
                            <option value="Used - Like New">Used - Like New</option>
                            <option value="Used - Good">Used - Good</option>
                            <option value="Used - Fair">Used - Fair</option>
                        </select>
                        <input type="text" name="location" class="form-control mb-3" placeholder="Location" required>
                        <select name="deal_option" class="form-select mb-3" required>
                            <option value="Meetup">Meetup</option>
                            <option value="Delivery">Delivery</option>
                        </select>
                        <textarea name="description" class="form-control mb-3" placeholder="Enter product description" rows="4" required></textarea>
                        <button type="submit" class="btn btn-warning w-100">Publish</button>
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar Product Edit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        body {
            display: flex;
        }
        .sidebar {
            width: 250px;
            background: #343a40;
            color: white;
            height: 100vh;
            padding: 20px;
            position: fixed;
        }
        .content {
            margin-left: 260px;
            padding: 20px;
            width: 100%;
        }
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
        .preview-container img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
            border: 1px solid #ddd;
            margin: 5px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h4>Manage Product</h4>
        <form action="" method="POST" enctype="multipart/form-data">
            <label class="upload-box" for="file-upload">
                <p>Add Photos</p>
            </label>
            <input type="file" id="file-upload" name="images[]" accept="image/*" multiple onchange="previewImages(event)">
            <div class="preview-container" id="preview-container"></div>
            <input type="text" name="title" class="form-control my-2" placeholder="Title" required>
            <input type="number" name="price" class="form-control my-2" placeholder="Price" required>
            <select name="category" class="form-select my-2" required>
                <option value="">Category</option>
                <option value="Women's Apparel">Women's Apparel</option>
                <option value="Men's Apparel">Men's Apparel</option>
                <option value="Laptops & Computers">Laptops & Computers</option>
            </select>
            <select name="condition" class="form-select my-2" required>
                <option value="">Condition</option>
                <option value="New">New</option>
                <option value="Used - Like New">Used - Like New</option>
            </select>
            <input type="text" name="location" class="form-control my-2" placeholder="Location" required>
            <select name="deal_option" class="form-select my-2" required>
                <option value="Meetup">Meetup</option>
                <option value="Delivery">Delivery</option>
            </select>
            <textarea name="description" class="form-control my-2" placeholder="Enter product description" rows="3" required></textarea>
            <button type="submit" class="btn btn-warning w-100">Save Changes</button>
        </form>
    </div>
    <div class="content">
        <h2>Product Listings</h2>
        <p>Your main content goes here...</p>
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

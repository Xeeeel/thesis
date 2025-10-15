<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: http://localhost/cartsy/login/login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "cartsy");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to fetch user data
function getUserData($conn, $userId) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

$user = getUserData($conn, $_SESSION['id']);

// Fetch user's products
function getUserProducts($conn, $userId) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE seller_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    return $products;
}

$products = getUserProducts($conn, $_SESSION['id']);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if this is a profile picture update request
    if (isset($_POST['profile_update'])) {
        $uploadDir = "uploads/";
        $profilePicture = $user['profile_picture']; // Keep existing picture if no new upload

        if (!empty($_FILES['profile_picture']['name'])) {
            $fileName = basename($_FILES['profile_picture']['name']);
            $targetFilePath = $uploadDir . time() . "_" . $fileName;
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            if (in_array($fileType, $allowedTypes)) {
                if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetFilePath)) {
                    $profilePicture = $targetFilePath; // Update profile picture path in database

                    // Update only the profile picture in database
                    $query = "UPDATE users SET profile_picture=? WHERE id=?";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("si", $profilePicture, $_SESSION['id']);
                    
                    if ($stmt->execute()) {
                        $user['profile_picture'] = $profilePicture; // Update user data for display
                    }
                }
            }
        }
    } 
    // Handle full profile update
    else {
        $email = $_POST['email'] ?? '';
        $username = $_POST['username'] ?? '';
        $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : null;
        $name = $_POST['name'] ?? '';
        $gender = $_POST['gender'] ?? '';
        $birth_month = $_POST['birth_month'] ?? '';
        $birth_day = $_POST['birth_day'] ?? '';
        $birth_year = $_POST['birth_year'] ?? '';
        $phone_number = $_POST['phone_number'] ?? '';
        $address = $_POST['address'] ?? '';

        // Update user data in database
        if ($password) {
            $query = "UPDATE users SET email=?, username=?, name=?, gender=?, birth_month=?, birth_day=?, birth_year=?, phone_number=?, address=?, password=? WHERE id=?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssssssisssi", $email, $username, $name, $gender, $birth_month, $birth_day, $birth_year, $phone_number, $address, $password, $_SESSION['id']);
        } else {
            $query = "UPDATE users SET email=?, username=?, name=?, gender=?, birth_month=?, birth_day=?, birth_year=?, phone_number=?, address=? WHERE id=?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssssssisss", $email, $username, $name, $gender, $birth_month, $birth_day, $birth_year, $phone_number, $address, $_SESSION['id']);
        }

        if ($stmt->execute()) {
            $user = getUserData($conn, $_SESSION['id']); // Fetch updated user data
        } else {
            echo "Error: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cartsy - My Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        body { background-color: #e0e0e0; }
        .profile-container { max-width: 900px; margin: 50px auto; background: white; border-radius: 10px; overflow: hidden; display: flex; }
        .sidebar { background: #f5f5f5; padding: 30px; width: 300px; text-align: center; }
        .sidebar img { width: 100px; height: 100px; border-radius: 50%; }
        .profile-content { flex: 1; padding: 30px; }
        input, select { background-color: #e0e0e0; }
        .save-btn { background-color: #d4af63; color: white; border: none; padding: 10px 30px; border-radius: 5px; cursor: pointer; }
        .save-btn:hover { background-color: #b89250; }
        .logout-icon { position: absolute; bottom: 20px; left: 20px; font-size: 24px; cursor: pointer; }
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
    </style>
</head>
<body>

<nav class="navbar sticky-top navbar-light">
    <div class="container-fluid">
        <a class="navbar-brand fs-3" href="http://localhost/cartsy/index/test-8.php">Cartsy</a>

        <form class="d-flex flex-grow-1 mx-3" action="http://localhost/cartsy/search-product/test-4.php" method="GET">
            <div class="input-group">
                <input class="form-control" type="search" name="query" placeholder="Search" required>
                <button class="btn btn-dark" type="submit">Search</button>
            </div>
        </form>

        <button class="btn btn-outline-dark me-3" onclick="window.location.href='http://localhost/cartsy/seller/test-1.php'">Sell</button>

        <a href="http://localhost/cartsy/saved-products.php" class="btn btn-outline-danger me-3">
            <i class="bi bi-heart-fill"></i>
        </a>

        <div>
            
            <?php
            // Display only one chat icon (instead of looping through all products)
            // Example: Select a product to associate with the chat (first product in the list)
            if (count($products) > 0) {
                $product = $products[0]; // Just use the first product for chat icon
                $product_id = $product['product_id'];
                $seller_id = $product['seller_id']; // Get the seller_id from the current product
            ?>
                <a href="http://localhost/cartsy/chat/conversation.php?product_id=<?= $product_id ?>&seller_id=<?= $seller_id ?>" class="btn">
                    <i class="bi bi-chat fs-4"></i>
                </a>
            <?php
            }
            ?>

            <i class="bi bi-person-circle fs-4"></i>
        </div>
    </div>
</nav>

<div class="profile-container shadow">
    <div class="sidebar position-relative">
        <form method="POST" action="" enctype="multipart/form-data">
            <input type="hidden" name="profile_update" value="1">
            <label for="profile_picture">
                <img src="<?= htmlspecialchars($user['profile_picture'] ?? 'https://via.placeholder.com/100') ?>" 
                     alt="Profile Picture" 
                     class="rounded-circle">
            </label>
            <input type="file" id="profile_picture" name="profile_picture" class="d-none" accept="image/*">
            <br>
            <button type="submit" class="btn btn-sm btn-outline-primary mt-2">Upload</button>
        </form>

        <h5 class="mt-3"><?= htmlspecialchars($user['name'] ?? 'Guest') ?></h5>
        <a href="http://localhost/cartsy/profile/logout.php"><i class="bi bi-box-arrow-right logout-icon"></i></a>
    </div>

    <div class="profile-content">
        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" name="username" value="<?= htmlspecialchars($user['username'] ?? '') ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="password">
                <small class="text-muted">Leave blank to keep the current password.</small>
            </div>

            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($user['name'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Gender</label>
                <div>
                    <input type="radio" name="gender" value="Female" <?= isset($user['gender']) && $user['gender'] == 'Female' ? 'checked' : '' ?>> Female
                    <input type="radio" name="gender" value="Male" <?= isset($user['gender']) && $user['gender'] == 'Male' ? 'checked' : '' ?> class="ms-3"> Male
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Date Of Birth</label>
                <div class="d-flex">
                    <input type="text" class="form-control me-2" name="birth_month" value="<?= htmlspecialchars($user['birth_month'] ?? '') ?>" placeholder="Month">
                    <input type="text" class="form-control me-2" name="birth_day" value="<?= htmlspecialchars($user['birth_day'] ?? '') ?>" placeholder="Day">
                    <input type="text" class="form-control" name="birth_year" value="<?= htmlspecialchars($user['birth_year'] ?? '') ?>" placeholder="Year">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Phone Number</label>
                <input type="text" class="form-control" name="phone_number" value="<?= htmlspecialchars($user['phone_number'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Address</label>
                <input type="text" class="form-control" name="address" value="<?= htmlspecialchars($user['address'] ?? '') ?>">
            </div>

            <button type="submit" class="save-btn">Save</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

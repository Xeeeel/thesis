<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: http://localhost/cartsy/login/login.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "cartsy";
$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the logged-in user ID
$user_id = $_SESSION["user_id"];

// Fetch user data securely
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $phone = htmlspecialchars($_POST['phone']);
    $address = htmlspecialchars($_POST['address']);
    $profile_picture = $user['profile_picture']; // Default to existing profile picture

    // Handle file upload
if (!empty($_FILES['profile_picture']['name'])) {
    $target_dir = "uploads/";
    $filename = time() . "_" . basename($_FILES["profile_picture"]["name"]);
    $target_file = $target_dir . $filename;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Validate image file
    $check = getimagesize($_FILES["profile_picture"]["tmp_name"]);
    if ($check === false) {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Validate file size (max 2MB)
    if ($_FILES["profile_picture"]["size"] > 2000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow only specific formats
    $allowed_formats = ["jpg", "jpeg", "png", "gif"];
    if (!in_array($imageFileType, $allowed_formats)) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Proceed with upload if checks pass
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
            echo "File uploaded successfully: " . $target_file;
            $profile_picture = $filename; // Store filename in database
        } else {
            echo "Error moving uploaded file.";
        }
    }
}

// Debugging output before updating the database
echo "Profile picture filename before update: " . $profile_picture;

// Update user data securely
$update_sql = "UPDATE users SET name=?, phone=?, address=?, profile_picture=? WHERE id=?";
$stmt = $conn->prepare($update_sql);
$stmt->bind_param("ssssi", $name, $phone, $address, $profile_picture, $user_id);

// Debugging: Check if profile picture is updating
if ($stmt->execute()) {
    echo "Profile updated successfully!";
} else {
    echo "Error updating profile: " . $stmt->error;
}

// Refresh the page
header("Location: test-1.php");
exit();


}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cartsy Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; }
        .profile-container {
            max-width: 900px; background: white; margin: 50px auto;
            padding: 30px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .profile-sidebar { background-color: #f8f9fa; padding: 30px; border-radius: 10px 0 0 10px; text-align: center; }
        .profile-img {
            width: 120px; height: 120px; border-radius: 50%; background-size: cover;
            background-position: center; cursor: pointer; display: flex;
            align-items: center; justify-content: center; margin: auto;
        }
        .save-btn { background-color: #d4af37; border: none; color: white; padding: 10px 20px; font-size: 16px; border-radius: 5px; }
        .logout-btn { width: 80%; background-color: #dc3545; border: none; color: white; padding: 10px; border-radius: 5px; font-size: 16px; }
        .default-text {
            display: flex; align-items: center; justify-content: center;
            color: white; background: rgba(0, 0, 0, 0.5);
            width: 100%; height: 100%; border-radius: 50%; font-size: 14px; font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container profile-container">
        <div class="row">
            <div class="col-md-4 profile-sidebar">
                <form action="test-1.php" method="POST" enctype="multipart/form-data">
                    <label for="profileInput" style="cursor: pointer; display: block;">
                        <div class="profile-img" id="profileImage" 
     style="background-image: url('<?php echo $user["profile_picture"] ? "uploads/" . $user["profile_picture"] . "?v=" . time() : "profile/image/default.png"; ?>');">

                            <?php if (!$user["profile_picture"]) : ?>
                                <span class="default-text">Upload Image</span>
                            <?php endif; ?>
                        </div>
                    </label>
                    <input type="file" id="profileInput" name="profile_picture" style="display: none;">
                    <h5 class="mt-3"><?php echo htmlspecialchars($user['name']); ?></h5>
                </form>
                <form action="http://localhost/cartsy/login/login.php" method="POST">
                    <button type="submit" class="btn logout-btn"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                </form>
            </div>
            <div class="col-md-8 p-4">
                <h4>My Profile</h4>
                <form action="test-1.php" method="POST" enctype="multipart/form-data">
                    <label class="form-label">Name</label>
                    <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($user['name']); ?>">
                    <label class="form-label">Phone</label>
                    <input type="text" class="form-control" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">
                    <label class="form-label">Address</label>
                    <input type="text" class="form-control" name="address" value="<?php echo htmlspecialchars($user['address']); ?>">
                    <button type="submit" class="btn save-btn">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>
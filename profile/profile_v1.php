<?php
session_start();
require_once __DIR__ . '/../config/db_config.php'; // Adjust path if needed
$pdo = db();

// Ensure the user is logged in; if not, redirect to the login page
if (empty($_SESSION['user_id'])) {
    header("Location: http://localhost/cartsy/login/login.php"); // Redirect to login if not authenticated
    exit();
}


$userId = $_SESSION['user_id'];

// Fetch user data
function getUserData(PDO $pdo, int $userId): array|null {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetch();
}

$user = getUserData($pdo, $userId);

// Fetch user's products
function getUserProducts(PDO $pdo, int $userId): array {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE seller_id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetchAll();
}

$products = getUserProducts($pdo, $userId);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // ✅ Profile picture update
    if (isset($_POST['profile_update'])) {
        $uploadDir = "uploads/";
        $profilePicture = $user['profile_picture'] ?? null;

        if (!empty($_FILES['profile_picture']['name'])) {
            $fileName = basename($_FILES['profile_picture']['name']);
            $targetFilePath = $uploadDir . time() . "_" . $fileName;
            $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            if (in_array($fileType, $allowedTypes)) {
                if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetFilePath)) {
                    $stmt = $pdo->prepare("UPDATE users SET profile_picture = ? WHERE id = ?");
                    if ($stmt->execute([$targetFilePath, $userId])) {
                        $user['profile_picture'] = $targetFilePath;
                    }
                }
            }
        }
    }

    // ✅ Full profile update
    else {
        $email = $_POST['email'] ?? '';
        $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : null;
        $name = $_POST['name'] ?? '';
        $gender = $_POST['gender'] ?? '';
        $birth_month = $_POST['birth_month'] ?? '';
        $birth_day = $_POST['birth_day'] ?? '';
        $birth_year = $_POST['birth_year'] ?? '';
        $phone_number = $_POST['phone_number'] ?? '';
        $address = $_POST['address'] ?? '';

        if ($password) {
            $stmt = $pdo->prepare("UPDATE users 
                SET email=?, name=?, gender=?, birth_month=?, birth_day=?, birth_year=?, phone_number=?, address=?, password=? 
                WHERE id=?");
            $stmt->execute([$email, $name, $gender, $birth_month, $birth_day, $birth_year, $phone_number, $address, $password, $userId]);
        } else {
            $stmt = $pdo->prepare("UPDATE users 
                SET email=?, name=?, gender=?, birth_month=?, birth_day=?, birth_year=?, phone_number=?, address=? 
                WHERE id=?");
            $stmt->execute([$email, $name, $gender, $birth_month, $birth_day, $birth_year, $phone_number, $address, $userId]);
        }

        $user = getUserData($pdo, $userId); // Refresh user data
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
    .sidebar { background: #f5f5f5; padding: 30px; width: 300px; text-align: center; position: relative; }
    .sidebar img { width: 100px; height: 100px; border-radius: 50%; }
    .profile-content { flex: 1; padding: 30px; }
    input, select { background-color: #e0e0e0; }
    .save-btn { background-color: #d4af63; color: white; border: none; padding: 10px 30px; border-radius: 5px; cursor: pointer; }
    .save-btn:hover { background-color: #b89250; }
    .logout-icon { position: absolute; bottom: 20px; left: 20px; font-size: 24px; cursor: pointer; }
    .navbar { background-color: #ffffff; border-bottom: 1px solid #e0e0e0; padding: 1rem 2rem; }
    .navbar-brand { color: #343a40; font-family: "Suranna", serif; font-size: 30px; }
  </style>
</head>
<body>

<nav class="navbar sticky-top navbar-light">
  <div class="container-fluid">
    <a class="navbar-brand fs-3" href="http://localhost/cartsy/index/beta_index.php">Cartsy</a>

    <form class="d-flex flex-grow-1 mx-3" action="http://localhost/cartsy/search-product/test-4.php" method="GET">
      <div class="input-group">
        <input class="form-control" type="search" name="query" placeholder="Search" required>
        <button class="btn btn-dark" type="submit">Search</button>
      </div>
    </form>

    <a href="http://localhost/cartsy/seller/test-1.php" class="btn btn-outline-dark me-3">Sell</a>
    <a href="http://localhost/cartsy/saved/test-6.php" class="btn btn-outline-danger me-3">
      <i class="bi bi-heart-fill"></i>
    </a>

    <div>
      <a href="http://localhost/cartsy/chat/conversation.php"><i class="bi bi-chat fs-4 me-3"></i></a>
      <a href="http://localhost/cartsy/profile/index-7.php"><i class="bi bi-person-circle fs-4"></i></a>
    </div>
  </div>
</nav>

<div class="profile-container shadow">
  <div class="sidebar">
    <form method="POST" enctype="multipart/form-data">
      <input type="hidden" name="profile_update" value="1">
      <label for="profile_picture">
        <img src="<?= htmlspecialchars($user['profile_picture'] ?? 'https://via.placeholder.com/100') ?>" alt="Profile Picture">
      </label>
      <input type="file" id="profile_picture" name="profile_picture" class="d-none" accept="image/*">
      <br>
      <button type="submit" class="btn btn-sm btn-outline-primary mt-2">Upload</button>
    </form>

    <h5 class="mt-3"><?= htmlspecialchars($user['name'] ?? 'Guest') ?></h5>
    <a href="http://localhost/cartsy/profile/logout.php"><i class="bi bi-box-arrow-right logout-icon"></i></a>
  </div>

  <div class="profile-content">
    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>">
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
        <label class="form-label">Gender</label><br>
        <input type="radio" name="gender" value="Female" <?= ($user['gender'] ?? '') === 'Female' ? 'checked' : '' ?>> Female
        <input type="radio" name="gender" value="Male" <?= ($user['gender'] ?? '') === 'Male' ? 'checked' : '' ?> class="ms-3"> Male
      </div>

      <div class="mb-3">
        <label class="form-label">Date of Birth</label>
        <div class="d-flex">
          <input type="text" class="form-control me-2" name="birth_month" placeholder="Month" value="<?= htmlspecialchars($user['birth_month'] ?? '') ?>">
          <input type="text" class="form-control me-2" name="birth_day" placeholder="Day" value="<?= htmlspecialchars($user['birth_day'] ?? '') ?>">
          <input type="text" class="form-control" name="birth_year" placeholder="Year" value="<?= htmlspecialchars($user['birth_year'] ?? '') ?>">
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

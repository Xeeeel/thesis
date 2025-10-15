<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli("localhost", "root", "", "cartsy");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : null;
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $birth_month = $_POST['birth_month'];
    $birth_day = $_POST['birth_day'];
    $birth_year = $_POST['birth_year'];
    $phone_number = $_POST['phone_number'];
    $address = $_POST['address'];

    if (isset($_SESSION['user_id'])) {
        if ($password) {
            $query = "UPDATE users SET email=?, username=?, name=?, gender=?, birth_month=?, birth_day=?, birth_year=?, phone_number=?, address=?, password=? WHERE id=?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssssssisssi", $email, $username, $name, $gender, $birth_month, $birth_day, $birth_year, $phone_number, $address, $password, $_SESSION['user_id']);
        } else {
            $query = "UPDATE users SET email=?, username=?, name=?, gender=?, birth_month=?, birth_day=?, birth_year=?, phone_number=?, address=? WHERE id=?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssssssissi", $email, $username, $name, $gender, $birth_month, $birth_day, $birth_year, $phone_number, $address, $_SESSION['user_id']);
        }
    } else {
        $stmt = $conn->prepare("INSERT INTO users (email, username, password, name, gender, birth_month, birth_day, birth_year, phone_number, address) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssisss", $email, $username, $password, $name, $gender, $birth_month, $birth_day, $birth_year, $phone_number, $address);
    }

    // Debug SQL Execution
    if ($stmt->execute()) {
        echo "Data saved successfully!";
        $_SESSION['user_id'] = $conn->insert_id;
        header("Location: index-3.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}


// Fetch user data if session exists
$user = null;
if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
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
        .sidebar img { width: 100px; height: 100px; border-radius: 50%; background: #d4af63; padding: 20px; }
        .profile-content { flex: 1; padding: 30px; }
        input, select { background-color: #e0e0e0; }
        .save-btn { background-color: #d4af63; color: white; border: none; padding: 10px 30px; border-radius: 5px; cursor: pointer; }
        .save-btn:hover { background-color: #b89250; }
        .logout-icon { position: absolute; bottom: 20px; left: 20px; font-size: 24px; cursor: pointer; }
    </style>
</head>
<body>

<nav class="navbar navbar-light bg-white p-3 border-bottom">
    <div class="container-fluid">
        <a class="navbar-brand fs-3" href="#">Cartsy</a>
        <div class="d-flex align-items-center">
            <i class="bi bi-chat fs-4 me-3"></i>
            <i class="bi bi-person-circle fs-4"></i>
        </div>
    </div>
</nav>

<div class="profile-container shadow">
    <div class="sidebar position-relative">
        <img src="https://via.placeholder.com/100" alt="Profile Picture">
        <h5 class="mt-3"><?= htmlspecialchars($user['name'] ?? 'Guest') ?></h5>
        <a href="logout.php"><i class="bi bi-box-arrow-right logout-icon"></i></a>
    </div>

    <div class="profile-content">
        <form method="POST">
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

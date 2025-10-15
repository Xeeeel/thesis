<?php
session_start();
$conn = new mysqli("localhost", "root", "", "cartsy");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $birth_month = $_POST['birth_month'];
    $birth_day = $_POST['birth_day'];
    $birth_year = $_POST['birth_year'];
    $phone_number = $_POST['phone_number'];
    $address = $_POST['address'];

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO users (email, username, password, name, gender, birth_month, birth_day, birth_year, phone_number, address) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssiiis", $email, $username, $password, $name, $gender, $birth_month, $birth_day, $birth_year, $phone_number, $address);
    
    if ($stmt->execute()) {
        $_SESSION['user_id'] = $conn->insert_id;
        header("Location: profile.php");
        exit();
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

<div class="container mt-5">
    <div class="card p-4">
        <h3>My Profile</h3>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" value="<?= $user['email'] ?? '' ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" name="username" value="<?= $user['username'] ?? '' ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" class="form-control" name="name" value="<?= $user['name'] ?? '' ?>">
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
                    <select class="form-select me-2" name="birth_month">
                        <option value="May" <?= isset($user['birth_month']) && $user['birth_month'] == 'May' ? 'selected' : '' ?>>May</option>
                    </select>
                    <select class="form-select me-2" name="birth_day">
                        <option value="29" <?= isset($user['birth_day']) && $user['birth_day'] == '29' ? 'selected' : '' ?>>29</option>
                    </select>
                    <select class="form-select" name="birth_year">
                        <option value="2000" <?= isset($user['birth_year']) && $user['birth_year'] == '2000' ? 'selected' : '' ?>>2000</option>
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Phone Number</label>
                <input type="text" class="form-control" name="phone_number" value="<?= $user['phone_number'] ?? '' ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Address</label>
                <input type="text" class="form-control" name="address" value="<?= $user['address'] ?? '' ?>">
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

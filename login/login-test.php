<?php
session_start();

// Initialize variables for error messages
$usernameError = $passwordError = $loginError = "";

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $inputUsername = trim($_POST['username']);
    $inputPassword = trim($_POST['password']);
    $hasError = false;  // To track validation status

    // Validate username
    if (empty($inputUsername)) {
        $usernameError = "Username is required.";
        $hasError = true;
    }

    // Validate password
    if (empty($inputPassword)) {
        $passwordError = "Password is required.";
        $hasError = true;
    } elseif (strlen($inputPassword) < 2) {
        $passwordError = "Password must be at least 2 characters.";
        $hasError = true;
    }

    // Proceed only if there are no validation errors
    if (!$hasError) {
        // Connect to the database
        $conn = new mysqli("localhost", "root", "", "cartsy");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prevent SQL Injection
        $inputUsername = mysqli_real_escape_string($conn, $inputUsername);
        $inputPassword = mysqli_real_escape_string($conn, $inputPassword);

        // Query the database for matching username and password
        $sql = "SELECT * FROM users WHERE username = '$inputUsername' AND password = '$inputPassword'";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            $_SESSION['username'] = $inputUsername;
            header("Location: http://localhost/cartsy/sign-up/sign-up.php");
            exit();
        } else {
            $loginError = "Invalid username or password.";
        }

        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cartsy - Login</title>
    <link rel="stylesheet" href="login.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Suranna&display=swap" rel="stylesheet">
</head>
<body class="body">
    <nav class="navbar navbar-expand-lg bg-body-tertiary p-3">
        <div class="container-fluid brand">
            <a class="navbar-brand suranna-regular" href="#">Cartsy</a>
        </div>
    </nav>
    <div class="container-fluid login-page">
        <div class="background-image"></div>
        <div class="row justify-content-center align-items-center vh-100">
            <div class="col-lg-4 col-md-6 col-sm-8">
                <div class="login-box p-4 shadow-lg rounded-3">
                    <h3 class="text-center mb-4">Login</h3>

                    <!-- Display login error (if any) -->
                    <?php if (!empty($loginError)): ?>
                        <div class="alert alert-danger"><?php echo $loginError; ?></div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" id="username" name="username" class="form-control" value="<?php echo htmlspecialchars($inputUsername ?? ''); ?>">
                            <small class="text-danger"><?php echo $usernameError; ?></small>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" id="password" name="password" class="form-control">
                            <small class="text-danger"><?php echo $passwordError; ?></small>
                        </div>
                        <button type="submit" class="btn btn-dark w-100">LOGIN</button>
                    </form>

                    <p class="text-center mt-3">
                        No Account? <a href="#" class="text-danger">Sign Up Here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

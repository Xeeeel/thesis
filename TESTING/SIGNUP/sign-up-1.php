<?php
session_start();

// Initialize error message variables
$usernameError = $passwordError = $confirmPasswordError = $registrationError = $successMessage = "";

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $inputUsername = trim($_POST['username']);
    $inputPassword = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm_password']);
    $hasError = false;

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

    // Confirm password validation
    if ($inputPassword !== $confirmPassword) {
        $confirmPasswordError = "Passwords do not match.";
        $hasError = true;
    }

    // Proceed with user registration only if no errors
    if (!$hasError) {
        // Connect to the database
        $conn = new mysqli("localhost", "root", "", "cartsy");

        // Check for connection error
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Escape special characters to prevent SQL Injection
        $inputUsername = mysqli_real_escape_string($conn, $inputUsername);
        $inputPassword = mysqli_real_escape_string($conn, $inputPassword);
        $hashedPassword = password_hash($inputPassword, PASSWORD_DEFAULT); // Hash the password

        // Check if the username already exists
        $checkUserQuery = "SELECT * FROM users WHERE username = '$inputUsername'";
        $result = $conn->query($checkUserQuery);

        if ($result->num_rows > 0) {
            $registrationError = "Username is already taken. Please choose a different one.";
        } else {
            // Insert the new user into the database (without email)
            $insertUserQuery = "INSERT INTO users (username, password) VALUES ('$inputUsername', '$hashedPassword')";

            if ($conn->query($insertUserQuery) === TRUE) {
                $successMessage = "Account created successfully. You can now log in!";
                header("Location: http://localhost/cartsy/login/login.php"); // Redirect to login page
                exit();
            } else {
                $registrationError = "Error: " . $conn->error;
            }
        }

        $conn->close();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cartsy - Sign Up</title>
    <link rel="stylesheet" href="sign-up.css" />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Suranna&display=swap"
      rel="stylesheet"
    />
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
            <h3 class="text-center mb-4">Sign Up</h3>

            <!-- Display Success or Error Message -->
                    <?php if (!empty($successMessage)): ?>
                        <div class="alert alert-success"><?php echo $successMessage; ?></div>
                    <?php elseif (!empty($registrationError)): ?>
                        <div class="alert alert-danger"><?php echo $registrationError; ?></div>
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
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" class="form-control">
                            <small class="text-danger"><?php echo $confirmPasswordError; ?></small>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Sign Up</button>
                    </form>

                    <p class="text-center mt-3">
                        Already have an account? <a href="http://localhost/cartsy/login/login.php">Log In Here</a>
                    </p>
          </div>
        </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>

<?php
    session_start();

    $conn = new mysqli("localhost", "root", "", "cartsy");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $registrationError = "";
    $successMessage = "";
    $inputUsername = "";
    $usernameError = "";
    $passwordError = "";
    $confirmPasswordError = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $inputUsername = trim($_POST['username']);
        $password = trim($_POST['password']);
        $confirmPassword = trim($_POST['confirm_password']);

        if (empty($inputUsername)) {
            $usernameError = "Username is required.";
        }

        if (empty($password)) {
            $passwordError = "Password is required.";
        }

        if (empty($confirmPassword)) {
            $confirmPasswordError = "Confirm password is required.";
        }

        if ($password !== $confirmPassword) {
            $confirmPasswordError = "Passwords do not match.";
        }

        if (empty($usernameError) && empty($passwordError) && empty($confirmPasswordError)) {
            // Check if username exists
            $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->bind_param("s", $inputUsername);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $usernameError = "Username is already taken.";
            } else {
                $stmt->close(); // Close previous statement before creating a new one

                // Hash password
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
                $stmt->bind_param("ss", $inputUsername, $hashedPassword);

                if ($stmt->execute()) {
                    $successMessage = "Registration successful! Redirecting...";
                    header("Refresh: 2; URL=http://localhost/cartsy/login/login.php");
                    exit();
                } else {
                    $registrationError = "Error: " . $stmt->error;
                }
            }
            $stmt->close();
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
    <nav class="navbar sticky-top navbar-expand-lg bg-body-tertiary p-3">
      <div class="container-fluid brand">
        <a
          class="navbar-brand suranna-regular"
          href="http://localhost/cartsy/index/test-7.php"
          >Cartsy</a
        >
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
            <div class="alert alert-success">
              <?php echo $successMessage; ?>
            </div>
            <?php elseif (!empty($registrationError)): ?>
            <div class="alert alert-danger">
              <?php echo $registrationError; ?>
            </div>
            <?php endif; ?>

            <form method="POST" action="">
              <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input
                  type="text"
                  id="username"
                  name="username"
                  class="form-control"
                  value="<?php echo htmlspecialchars($inputUsername); ?>"
                />
                <small class="text-danger"><?php echo $usernameError; ?></small>
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input
                  type="password"
                  id="password"
                  name="password"
                  class="form-control"
                />
                <small class="text-danger"><?php echo $passwordError; ?></small>
              </div>
              <div class="mb-3">
                <label for="confirm_password" class="form-label"
                  >Confirm Password</label
                >
                <input
                  type="password"
                  id="confirm_password"
                  name="confirm_password"
                  class="form-control"
                />
                <small class="text-danger"
                  ><?php echo $confirmPasswordError; ?></small
                >
              </div>
              <button type="submit" class="btn btn-primary w-100">
                Sign Up
              </button>
            </form>

            <p class="text-center mt-3">
              Already have an account?
              <a href="http://localhost/cartsy/login/login.php">Log In Here</a>
            </p>
          </div>
        </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>

<?php
session_start();  // Start session to track login status

// CSRF Token to protect against CSRF attacks
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));  // Generate a secure token
}

// Database connection
$conn = new mysqli("localhost", "root", "", "cartsy");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$loginError = "";
$inputUsername = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate CSRF token
    if ($_POST['csrf_token'] != $_SESSION['csrf_token']) {
        die("Invalid CSRF token!");
    }

    $inputUsername = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($inputUsername) || empty($password)) {
        $loginError = "Username and password are required.";
    } else {
        // Prepare and bind
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $inputUsername);
        $stmt->execute();
        $stmt->bind_result($userId, $hashedPassword);

        if ($stmt->fetch() && password_verify($password, $hashedPassword)) {
            // Password is correct, start the session
            $_SESSION['id'] = $userId;
            header("Location: http://localhost/cartsy/index/test-9.php"); // Redirect to the profile page or dashboard
            exit();
        } else {
            $loginError = "Invalid username or password.";
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
    <title>Cartsy - Login</title>
    <link rel="stylesheet" href="login.css" />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Suranna&display=swap"
      rel="stylesheet"
    />
  </head>
  <body class="body">
    <!-- Navbar -->
    <nav class="navbar sticky-top navbar-expand-lg bg-body-tertiary p-3">
      <div class="container-fluid brand">
        <a class="navbar-brand suranna-regular" href="http://localhost/cartsy/index/test-7.php">
          Cartsy
        </a>
      </div>
    </nav>
    
    <!-- Login Form -->
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

            <!-- Login form with CSRF token -->
            <form method="POST" action="">
              <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

              <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input
                  type="text"
                  id="username"
                  name="username"
                  class="form-control"
                  value="<?php echo htmlspecialchars($inputUsername); ?>"
                  required
                />
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input
                  type="password"
                  id="password"
                  name="password"
                  class="form-control"
                  required
                />
              </div>
              <button type="submit" class="btn btn-dark w-100">LOGIN</button>
            </form>

            <p class="text-center mt-3">
              No Account?
              <a href="http://localhost/cartsy/sign-up/sign-up.php" class="text-danger">Sign Up Here</a>
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- Optional JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>

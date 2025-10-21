<?php
session_start();
require_once __DIR__ . '/../config/db_config.php'; // Include the database configuration
$pdo = db();

$loginError = "";
$inputUsername = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $inputUsername = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Check if both username and password are provided
    if (empty($inputUsername) || empty($password)) {
        $loginError = "Username and password are required.";
    } else {
        // Determine if the input is an email or username
        if (filter_var($inputUsername, FILTER_VALIDATE_EMAIL)) {
            // If it's an email, find the user by email
            $stmt = $pdo->prepare("SELECT id, password FROM users WHERE email = ?");
            $stmt->execute([$inputUsername]);
        } else {
            // Otherwise, it is assumed to be a username
            $stmt = $pdo->prepare("SELECT id, password FROM users WHERE username = ?");
            $stmt->execute([$inputUsername]);
        }
        
        $user = $stmt->fetch();

        // If the user exists and the password matches
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header("Location: http://localhost/cartsy/index/beta_index.php"); // Redirect after successful login
            exit();
        } else {
            $loginError = "Invalid username/email or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Cartsy</title>
  <link rel="stylesheet" href="login.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Suranna&display=swap" rel="stylesheet">
</head>
<body class="body">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light p-3">
  <div class="container-fluid brand">
    <a class="navbar-brand suranna-regular" href="http://localhost/cartsy/index/beta_index.php">
      Cartsy
    </a>
  </div>
</nav>

<div class="container-fluid">
  <div class="background-image"></div>
  <div class="row justify-content-center align-items-center vh-100">
    <div class="col-lg-5 col-md-6 col-sm-8 col-10">
      <div class="login-box p-4 m-auto shadow-lg rounded-3" style="width: 70%">
        <h3 class="text-center mb-3">Login</h3>
        <p class="text-center text-muted mb-4">Please enter your credentials</p>

        <!-- Login Form -->
        <form method="POST" id="loginForm">

          <!-- Username or Email Input -->
          <div class="mb-3">
            <label for="username" class="form-label">Username or Email</label>
            <input type="text" id="username" name="username" class="form-control" 
                   value="<?php echo htmlspecialchars($inputUsername); ?>" required />
          </div>

          <!-- Password Input -->
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password" name="password" class="form-control" required />
          </div>

          <!-- Display Error if any -->
          <?php if ($loginError): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($loginError) ?></div>
          <?php endif; ?>

          <!-- Submit Button -->
          <button class="btn btn-dark w-100" type="submit">Login</button>
        </form>

        <!-- Signup Link -->
        <p class="text-center mt-3">
          Don't have an account? 
          <a href="http://localhost/cartsy/signup/signup.php" class="text-danger">Sign up here</a>
        </p>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
session_start();
require_once __DIR__ . '/../config/db_config.php';
$pdo = db();

$loginError = "";

// Ensure default admin account exists
try {
    $stmt = $pdo->prepare("SELECT * FROM admin_account WHERE username = :u");
    $stmt->execute([':u' => 'admin']);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$admin) {
        // If admin doesn’t exist, create it
        $hash = password_hash('admin', PASSWORD_DEFAULT);
        $insert = $pdo->prepare("INSERT INTO admin_account (username, password, created_at) VALUES (:u, :p, NOW())");
        $insert->execute([':u' => 'admin', ':p' => $hash]);
    } else {
        // Optional: ensure password is still "admin"
        if (!password_verify('admin', $admin['password'])) {
            $newHash = password_hash('admin', PASSWORD_DEFAULT);
            $update = $pdo->prepare("UPDATE admin_account SET password = :p WHERE username = :u");
            $update->execute([':p' => $newHash, ':u' => 'admin']);
        }
    }
} catch (PDOException $e) {
    $loginError = "Database setup error: " . $e->getMessage();
}

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $loginError = "Username and password are required.";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT admin_id, username, password FROM admin_account WHERE username = :username LIMIT 1");
            $stmt->execute([':username' => $username]);
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($admin && password_verify($password, $admin['password'])) {
                $_SESSION['admin_id'] = $admin['admin_id'];
                $_SESSION['admin_username'] = $admin['username'];
                header("Location: http://localhost/cartsy/admin/admin.php");
                exit();
            } else {
                $loginError = "Invalid username or password.";
            }
        } catch (PDOException $e) {
            $loginError = "Database error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Cartsy Admin Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body, html { margin:0; padding:0; }
    .main-bg { background: linear-gradient(to bottom, #c58900, #7a5600); }
    .cartsy-box { border:5px solid white; padding:40px 60px; }
    .cartsy-text { color:white; font-size:3rem; font-family:'Georgia',serif; }
    .login-box { background:rgba(255,255,255,0.05); width:100%; max-width:400px; }
  </style>
</head>
<body>
  <div class="container-fluid main-bg vh-100 d-flex align-items-center justify-content-center">
    <div class="row w-100 h-100">
      <div class="col-md-6 d-flex align-items-center justify-content-center border-end">
        <div class="cartsy-box text-center">
          <h1 class="cartsy-text" style="width:400px; font-size:70px;">Cartsy</h1>
        </div>
      </div>
      <div class="col-md-6 d-flex align-items-center justify-content-center">
        <div class="login-box shadow rounded p-4">
          <h4 class="text-center fw-bold mb-4 text-white">WELCOME ADMIN</h4>

          <?php if (!empty($loginError)): ?>
            <div class="alert alert-danger text-center py-2"><?= htmlspecialchars($loginError) ?></div>
          <?php endif; ?>

          <form method="POST" action="">
            <div class="mb-3">
              <label for="username" class="form-label text-white">Username</label>
              <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" required />
            </div>
            <div class="mb-3">
              <label for="password" class="form-label text-white">Password</label>
              <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required />
            </div>
            <button type="submit" class="btn btn-dark w-100 mt-3">LOGIN</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>
</html>

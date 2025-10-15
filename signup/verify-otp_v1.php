<?php
session_start();
require_once __DIR__ . '/../config/db_config.php';
$pdo = db();

// If OTP is not available, redirect to the signup page
if (empty($_SESSION['pending_user_id']) || empty($_SESSION['pending_email'])) {
    header("Location: signup-v1.php");
    exit;
}

$userId = (int)$_SESSION['pending_user_id'];
$email  = $_SESSION['pending_email'];
$error  = '';

// Handle OTP verification when the user enters the code
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = trim($_POST['otp'] ?? '');
    $code = preg_replace('/\D/', '', $code); // keep digits only

    if (!preg_match('/^\d{6}$/', $code)) {
        $error = "Enter a valid 6-digit code.";
    } else {
        // Fetch OTP records for the user from the database
        $stmt = $pdo->prepare("SELECT id, otp_hash, attempt_count FROM user_otps WHERE user_id = ? AND purpose='email_verify' AND expires_at > NOW() ORDER BY id DESC");
        $stmt->execute([$userId]);
        $rows = $stmt->fetchAll();

        if (!$rows) {
            $error = "OTP expired or invalid. Please click 'Resend OTP'.";
        } else {
            $matched = false;
            foreach ($rows as $row) {
                // Skip if the maximum attempts have been reached
                if ((int)$row['attempt_count'] >= 5) continue;

                // Verify OTP by comparing it with the stored hashed OTP
                if (password_verify($code, $row['otp_hash'])) {
                    // ✅ Correct OTP
                    $pdo->beginTransaction();
                    try {
                        // Update user's verification status to 'Approved'
                        $pdo->prepare("UPDATE users SET verification_status='Approved' WHERE id=?")->execute([$userId]);

                        // Delete used OTP from the database
                        $pdo->prepare("DELETE FROM user_otps WHERE id=?")->execute([$row['id']]);
                        $pdo->commit();

                        unset($_SESSION['pending_user_id'], $_SESSION['pending_email']);
                        $_SESSION['otp_msg'] = "🎉 Your account has been verified successfully!";
                        header("Location: ../login/login.php");
                        exit;
                    } catch (Throwable $t) {
                        $pdo->rollBack();
                        $error = "Unexpected error. Try again.";
                    }
                    $matched = true;
                    break;
                }
            }

            if (!$matched) {
                // Increment attempt count for failed OTP attempts
                $latestId = (int)$rows[0]['id'];
                $pdo->prepare("UPDATE user_otps SET attempt_count=attempt_count+1 WHERE id=?")->execute([$latestId]);
                $error = "Incorrect code. Please try again.";
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verify OTP - Cartsy</title>

  <!-- Custom Styles (using your uploaded sign-up.css) -->
  <link rel="stylesheet" href="verify-otp_v1.css">
  
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Suranna&display=swap" rel="stylesheet">
</head>
<body>
  <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light p-3">
      <div class="container-fluid brand">
        <a class="navbar-brand suranna-regular" href="#">Cartsy</a>
      </div>
    </nav>
  <div class="container-fluid">
    <div class="background-image"></div>

    <!-- OTP Section -->
    <div class="row justify-content-center align-items-center vh-100">
      <div class="col-lg-5 col-md-6 col-sm-8 col-10">
        <div class="login-box p-4 shadow-lg rounded-3">
          <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/e2/Lock_icon.svg/1024px-Lock_icon.svg.png" alt="Lock Icon" class="img-fluid mb-4" style="max-width: 80px;">

          <h3 class="text-center mb-3">Enter OTP Code</h3>
          <p class="text-center text-muted mb-4">We have sent the code to your email. Please enter it below.</p>

          <!-- OTP Input Fields -->
          <div class="otp-input-group d-flex justify-content-center mb-3">
            <input type="text" maxlength="1" class="otp-input" id="otp1" oninput="moveFocus(this, 'otp2')">
            <input type="text" maxlength="1" class="otp-input" id="otp2" oninput="moveFocus(this, 'otp3')">
            <input type="text" maxlength="1" class="otp-input" id="otp3" oninput="moveFocus(this, 'otp4')">
            <input type="text" maxlength="1" class="otp-input" id="otp4" oninput="moveFocus(this, 'otp5')">
            <input type="text" maxlength="1" class="otp-input" id="otp5" oninput="moveFocus(this, 'otp6')">
            <input type="text" maxlength="1" class="otp-input" id="otp6">
          </div>

          <!-- OTP Error Message -->
          <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
          <?php endif; ?>

          <!-- Resend OTP Link -->
          <form method="POST">
            <p class="text-center"><a class="text-danger" href="#" name="resend_otp">Resend Code</a></p>
            <button class="btn btn-dark w-100" type="submit">Verify OTP</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  
  <!-- OTP JavaScript for Auto-focus -->
  <script>
    function moveFocus(current, next) {
      if (current.value.length >= 1) {
        document.getElementById(next).focus();
      }
    }
  </script>
</body>
</html>

<?php
session_start();
require_once __DIR__ . '/../config/db_config.php';
$pdo = db();

if (empty($_SESSION['pending_user_id']) || empty($_SESSION['pending_email'])) {
  header("Location: /signup/signup.php");
  exit;
}

$userId = (int)$_SESSION['pending_user_id'];
$email  = $_SESSION['pending_email'];
$error  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $code = preg_replace('/\D/', '', trim($_POST['otp'] ?? ''));

  if (!preg_match('/^\d{6}$/', $code)) {
    $error = "Enter a valid 6-digit code.";
  } else {
    $stmt = $pdo->prepare("
      SELECT id, otp_hash, attempt_count
      FROM user_otps
      WHERE user_id = ? AND purpose = 'email_verify' AND expires_at > UTC_TIMESTAMP()
      ORDER BY id DESC
    ");
    $stmt->execute([$userId]);
    $rows = $stmt->fetchAll();

    if (!$rows) {
      $error = "OTP expired or invalid. Please tap 'Resend OTP'.";
    } else {
      $matched = false;
      foreach ($rows as $row) {
        if ((int)$row['attempt_count'] >= 5) continue;

        if (password_verify($code, $row['otp_hash'])) {
          try {
            $pdo->beginTransaction();
            $pdo->prepare("UPDATE users SET verification_status='Approved' WHERE id=?")->execute([$userId]);
            $pdo->prepare("DELETE FROM user_otps WHERE id=?")->execute([$row['id']]);
            $pdo->commit();
          } catch (Throwable $t) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            $error = "Unexpected error. Please try again.";
          }

          if (!$error) {
            unset($_SESSION['pending_user_id'], $_SESSION['pending_email']);
            $_SESSION['otp_msg'] = "🎉 Your account has been verified!";
            header("Location: http://localhost/cartsy/login/login.php");
            exit;
          }
          $matched = true;
          break;
        }
      }

      if (!$matched && !$error) {
        $latestId = (int)$rows[0]['id'];
        $pdo->prepare("UPDATE user_otps SET attempt_count = attempt_count + 1 WHERE id=?")->execute([$latestId]);
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
  <link rel="stylesheet" href="test.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Suranna&display=swap" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light p-3">
  <div class="container-fluid brand">
    <a class="navbar-brand suranna-regular" href="#">Cartsy</a>
  </div>
</nav>

<div class="container-fluid">
  <div class="background-image"></div>
  <div class="row justify-content-center align-items-center vh-100">
    <div class="col-lg-5 col-md-6 col-sm-8 col-10">
      <div class="login-box p-4 shadow-lg rounded-3">
        <img src="./image/lock.png" alt="Lock Icon" class="img-fluid mb-4" style="max-width: 80px;">
        <h3 class="text-center mb-3">Enter OTP Code</h3>
        <p class="text-center text-muted mb-4">We’ve sent the code to your email.</p>

        <form method="POST" id="otpForm">
          <div class="otp-input-group d-flex justify-content-center mb-3">
            <input type="text" maxlength="1" class="otp-input" id="otp1" oninput="moveFocus(this, 'otp2')">
            <input type="text" maxlength="1" class="otp-input" id="otp2" oninput="moveFocus(this, 'otp3')">
            <input type="text" maxlength="1" class="otp-input" id="otp3" oninput="moveFocus(this, 'otp4')">
            <input type="text" maxlength="1" class="otp-input" id="otp4" oninput="moveFocus(this, 'otp5')">
            <input type="text" maxlength="1" class="otp-input" id="otp5" oninput="moveFocus(this, 'otp6')">
            <input type="text" maxlength="1" class="otp-input" id="otp6">
          </div>

          <!-- Hidden combined OTP -->
          <input type="hidden" name="otp" id="otp">

          <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
          <?php endif; ?>

          <p class="text-center"><a class="text-danger" href="beta_resend-otp.php" name="resend_otp">Resend Code</a></p>
          <button class="btn btn-dark w-100" type="submit">Verify OTP</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  function moveFocus(current, nextId) {
    if (current.value.length === 1 && nextId) {
      document.getElementById(nextId).focus();
    }
  }

  // Combine 6 inputs into one hidden field before submission
  document.getElementById('otpForm').addEventListener('submit', function() {
    const otp = [
      document.getElementById('otp1').value,
      document.getElementById('otp2').value,
      document.getElementById('otp3').value,
      document.getElementById('otp4').value,
      document.getElementById('otp5').value,
      document.getElementById('otp6').value
    ].join('');
    document.getElementById('otp').value = otp;
  });
</script>
</body>
</html>

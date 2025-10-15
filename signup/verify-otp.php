<?php
/** /signup/verify-otp.php — InfinityFree-tuned */
session_start();

/* --- DB connection --- */
require_once __DIR__ . '/../config/db_config.php';
$pdo = db();

/* If someone opens this directly without signup session, kick them back. */
if (empty($_SESSION['pending_user_id']) || empty($_SESSION['pending_email'])) {
  header("Location: /signup/signup.php");
  exit;
}

$userId = (int)$_SESSION['pending_user_id'];
$email  = $_SESSION['pending_email'];
$error  = '';

/* Handle form submit */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // keep only digits (avoid spaces / copy-paste artifacts)
  $code = preg_replace('/\D/', '', trim($_POST['otp'] ?? ''));

  if (!preg_match('/^\d{6}$/', $code)) {
    $error = "Enter a valid 6-digit code.";
  } else {
    // Pull ANY unexpired OTP for this user (most recent first)
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
        if ((int)$row['attempt_count'] >= 5) continue; // simple brute-force brake
        if (password_verify($code, $row['otp_hash'])) {
          // Success → approve user, delete OTP, redirect to login
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
            // clean session & go login
            unset($_SESSION['pending_user_id'], $_SESSION['pending_email']);
            $_SESSION['otp_msg'] = "🎉 Your account has been verified!";
            header("Location: /test.pdo.php");
            exit;
          }
          $matched = true;
          break;
        }
      }

      if (!$matched && !$error) {
        // bump attempts on latest code so we don't leak which one failed
        $latestId = (int)$rows[0]['id'];
        $pdo->prepare("UPDATE user_otps SET attempt_count = attempt_count + 1 WHERE id=?")->execute([$latestId]);
        $error = "Incorrect code. Please try again.";
      }
    }
  }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Verify OTP - Cartsy</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap first -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  <!-- Your CSS last (absolute path; version to bypass InfinityFree cache) -->
  <link rel="stylesheet" href="sign-up-v3.css">
</head>
<body class="p-3">
  <div class="container" style="max-width:480px">
    <h3 class="text-center mb-3">Verify OTP</h3>
    <p class="text-center">We sent a 6-digit code to <b><?= htmlspecialchars($email) ?></b></p>

    <?php if (!empty($_SESSION['otp_msg'])): ?>
      <div class="alert alert-info text-center"><?= htmlspecialchars($_SESSION['otp_msg']); ?></div>
      <?php unset($_SESSION['otp_msg']); ?>
    <?php endif; ?>

    <?php if ($error): ?>
      <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" novalidate>
      <input type="text"
             name="otp"
             maxlength="6"
             pattern="\d{6}"
             inputmode="numeric"
             class="form-control mb-3 text-center fs-4"
             placeholder="Enter 6-digit code"
             required>
      <button class="btn btn-primary w-100">Verify</button>
    </form>

    <p class="text-center mt-3">
      Didn’t receive a code?
      <a href="/signup/resend_otp.php">Resend OTP</a>
    </p>

    <p class="text-center text-muted" style="font-size:.9rem">
      (You can resend up to 3 times per hour)
    </p>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

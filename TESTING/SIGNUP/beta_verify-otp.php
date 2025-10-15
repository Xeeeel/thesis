<?php
session_start();
require_once __DIR__ . '/../config/db_config.php';
$pdo = db();

if (empty($_SESSION['pending_user_id']) || empty($_SESSION['pending_email'])) {
    header("Location: beta_signup.php");
    exit;
}

$userId = (int)$_SESSION['pending_user_id'];
$email  = $_SESSION['pending_email'];
$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = trim($_POST['otp'] ?? '');
    $code = preg_replace('/\D/', '', $code); // keep digits only

    if (!preg_match('/^\d{6}$/', $code)) {
        $err = "Enter a valid 6-digit code.";
    } else {
        $stmt = $pdo->prepare("
            SELECT id, otp_hash, attempt_count
            FROM user_otps
            WHERE user_id = ? AND purpose='email_verify' AND expires_at > UTC_TIMESTAMP()
            ORDER BY id DESC
        ");
        $stmt->execute([$userId]);
        $rows = $stmt->fetchAll();

        if (!$rows) {
            $err = "OTP expired or invalid. Please click 'Resend OTP'.";
        } else {
            $matched = false;
            foreach ($rows as $row) {
                if ((int)$row['attempt_count'] >= 5) continue;
                if (password_verify($code, $row['otp_hash'])) {
                    // ✅ Correct OTP
                    $pdo->beginTransaction();
                    try {
                        $pdo->prepare("UPDATE users SET verification_status='Approved' WHERE id=?")
                            ->execute([$userId]);
                        $pdo->prepare("DELETE FROM user_otps WHERE id=?")->execute([$row['id']]);
                        $pdo->commit();

                        unset($_SESSION['pending_user_id'], $_SESSION['pending_email']);
                        $_SESSION['otp_msg'] = "🎉 Your account has been verified successfully!";
                        header("Location: ../login/login.php");
                        exit;
                    } catch (Throwable $t) {
                        $pdo->rollBack();
                        $err = "Unexpected error. Try again.";
                    }
                    $matched = true;
                    break;
                }
            }

            if (!$matched) {
                $latestId = (int)$rows[0]['id'];
                $pdo->prepare("UPDATE user_otps SET attempt_count=attempt_count+1 WHERE id=?")
                    ->execute([$latestId]);
                $err = "Incorrect code. Please try again.";
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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-3">
<div class="container" style="max-width:480px">
  <h3 class="text-center mb-3">Verify OTP</h3>
  <p class="text-center">We sent a 6-digit code to <b><?= htmlspecialchars($email) ?></b></p>

  <!-- Show resend / success messages -->
  <?php if (!empty($_SESSION['otp_msg'])): ?>
    <div class="alert alert-info text-center"><?= htmlspecialchars($_SESSION['otp_msg']) ?></div>
    <?php unset($_SESSION['otp_msg']); ?>
  <?php endif; ?>

  <!-- Show error messages -->
  <?php if ($err): ?>
    <div class="alert alert-danger text-center"><?= htmlspecialchars($err) ?></div>
  <?php endif; ?>

  <!-- OTP Form -->
  <form method="POST">
    <div class="mb-3">
      <input type="text" name="otp" maxlength="6" pattern="\d{6}" inputmode="numeric"
             class="form-control text-center fs-4" placeholder="Enter 6-digit code" required>
    </div>
    <button class="btn btn-primary w-100">Verify</button>
  </form>

  <!-- Resend OTP Section -->
  <p class="text-center mt-3">
    Didn’t receive a code? <a href="resend_otp.php">Resend OTP</a>
  </p>
</div>
</body>
</html>

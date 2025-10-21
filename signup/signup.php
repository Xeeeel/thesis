<?php
session_start();

require_once __DIR__ . '/../config/db_config.php';
$pdo = db();

require_once __DIR__ . '/../PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../PHPMailer/src/SMTP.php';
require_once __DIR__ . '/../PHPMailer/src/Exception.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendOtpMail(string $toEmail, string $otp, ?string &$err = null): bool {
  $mail = new PHPMailer(true);
  try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'cartsy.marketplace@gmail.com'; // your Gmail
    $mail->Password = 'whoy panz rpjh ojcw';          // your Gmail App Password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('cartsy.marketplace@gmail.com', 'Cartsy');
    $mail->addAddress($toEmail);

    $mail->isHTML(true);
    $mail->Subject = 'Your Cartsy OTP Code';

    // ✅ Use HEREDOC to safely include HTML
    $mail->Body = <<<HTML
<html>
<head>
  <title>OTP Verification</title>
</head>
<body style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; background-color: #f3f4f6;">
  <table role="presentation" style="width: 100%; border-collapse: collapse;">
    <tr>
      <td style="padding: 40px 20px;">
        <table role="presentation" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
          <tr>
            <td style="text-align: left; vertical-align: middle;">
              <img src="https://www.canva.com/design/DAG2bStA2dQ/ysiK9t3Rliiy-UvbsO8uhg/view?utm_content=DAG2bStA2dQ&utm_campaign=designshare&utm_medium=link2&utm_source=uniquelinks&utlId=hdb3e254f90" alt="Cartsy Logo" style="width: 150px; display: block;">
            </td>
          </tr>
          <tr>
            <td style="padding: 20px 30px;">
              <p style="margin: 0 0 16px 0; color: #374151; font-size: 16px; line-height: 1.5;">
                Hello,
              </p>
              <p style="margin: 0 0 24px 0; color: #374151; font-size: 16px; line-height: 1.5;">
                You requested a one-time password to verify your identity. Use the code below to complete your verification:
              </p>

              <table role="presentation" style="width: 100%; margin: 32px 0;">
                <tr>
                  <td style="background-color: #f9fafb; border: 2px dashed #E3C16D; border-radius: 8px; padding: 32px; text-align: center;">
                    <p style="margin: 0 0 8px 0; color: #6b7280; font-size: 14px;">
                      Your verification code
                    </p>
                    <p style="margin: 0; color: #E3C16D; font-size: 36px; font-weight: 700; letter-spacing: 8px; font-family: 'Courier New', monospace;">
                      {$otp}
                    </p>
                  </td>
                </tr>
              </table>

              <p style="margin: 0 0 8px 0; color: #6b7280; font-size: 14px; line-height: 1.5;">
                This code will expire in <strong style="color: #111827;">10 minutes</strong>.
              </p>
              <p style="margin: 0; color: #6b7280; font-size: 14px; line-height: 1.5;">
                If you didn't request this code, please ignore this email.
              </p>
            </td>
          </tr>
          <tr>
            <td style="padding: 0 30px;"><div style="border-top: 1px solid #e5e7eb;"></div></td>
          </tr>
          <tr>
            <td style="padding: 24px 30px;">
              <p style="margin: 0; color: #9ca3af; font-size: 13px;">
                This email was sent to: <span style="color: #374151;">{$toEmail}</span>
              </p>
            </td>
          </tr>
          <tr>
            <td style="background-color: #f9fafb; padding: 24px 30px; text-align: center;">
              <p style="margin: 0 0 4px 0; color: #6b7280; font-size: 12px;">
                © 2025 Cartsy. All rights reserved.
              </p>
              <p style="margin: 0; color: #9ca3af; font-size: 12px;">
                This is an automated message, please do not reply.
              </p>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
HTML;

    return $mail->send();
  } catch (Exception $e) {
    $err = $mail->ErrorInfo;
    return false;
  }
}


$registrationError = "";
$successMessage = "";
$inputEmail = "";
$emailError = "";
$passwordError = "";
$confirmPasswordError = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $inputEmail = trim($_POST['email'] ?? '');
  $password   = trim($_POST['password'] ?? '');
  $confirm    = trim($_POST['confirm_password'] ?? '');

  // Validate email
  if ($inputEmail === '') {
    $emailError = "Email is required.";
  } elseif (!filter_var($inputEmail, FILTER_VALIDATE_EMAIL)) {
    $emailError = "Invalid email format.";
  }

  // Validate password + confirm
  if ($password === '') {
    $passwordError = "Password is required.";
  }
  if ($confirm === '') {
    $confirmPasswordError = "Confirm password is required.";
  }
  if ($password && $confirm && $password !== $confirm) {
    $confirmPasswordError = "Passwords do not match.";
  }

  // Process
  if (!$emailError && !$passwordError && !$confirmPasswordError) {
    try {
      // 1) Insert user (users.email is UNIQUE)
      $stmt = $pdo->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
      $stmt->execute([$inputEmail, password_hash($password, PASSWORD_DEFAULT)]);
      $userId = (int)$pdo->lastInsertId();

      // 2) Remove any unexpired OTPs (one-active-code policy)
      $pdo->prepare("
        DELETE FROM user_otps
        WHERE user_id = ? AND purpose='email_verify' AND expires_at > UTC_TIMESTAMP()
      ")->execute([$userId]);

      // 3) Generate + hash OTP, store with UTC expiry (5 minutes)
      $otp = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
      $otpHash = password_hash($otp, PASSWORD_BCRYPT);

      $pdo->prepare("
        INSERT INTO user_otps (user_id, otp_hash, purpose, expires_at, attempt_count, last_sent_at)
        VALUES (?, ?, 'email_verify', DATE_ADD(UTC_TIMESTAMP(), INTERVAL 20 MINUTE), 0, UTC_TIMESTAMP())
      ")->execute([$userId, $otpHash]);

      // 4) Send email
      $mailerErr = null;
      if (!sendOtpMail($inputEmail, $otp, $mailerErr)) {
        $registrationError = "Failed to send OTP. Please try again. " . ($mailerErr ?? '');
        // Optional: rollback the created user + otps if you prefer no partial accounts
        // $pdo->prepare("DELETE FROM user_otps WHERE user_id=?")->execute([$userId]);
        // $pdo->prepare("DELETE FROM users WHERE id=?")->execute([$userId]);
      } else {
        // 5) Stash who to verify, then move to OTP page
        $_SESSION['pending_user_id'] = $userId;
        $_SESSION['pending_email']   = $inputEmail;
        header("Location: verify-otp.php");
        exit;
      }
    } catch (PDOException $ex) {
      if (($ex->errorInfo[1] ?? 0) === 1062) {
        $registrationError = "This email is already registered. Please log in or use another email.";
      } else {
        $registrationError = "Registration error. Please try again.";
        // error_log($ex->getMessage());
      }
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cartsy - Sign Up</title>

    <!-- EXACT same design resources -->
    <link rel="stylesheet" href="signup_v1.css" />
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
          href="../index/test-7.php"
        >Cartsy</a>
      </div>
    </nav>

    <div class="container-fluid">
      <div class="background-image"></div>
      <div class="row justify-content-center align-items-center vh-100">
        <div class="col-lg-4 col-md-6 col-sm-8">
          <div class="login-box p-4 shadow-lg rounded-3">
            <h3 class="text-center mb-4">Sign Up</h3>

            <!-- Display Success or Error Message -->
            <?php if (!empty($successMessage)): ?>
              <div class="alert alert-success text-center">
                <?php echo $successMessage; ?>
              </div>
            <?php elseif (!empty($registrationError)): ?>
              <div class="alert alert-danger text-center">
                <?php echo htmlspecialchars($registrationError); ?>
              </div>
            <?php endif; ?>

            <form method="POST" action="" novalidate>
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input
                  type="email"
                  id="email"
                  name="email"
                  class="form-control"
                  value="<?php echo htmlspecialchars($inputEmail); ?>"
                  required
                />
                <small class="text-danger"><?php echo htmlspecialchars($emailError); ?></small>
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
                <small class="text-danger"><?php echo htmlspecialchars($passwordError); ?></small>
              </div>
              <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <input
                  type="password"
                  id="confirm_password"
                  name="confirm_password"
                  class="form-control"
                  required
                />
                <small class="text-danger"><?php echo htmlspecialchars($confirmPasswordError); ?></small>
              </div>
              <button type="submit" class="btn btn-dark w-100">Sign Up</button>
            </form>

            <p class="text-center mt-3">
              Already have an account?
              <a class="text-danger" href="../login/login.php">Log In Here</a>
            </p>
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
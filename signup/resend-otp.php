<?php
session_start();
require_once __DIR__ . '/../config/db_config.php';
require_once __DIR__ . '/../PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../PHPMailer/src/SMTP.php';
require_once __DIR__ . '/../PHPMailer/src/Exception.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$pdo = db();

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
              <img src="https://raw.githubusercontent.com/Xeeeel/thesis/refs/heads/main/2.png" alt="Cartsy Logo" style="width: 150px; display: block;">
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

/* Check if the session exists */
if (empty($_SESSION['pending_user_id']) || empty($_SESSION['pending_email'])) {
    header("Location: signup.php");
    exit;
}

$userId = $_SESSION['pending_user_id'];
$email  = $_SESSION['pending_email'];

// Generate a new 6-digit OTP
$otp = rand(100000, 999999);

// Hash the OTP for storage in the database (use bcrypt)
$otpHash = password_hash($otp, PASSWORD_BCRYPT);
$expiresAt = date('Y-m-d H:i:s', strtotime('+1 minutes'));  // OTP expires in 20 minutes

// Store OTP in the database
$stmt = $pdo->prepare("
    INSERT INTO user_otps (user_id, otp_hash, expires_at, purpose)
    VALUES (?, ?, ?, 'email_verify')
");
$stmt->execute([$userId, $otpHash, $expiresAt]);

// Send OTP to the user's email
$error = '';
if (!sendOtpMail($email, $otp, $error)) {
    $_SESSION['otp_msg'] = "Error sending OTP: " . htmlspecialchars($error);
    header("Location: verify-otp.php");
    exit;
}

$_SESSION['otp_msg'] = "A new OTP has been sent to your email.";
header("Location: verify-otp.php");
exit;
?>

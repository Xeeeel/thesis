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
        $mail->Username = 'cartsy.marketplace@gmail.com';
        $mail->Password = 'whoy panz rpjh ojcw';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('cartsy.marketplace@gmail.com', 'Cartsy');
        $mail->addAddress($toEmail);
        $mail->isHTML(true);
        $mail->Subject = 'Your Cartsy OTP Code';
        $mail->Body = "Hello,<br>Your new OTP code is <b>{$otp}</b><br><small>Expires in 5 minutes.</small>";
        return $mail->send();
    } catch (Exception $e) {
        $err = $mail->ErrorInfo;
        return false;
    }
}

if (empty($_SESSION['pending_user_id']) || empty($_SESSION['pending_email'])) {
    header("Location: beta_signup.php");
    exit;
}

$userId = (int)$_SESSION['pending_user_id'];
$email  = $_SESSION['pending_email'];

// Rate limit: max 3 sends per hour
$stmt = $pdo->prepare("
    SELECT COUNT(*) AS sent_count
    FROM user_otps
    WHERE user_id = ? AND purpose='email_verify' AND last_sent_at > DATE_SUB(UTC_TIMESTAMP(), INTERVAL 1 HOUR)
");
$stmt->execute([$userId]);
$count = (int)$stmt->fetchColumn();

if ($count >= 3) {
    $_SESSION['otp_msg'] = "You’ve reached the resend limit. Please wait an hour before trying again.";
    header("Location: beta_verify-otp.php");
    exit;
}

// Delete previous unexpired OTPs
$pdo->prepare("DELETE FROM user_otps WHERE user_id = ? AND purpose='email_verify' AND expires_at > UTC_TIMESTAMP()")
    ->execute([$userId]);

// Create new OTP
$otp = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
$hash = password_hash($otp, PASSWORD_BCRYPT);

// Insert new record
$pdo->prepare("
    INSERT INTO user_otps (user_id, otp_hash, purpose, expires_at, attempt_count, last_sent_at)
    VALUES (?, ?, 'email_verify', DATE_ADD(UTC_TIMESTAMP(), INTERVAL 5 MINUTE), 0, UTC_TIMESTAMP())
")->execute([$userId, $hash]);

// Send email
$mailErr = null;
if (!sendOtpMail($email, $otp, $mailErr)) {
    $_SESSION['otp_msg'] = "Failed to resend OTP. Error: $mailErr";
} else {
    $_SESSION['otp_msg'] = "✅ A new OTP was sent to your email.";
}

header("Location: beta_verify-otp.php");
exit;

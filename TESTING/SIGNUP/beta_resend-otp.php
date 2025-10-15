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
        $mail->Password = 'whoy panz rpjh ojcw';  // Please keep your credentials secure
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('cartsy.marketplace@gmail.com', 'Cartsy');
        $mail->addAddress($toEmail);
        $mail->isHTML(true);
        $mail->Subject = 'Your Cartsy OTP Code';
        $mail->Body = "Hello,<br>Your new OTP code is <b>{$otp}</b><br><small>This OTP is valid for 10 minutes.</small>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        $err = "Mailer Error: " . $mail->ErrorInfo;
        return false;
    }
}

/* Check if the session exists */
if (empty($_SESSION['pending_user_id']) || empty($_SESSION['pending_email'])) {
    header("Location: /signup/signup.php");
    exit;
}

$userId = $_SESSION['pending_user_id'];
$email  = $_SESSION['pending_email'];

// Generate a new 6-digit OTP
$otp = rand(100000, 999999);

// Hash the OTP for storage in the database (use bcrypt)
$otpHash = password_hash($otp, PASSWORD_BCRYPT);
$expiresAt = date('Y-m-d H:i:s', strtotime('+10 minutes'));  // OTP expires in 10 minutes

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
    header("Location: /cartsy/signup/verify-otp_v1.php");
    exit;
}

$_SESSION['otp_msg'] = "A new OTP has been sent to your email.";
header("Location: /cartsy/signup/verify-otp_v1.php");
exit;
?>

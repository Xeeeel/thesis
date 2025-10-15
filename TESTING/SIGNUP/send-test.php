<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '/../PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'cartsy.marketplace@gmail.com'; // your Gmail
    $mail->Password   = 'dhao ftdk vxqt abdm';            // <<-- replace with new app password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->setFrom('cartsy.marketplace@gmail.com', 'Cartsy');
    $mail->addAddress('axeldelgado.cybersec@gmail.com', 'Axel');

    $otp = rand(100000, 999999);
    $mail->isHTML(true);
    $mail->Subject = 'Your Cartsy OTP Code';
    $mail->Body    = "Hello,<br>Your OTP code is: <b>$otp</b>";

    $mail->send();
    echo "✅ OTP sent. (OTP: $otp)";
} catch (Exception $e) {
    echo "❌ Mailer Error: {$mail->ErrorInfo}";
}

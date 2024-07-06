<?php
session_start();
require_once 'config.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Adjust the path if necessary

function generateOTP() {
    return rand(100000, 999999);
}

function sendOTP($email, $otp) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.example.com'; // Set the SMTP server to send through
        $mail->SMTPAuth = true;
        $mail->Username = 'your-email@example.com'; // SMTP username
        $mail->Password = 'your-email-password'; // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('your-email@example.com', 'File Manager');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code';
        $mail->Body    = "Your OTP code is: $otp";
        $mail->AltBody = "Your OTP code is: $otp";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

if (isset($_POST['email'])) {
    $otp = generateOTP();
    $_SESSION['otp'] = $otp;
    $_SESSION['otp_expiry'] = time() + 300; // OTP expires in 5 minutes

    if (sendOTP($_POST['email'], $otp)) {
        echo "OTP sent successfully.";
    } else {
        echo "Failed to send OTP. Please try again.";
    }
}
?>

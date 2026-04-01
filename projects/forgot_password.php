<?php
include "db.php";
include "config.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if (isset($_POST['send'])) {
    $email = trim($_POST['email']);
    $error = '';
    $success = '';

    // Check if email exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    if (!$stmt) {
        $error = "Database error: " . $conn->error;
    } else {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            $error = "Email not found";
        } else {
            // Generate secure token + expiry
            $token  = bin2hex(random_bytes(16)); // 32 chars
            $expire = date("Y-m-d H:i:s", strtotime("+30 minutes"));

            // Save token to database
            $update = $conn->prepare(
                "UPDATE users 
                 SET reset_token = ?, reset_expire = ?
                 WHERE email = ?"
            );
            if (!$update) {
                $error = "Database error: " . $conn->error;
            } else {
                $update->bind_param("sss", $token, $expire, $email);
                $update->execute();
                $update->close();

                // Build reset URL
                $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
                $host = $_SERVER['HTTP_HOST'];

                $reset_url = $protocol . '://' . $host . '/projects/reset_password.php?token=' . $token;

                // Send email
                $mail = new PHPMailer(true);

                try {
                    $mail->isSMTP();
                    $mail->Host       = $_ENV['SMTP_HOST'];
                    $mail->SMTPAuth   = true;
                    $mail->Username   = $_ENV['SMTP_USER'];
                    $mail->Password   = $_ENV['SMTP_PASS'];
                    $mail->SMTPSecure = 'tls';
                    $mail->Port       = $_ENV['SMTP_PORT'];

                    $mail->setFrom($_ENV['SMTP_FROM_EMAIL'], $_ENV['SMTP_FROM_NAME']);
                    $mail->addAddress($email);

                    $mail->isHTML(true);
                    $mail->Subject = 'Password Reset';

                    $mail->Body = "
                        <p>You requested a password reset.</p>
                        <p>
                            <a href='$reset_url'>Click here to reset your password</a>
                        </p>
                        <p>This link will expire in 30 minutes.</p>
                    ";

                    $mail->send();
                    $success = "Password reset link sent! Check your email.";

                } catch (Exception $e) {
                    $error = "Email error: " . $mail->ErrorInfo;
                }
            }
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Strapify</title>
    <link rel="stylesheet" href="../Log-in UI/Login.css">
</head>
<body>
    <!-- Loader overlay -->
    <div id="loader" style="display:none;">
        <div class="spinner"></div>
        <p>Loading...</p>
    </div>

    <div class="container">
        <div class="left-panel">
            <div class="overlay"></div>
        </div>

        <div class="right-panel">
            <h1 class="title">Forgot Password?</h1>
            <p class="subtitle">Enter your email to reset your password.</p>

            <?php if (!empty($error)): ?>
                <div style="color:#c00;margin-bottom:10px"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
                <div style="color:#0a0;margin-bottom:10px"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <form id="ForgotF" method="POST" action="">
                <input type="email" name="email" placeholder="Email Address" required>
                <button type="submit" name="send">Send Reset Link</button>
            </form>

            <p class="back-login">
                Remembered your password? <a href="login.php" id="backLogin">Log-In</a>
            </p>
        </div>
    </div>

    <script src="../Log-in UI/login.js"></script>

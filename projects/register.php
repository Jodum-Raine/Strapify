<?php
include "db.php";
include "config.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$error = '';
$success = '';

if (isset($_POST['register'])) {
    $name  = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $pass  = $_POST['password'];
    $cpass = $_POST['confirm_password'];

    if ($pass !== $cpass) {
        $error = "Passwords do not match";
    } else {
        // Check if email already exists
        $check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            $error = "Email already exists";
        } else {
            $hashed = password_hash($pass, PASSWORD_DEFAULT);
            $code = md5(rand());

            // Insert user
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, verification_code) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $hashed, $code);

            if ($stmt->execute()) {
                // Send verification email
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'not.yuriusu@gmail.com';
                    $mail->Password = 'qhujafxexemykgor';
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;

                    $mail->setFrom($_ENV['SMTP_FROM_EMAIL'], $_ENV['SMTP_FROM_NAME']);
                    $mail->addAddress($email);
                    $mail->isHTML(true);
                    $mail->Subject = 'Email Verification';
                    
                    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
                    $host = $_SERVER['HTTP_HOST'];
                    if ($host === 'localhost' || $host === '127.0.0.1') {
                        $host = 'localhost';
                    }

                    $verify_url = $protocol . '://' . $host . '/projects/verify.php?code=' . $code;
                    $mail->Body = "Click to verify your account: <a href='$verify_url'>Verify Email</a>";
                    $mail->send();

                    $success = "Registered! Check your email to verify.";

                } catch (Exception $e) {
                    $error = "Email error: " . $mail->ErrorInfo;
                }
            } else {
                $error = "Registration error: " . $stmt->error;
            }
            $stmt->close();
        }
        $check_stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register - Strapify</title>
<link rel="stylesheet" href="../Log-in UI/Login.css">
</head>
<body>

<div class="container">
    <div class="left-panel">
        <div class="overlay"></div>
    </div>

    <div class="right-panel">
        <h1 class="title">Create Account</h1>
        <p class="subtitle">Join Strapify to get started.</p>

        <?php if (!empty($error)): ?>
            <div style="color:#c00;margin-bottom:5px"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div style="color:#0a0;margin-bottom:10px"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <?php if (empty($success)): ?>
            <form id="RegisterF" method="POST" action="">
                <input type="text" name="name" placeholder="Full Name" required>
                <input type="email" name="email" placeholder="Email Address" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                <button type="submit" name="register">Register</button>
            </form>
        <?php endif; ?>

        <p class="back-login">
            Already have an account? <a href="login.php" id="backLogin">Log-In</a>
        </p>
    </div>
</div>

<script src="../Log-in UI/login.js"></script>
</body>
</html>

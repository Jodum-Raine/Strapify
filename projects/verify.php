<?php
include "db.php";

$error = '';
$success = '';

if (isset($_GET['code'])) {
    $code = $_GET['code'];

    // Check if code exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE verification_code = ?");
    if ($stmt) {
        $stmt->bind_param("s", $code);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Update verification status
            $update_stmt = $conn->prepare("UPDATE users SET is_verified=1 WHERE verification_code = ?");
            $update_stmt->bind_param("s", $code);
            if ($update_stmt->execute()) {
                $success = "Email verified! Redirecting to login...";
                header("Refresh: 2; url=login.php"); // Auto-redirect after 2 seconds
            } else {
                $error = "Error during verification. Please try again.";
            }
            $update_stmt->close();
        } else {
            $error = "Invalid verification link";
        }
        $stmt->close();
    } else {
        $error = "Database error. Please try again later.";
    }
} else {
    $error = "No verification code provided.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Email Verification - Strapify</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../Log-in UI/Login.css">
<style>
    /* Minimal adjustments to fit the existing login/register UI */
    .container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: auto;  /* remove full height */
    margin: 50px auto; /* give some spacing from top/bottom */
    }
    .right-panel {
    width: 100%;
    padding: 30px 20px;  /* smaller padding */
    max-width: 400px;    /* same width as register/reset */
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    text-align: center;
    }
    .right-panel h1.title {
        margin-bottom: 10px;
        color: #333;
    }
    .right-panel p.subtitle {
        margin-bottom: 20px;
        color: #555;
    }
    .message {
        font-weight: 500;
        margin-bottom: 20px;
    }
    .message.error { color: #c00; }
    .message.success { color: #0a0; }
    .back-login a {
        color: #2575fc;
        font-weight: 500;
        text-decoration: none;
    }
    .back-login a:hover { text-decoration: underline; }
</style>
</head>
<body>

<div class="container">
    <div class="right-panel">
        <h1 class="title">Email Verification</h1>

        <?php if (!empty($error)): ?>
            <div class="message error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="message success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <?php if (empty($success)): ?>
            <p class="subtitle">Click the link in your email to verify your account.</p>
        <?php endif; ?>

        <div class="back-login">
            <a href="login.php">Back to Login</a>
        </div>
    </div>
</div>

</body>
</html>

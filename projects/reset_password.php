<?php
include "db.php";

$error = '';
$success = '';

if (!isset($_GET['token'])) {
    $error = "Invalid request";
}

if (!$error) {
    $token = $_GET['token'];

    // Check if token exists and is valid
    $stmt = $conn->prepare(
        "SELECT id, reset_expire FROM users
        WHERE reset_token = ?"
    );

    if (!$stmt) {
        $error = "Database error: " . $conn->error;
    } else {
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $check = $stmt->get_result();

        if ($check->num_rows == 0) {
            $error = "Reset link is invalid - token not found in database";
        } else {
            $row = $check->fetch_assoc();
            $expire_time = $row['reset_expire'];

            // Check if token is expired
            $current_time = date("Y-m-d H:i:s");
            if (empty($expire_time) || $expire_time < $current_time) {
                $error = "Reset link expired. Expiration was: " . $expire_time . " | Current time: " . $current_time;
            }
        }
        $stmt->close();
    }
}

// Token is valid - show form
if (!$error && isset($_POST['reset'])) {
    $pass = $_POST['password'];
    $cpass = $_POST['confirm_password'];

    if ($pass !== $cpass) {
        $error = "Passwords do not match";
    } elseif (strlen($pass) < 6) {
        $error = "Password must be at least 6 characters";
    } else {
        $hashed = password_hash($pass, PASSWORD_DEFAULT);

        // Use prepared statement for update
        $update_stmt = $conn->prepare(
            "UPDATE users 
             SET password=?, reset_token=NULL, reset_expire=NULL 
             WHERE reset_token=?"
        );
        $update_stmt->bind_param("ss", $hashed, $token);
        
        if ($update_stmt->execute()) {
            $success = "Password updated! Redirecting to login...";
            header("Refresh: 2; url=/projects/login.php");
        } else {
            $error = "Error updating password. Please try again.";
        }
        $update_stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Strapify</title>
    <link rel="stylesheet" href="../Log-in UI/Login.css">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .container {
            display: flex;
            width: 100%;
            max-width: 500px;
        }
        .left-panel {
            display: none;
        }
        .right-panel {
            width: 100%;
            padding: 40px;
        }
    </style>
</head>
<body>
    <!-- Loader overlay -->
    <div id="loader" style="display:none;">
        <div class="spinner"></div>
        <p>Loading...</p>
    </div>

    <div class="container">
        <div class="right-panel">
            <h1 class="title">Reset Your Password</h1>
            <p class="subtitle">Enter your new password below.</p>

            <?php if (!empty($error)): ?>
                <div style="color:#c00;margin-bottom:10px"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
                <div style="color:#0a0;margin-bottom:10px"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <form id="ResetF" method="POST" action="">
                <input type="password" name="password" placeholder="New Password" required>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                <button type="submit" name="reset">Reset Password</button>
            </form>

            <p class="back-login">
                Remember your password? <a href="login.php" id="backLogin">Log-In</a>
            </p>
        </div>
    </div>

    <script src="../Log-in UI/login.js"></script>
</body>
</html>
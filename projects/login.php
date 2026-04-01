<?php
session_start();
include "db.php";

$error = '';
$passwordError = '';

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $pass  = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($pass, $user['password'])) {

    session_regenerate_id(true);
    $_SESSION['user'] = $user['name'];
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['role'] = $user['role'];

    // ADMIN LOGIN
    if ($user['role'] === 'admin') {
        header("Location: ../projects/admin/admin_dashboard.php");
        exit();
    }

    // USER MUST VERIFY EMAIL
    if ((int)$user['is_verified'] !== 1) {
        $error = 'Please verify your email first.';
    } else {
        header("Location: ../projects/dashboard/Dashboard1.php");
        exit();
    }

} else {
    $passwordError = 'Wrong password';
}
    } else {
        $error = 'Account not found';
    }
    $stmt->close();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Strapify - Login</title>
    <link rel="stylesheet" href="../Log-in UI/Login.css">
    <style>/* minimal inline fix for container if needed */</style>
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
        <h1 class="title">Welcome to Strapify!</h1>
        <p class="subtitle">Log in to your account to continue.</p>

        <?php if (isset($result) && isset($result->num_rows) && $result->num_rows == 0): ?>
            <div style="color:#c00;margin-bottom:10px">Account not found</div>
        <?php endif; ?>
        <?php if (isset($error) && $error): ?>
            <div style="color:#c00;margin-bottom:10px"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

    <form id="LoginF" method="POST" action="">
    <input type="email" name="email" placeholder="Email" required
        value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">

    <input type="password" name="password" placeholder="Password" required>

    <?php if (!empty($passwordError)): ?>
        <div class="field-error"><?php echo htmlspecialchars($passwordError); ?></div>
        <?php endif; ?>

    <a href="forgot_password.php" class="forgot" id="forgotLink">Forgot your password?</a>
    <button type="submit" name="login">Log-In</button>
</form>


        <p class="signup">
            Don't have an account? <a href="register.php">Register</a>
        </p>
    </div>
</div>

<!-- <script src="../Log-in UI/login.js"></script>  -->
</body>
</html>
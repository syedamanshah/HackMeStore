<?php
require 'db.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $new_password = $_POST['new_password'];

    // Basic input validation (for demonstration purposes)
    if (empty($username) || empty($new_password)) {
        $error = 'All fields are required.';
    } else {
        // Insecure design: No verification of username ownership, directly updating the password
        $sql = "UPDATE users SET password = ? WHERE username = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$new_password, $username]);

        $success = 'Password reset successfully. You can now <a href="login.php">login</a> with your new password.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HackMeStore - Reset Password</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="reset-container">
        <div class="reset-form">
            <h2>Reset Password</h2>
            <?php if (!empty($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
                <p class="success"><?php echo $success; ?></p>
            <?php endif; ?>
            <form method="POST" action="reset_password.php">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="new_password">New Password:</label>
                    <input type="password" id="new_password" name="new_password" required>
                </div>
                <button type="submit">Reset Password</button>
            </form>
        </div>
    </div>
    <footer class="footer">
        <p>Made by Syed Aman Shah</p>
    </footer>
</body>
</html>

<?php
require 'db.php';

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if session is not started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// Allow user to specify which profile to view via GET parameter
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : $_SESSION['user']['id'];

// Fetch user profile
$sql = "SELECT * FROM users WHERE id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Basic input validation (for demonstration purposes)
    if (empty($username) || empty($email) || empty($password)) {
        $error = 'All fields are required.';
    } else {
        // Update user profile without proper authorization check
        $sql = "UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$username, $email, $password, $user_id]);

        // Update session user data if editing their own profile
        if ($user_id == $_SESSION['user']['id']) {
            $_SESSION['user']['username'] = $username;
            $_SESSION['user']['email'] = $email;
            $_SESSION['user']['password'] = $password;

            // Intentionally not logging the password change
            // log_security_event("Password changed for username: $username");
        }

        $success = 'Profile updated successfully.';
    }
}
function log_security_event($message) {
    $log_file = 'security.log';
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($log_file, "[$timestamp] $message\n", FILE_APPEND);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HackMeStore - Profile</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>HackMeStore</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="products.php">View Products</a></li>
                <li><a href="cart.php">View Cart</a></li>
                <li><a href="history.php">Order History</a></li>
                <li><a href="profile.php">Profile</a></li>
                <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                    <li><a href="admin.php">Admin</a></li>
                <?php endif; ?>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <div class="login-container">
        <div class="form-group">
            <h2>Profile</h2>
            <?php if (!empty($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
                <p class="success"><?php echo $success; ?></p>
            <?php endif; ?>
            <form method="POST" action="profile.php?user_id=<?php echo $user_id; ?>">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($user['password'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>
                <button type="submit">Update Profile</button>
            </form>
        </div>
    </div>
    <footer class="footer">
        <p>Made by Syed Aman Shah</p>
    </footer>
</body>
</html>

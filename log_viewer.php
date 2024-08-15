<?php
require 'db.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if session is not started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// Path to the log file
$logFile = 'security.log';

// Check if log file exists
if (!file_exists($logFile)) {
    echo "Log file does not exist.";
    exit;
}

// Read the log file
$logContent = file_get_contents($logFile);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HackMeStore - Log Viewer</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>HackMeStore - Log Viewer</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="products.php">View Products</a></li>
                <li><a href="cart.php">View Cart</a></li>
                <li><a href="history.php">Order History</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <div class="log-container">
            <h2>Security Logs:</h2>
            <pre><?php echo htmlspecialchars($logContent, ENT_QUOTES, 'UTF-8'); ?></pre>
        </div>
    </main>
    <footer>
        <p>Made by Syed Aman Shah</p>
    </footer>
</body>
</html>

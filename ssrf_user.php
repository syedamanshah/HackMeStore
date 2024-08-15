<?php
require 'db.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if session is not started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$response = '';
if (isset($_POST['url'])) {
    $url = $_POST['url'];

    // Vulnerable to SSRF
    $response = file_get_contents($url);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HackMeStore - SSRF</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>HackMeStore - SSRF</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="products.php">View Products</a></li>
                <li><a href="cart.php">View Cart</a></li>
                <li><a href="history.php">Order History</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="ssrf_user.php">Fetch URL</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <div class="form-container">
            <h2>Fetch Data from URL</h2>
            <form method="POST" action="ssrf_user.php">
                <div class="form-group">
                    <label for="url">URL:</label>
                    <input type="text" id="url" name="url" required>
                </div>
                <button type="submit">Fetch</button>
            </form>
            <?php if ($response): ?>
                <h3>Response:</h3>
                <pre><?php echo htmlspecialchars($response, ENT_QUOTES, 'UTF-8'); ?></pre>
            <?php endif; ?>
        </div>
    </main>
    <footer>
        <p>Made by Syed Aman Shah</p>
    </footer>
</body>
</html>

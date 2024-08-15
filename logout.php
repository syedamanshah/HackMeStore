<?php
// Start the session
session_start();

// Check if the user confirmed the logout
if (isset($_POST['confirm_logout'])) {
    // Destroy the session
    session_destroy();

    // Redirect to the login page
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HackMeStore - Logout</title>
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
                <li><a href="order_history.php">Order History</a></li>
                <li><a href="profile.php">Profile</a></li>
                <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                    <li><a href="admin.php">Admin</a></li>
                <?php endif; ?>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <div class="main-container">
        <div class="logout-container">
            <h2>Confirm Logout</h2>
            <p>Are you sure you want to log out?</p>
            <form method="POST" action="logout.php">
                <button type="submit" name="confirm_logout" class="logout-button">Yes, Log me out</button>
                <a href="index.php" class="cancel-button">Cancel</a>
            </form>
        </div>
    </div>
    <footer class="footer">
        <p>Made by Syed Aman Shah</p>
    </footer>
</body>
</html>


<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You - HackMeStore</title>
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
                <?php if ($user['role'] === 'admin'): ?>
                    <li><a href="admin.php">Admin</a></li>
                <?php endif; ?>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <div class="thank-you-container">
            <h2>Thank You for Your Order!</h2>
            <p>Your order has been successfully placed. We appreciate your business and hope you enjoy your purchase.</p>
            <p>You will receive an email confirmation shortly with your order details.</p>
            <a href="products.php" class="btn">Continue Shopping</a>
        </div>
    </main>
    <footer>
        <p>Made by Syed Aman Shah</p>
    </footer>
</body>
</html>


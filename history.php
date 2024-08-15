<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'db.php';

// Remove the session check
// if (session_status() == PHP_SESSION_NONE) {
//     session_start();
// }

// if (!isset($_SESSION['user'])) {
//     header('Location: login.php');
//     exit;
// }

// Fetch all order histories instead of specific user
$sql = "SELECT orders.id, products.name AS product_name, orders.quantity, orders.total, orders.shipping_address, orders.payment_method 
        FROM orders 
        JOIN products ON orders.product_id = products.id 
        ORDER BY orders.id DESC";  // Using `id` to sort orders
$stmt = $pdo->query($sql);
$orders = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HackMeStore - Order History</title>
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
                <li><a href="profile.php">Profile</a></li>
                <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                    <li><a href="admin.php">Admin</a></li>
                <?php endif; ?>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main class="main-container">
        <div class="history-container">
            <h2>Order History</h2>
            <?php if ($orders): ?>
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Shipping Address</th>
                            <th>Payment Method</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($order['id'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($order['product_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($order['quantity'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td>$<?php echo htmlspecialchars($order['total'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($order['shipping_address'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($order['payment_method'], ENT_QUOTES, 'UTF-8'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>You have no order history. Browse our <a href="products.php">products</a> and place your first order!</p>
            <?php endif; ?>
        </div>
    </main>
    <footer class="footer">
        <p>Made by Syed Aman Shah</p>
    </footer>
</body>
</html>

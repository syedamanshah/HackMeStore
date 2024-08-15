<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'db.php';

// Check if session is not started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user']['id'];

// Fetch cart items
$sql = "SELECT products.id AS product_id, products.name, products.price, cart_items.quantity, (products.price * cart_items.quantity) AS total 
        FROM cart_items 
        JOIN products ON cart_items.product_id = products.id 
        WHERE cart_items.user_id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['user_id' => $user_id]);
$cart_items = $stmt->fetchAll();

// Calculate total amount
$total_amount = array_reduce($cart_items, function($sum, $item) {
    return $sum + $item['total'];
}, 0);

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Process the order
        $shipping_address = $_POST['shipping_address'];
        $payment_method = $_POST['payment_method'];

        // Insert each item in the cart as a separate row in the orders table
        foreach ($cart_items as $item) {
            $insert_order_sql = "INSERT INTO orders (user_id, product_id, quantity, total, shipping_address, payment_method) VALUES (:user_id, :product_id, :quantity, :total, :shipping_address, :payment_method)";
            $stmt = $pdo->prepare($insert_order_sql);
            $stmt->execute([
                'user_id' => $user_id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'total' => $item['total'],
                'shipping_address' => $shipping_address,
                'payment_method' => $payment_method
            ]);
        }

        // Clear the cart
        $clear_cart_sql = "DELETE FROM cart_items WHERE user_id = :user_id";
        $stmt = $pdo->prepare($clear_cart_sql);
        $stmt->execute(['user_id' => $user_id]);

        // Redirect to the thank you page
        header('Location: thank_you.php');
        exit;
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HackMeStore - Checkout</title>
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
    <main>
        <div class="checkout-container">
            <h2>Checkout</h2>
            <?php if ($cart_items): ?>
                <table class="checkout-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart_items as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td>$<?php echo htmlspecialchars($item['price'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($item['quantity'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td>$<?php echo htmlspecialchars($item['total'], ENT_QUOTES, 'UTF-8'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="3" style="text-align: right;"><strong>Total Amount:</strong></td>
                            <td><strong>$<?php echo htmlspecialchars($total_amount, ENT_QUOTES, 'UTF-8'); ?></strong></td>
                        </tr>
                    </tbody>
                </table>
                <form method="POST" action="checkout.php" class="checkout-form">
                    <div class="form-group">
                        <label for="shipping_address">Shipping Address:</label>
                        <input type="text" id="shipping_address" name="shipping_address" required>
                    </div>
                    <div class="form-group">
                        <label for="payment_method">Payment Method:</label>
                        <select id="payment_method" name="payment_method" required>
                            <option value="Credit Card">Credit Card</option>
                            <option value="PayPal">PayPal</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                        </select>
                    </div>
                    <button type="submit" class="checkout-btn">Place Order</button>
                </form>
            <?php else: ?>
                <p>Your shopping cart is currently empty. Browse our <a href="products.php">products</a> and add items to your cart.</p>
            <?php endif; ?>
        </div>
    </main>
    <footer>
        <p>Made by Syed Aman Shah</p>
    </footer>
</body>
</html>


<?php
require 'db.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Ensure user is authenticated
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user']['id'];

// Handle form submissions for updating and deleting cart items
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update'])) {
        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'];

        if ($quantity > 0) {
            // Update the quantity in the cart
            $update_cart_sql = "UPDATE cart_items SET quantity = :quantity WHERE user_id = :user_id AND product_id = :product_id";
            $stmt = $pdo->prepare($update_cart_sql);
            $stmt->execute(['quantity' => $quantity, 'user_id' => $user_id, 'product_id' => $product_id]);
        } else {
            // Delete the item from the cart if quantity is set to 0
            $delete_cart_sql = "DELETE FROM cart_items WHERE user_id = :user_id AND product_id = :product_id";
            $stmt = $pdo->prepare($delete_cart_sql);
            $stmt->execute(['user_id' => $user_id, 'product_id' => $product_id]);
        }
    } elseif (isset($_POST['delete'])) {
        $product_id = $_POST['product_id'];
        // Delete the item from the cart
        $delete_cart_sql = "DELETE FROM cart_items WHERE user_id = :user_id AND product_id = :product_id";
        $stmt = $pdo->prepare($delete_cart_sql);
        $stmt->execute(['user_id' => $user_id, 'product_id' => $product_id]);
    } elseif (isset($_POST['product_id']) && isset($_POST['quantity'])) {
        // Adding new product to cart
        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'];

        // Insert product into cart (assuming a 'cart_items' table with user_id, product_id, and quantity)
        $sql = "INSERT INTO cart_items (user_id, product_id, quantity) VALUES (:user_id, :product_id, :quantity)
                ON DUPLICATE KEY UPDATE quantity = quantity + :quantity";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['user_id' => $user_id, 'product_id' => $product_id, 'quantity' => $quantity]);
    }
}

// Fetch cart items
$sql = "SELECT products.id, products.name, products.price, cart_items.quantity, (products.price * cart_items.quantity) AS total 
        FROM cart_items 
        JOIN products ON cart_items.product_id = products.id 
        WHERE cart_items.user_id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['user_id' => $user_id]);
$cart_items = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HackMeStore - Cart</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function updateQuantity(action, productId) {
            var quantityInput = document.getElementById('quantity-' + productId);
            var currentQuantity = parseInt(quantityInput.value);

            if (action === 'increment') {
                quantityInput.value = currentQuantity + 1;
            } else if (action === 'decrement' && currentQuantity > 1) {
                quantityInput.value = currentQuantity - 1;
            }
        }
    </script>
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
    <div class="main-container">
        <h2>Your Cart</h2>
        <div class="cart-container">
        <?php if ($cart_items): ?>
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td>$<?php echo htmlspecialchars($item['price'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td>
                                <form method="POST" action="cart.php">
                                    <div class="quantity-controls">
                                        <button type="button" onclick="updateQuantity('decrement', <?php echo $item['id']; ?>)">-</button>
                                        <input type="number" id="quantity-<?php echo $item['id']; ?>" name="quantity" value="<?php echo htmlspecialchars($item['quantity'], ENT_QUOTES, 'UTF-8'); ?>" min="1" readonly>
                                        <button type="button" onclick="updateQuantity('increment', <?php echo $item['id']; ?>)">+</button>
                                    </div>
                            </td>
                            <td>$<?php echo htmlspecialchars($item['total'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td>
                                    <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                    <input type="hidden" name="quantity" id="form-quantity-<?php echo $item['id']; ?>" value="<?php echo $item['quantity']; ?>">
                                    <button type="submit" name="update" class="update-btn" onclick="document.getElementById('form-quantity-<?php echo $item['id']; ?>').value = document.getElementById('quantity-<?php echo $item['id']; ?>').value;">Update</button>
                                    <button type="submit" name="delete" class="delete-btn">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="cart-actions">
                <button onclick="window.location.href='checkout.php'">Proceed to Checkout</button>
            </div>
        <?php else: ?>
            <p>Your shopping cart is currently empty. Browse our <a href="products.php">products</a> and add items to your cart.</p>
        <?php endif; ?>
        </div>
    </div>
    <footer class="footer">
        <p>Made by Syed Aman Shah</p>
    </footer>
</body>
</html>


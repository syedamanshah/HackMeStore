<?php
require 'db.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if session is not started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}


$search_term = '';
$sql = "SELECT * FROM products";

if (isset($_GET['search'])) {
    $search_term = $_GET['search'];
    // XSS Vulnerability: Not escaping user input
    $sql = "SELECT * FROM products WHERE name LIKE :search_term";
}

$stmt = $pdo->prepare($sql);

if (isset($_GET['search'])) {
    $stmt->execute(['search_term' => '%' . $search_term . '%']);
} else {
    $stmt->execute();
}

$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HackMeStore - Products</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function showQuantitySelector(button, productId) {
            button.style.display = 'none';
            document.getElementById('quantity-form-' + productId).style.display = 'block';
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
        <form class="search-form" method="GET" action="products.php">
            <!-- Reflect search term directly in the input field to allow XSS -->
            <input type="text" name="search" placeholder="Search for products..." value="<?php echo $search_term; ?>">
            <button type="submit">Search</button>
        </form>
        <!-- Directly echo the search term to simulate reflected XSS -->
        <p><?php echo $search_term; ?></p>
        <div class="products-container">
            <h2>Products</h2>
            <div class="products-grid">
                <?php foreach ($products as $product): ?>
                    <div class="product-item">
                        <img src="<?php echo htmlspecialchars($product['image_url'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?>">
                        <h3><?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?></h3>
                        <p><?php echo htmlspecialchars($product['description'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <p>$<?php echo htmlspecialchars($product['price'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <button class="add-to-cart-btn" onclick="showQuantitySelector(this, <?php echo $product['id']; ?>)">Add to Cart</button>
                        <form id="quantity-form-<?php echo $product['id']; ?>" method="POST" action="cart.php" class="quantity-form" style="display: none;">
                            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id'], ENT_QUOTES, 'UTF-8'); ?>">
                            <input type="number" name="quantity" value="1" min="1">
                            <button type="submit">Add to Cart</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <footer class="footer">
        <p>Made by Syed Aman Shah</p>
    </footer>
</body>
</html>

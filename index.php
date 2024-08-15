<?php
require 'db.php';

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
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['search_term' => '%' . $search_term . '%']);
} else {
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}

$products = $stmt->fetchAll();


$mobilesQuery = "SELECT * FROM products WHERE category = 'Smartphones' LIMIT 5";
$mobilesStmt = $pdo->query($mobilesQuery);
$topMobiles = $mobilesStmt->fetchAll();

$laptopsQuery = "SELECT * FROM products WHERE category = 'Laptops' LIMIT 5";
$laptopsStmt = $pdo->query($laptopsQuery);
$topLaptops = $laptopsStmt->fetchAll();

$consolesQuery = "SELECT * FROM products WHERE category = 'Gaming Consoles' LIMIT 5";
$consolesStmt = $pdo->query($consolesQuery);
$topConsoles = $consolesStmt->fetchAll();

$accessoriesQuery = "SELECT * FROM products WHERE category = 'Accessories' LIMIT 5";
$accessoriesStmt = $pdo->query($accessoriesQuery);
$topAccessories = $accessoriesStmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HackMeStore - Products</title>
    <link rel="stylesheet" href="style.css">
    <!-- Insecure jQuery inclusion -->
    <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
</head>
<body>
    <header>
        <h1>HackMeStore</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="cart.php">View Cart</a></li>
                <li><a href="history.php">Order History</a></li>
                <li><a href="profile.php">Profile</a></li>
                <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                    <li><a href="admin.php">Admin</a></li>
                <?php endif; ?>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
        <form action="index.php" method="get" class="search-form">
            <!-- Reflect search term directly in the input field to allow XSS -->
            <input type="text" name="search" placeholder="Search for products..." value="<?php echo $search_term; ?>">
            <button type="submit">Search</button>
        </form>
         <!-- Directly echo the search term to simulate reflected XSS -->
         <p><?php echo $search_term; ?></p>
    </header>
    <main>
    <div class="slider">
            <div class="slides">
                <img src="images/slider_img/slide1.jpg" alt="Slide 1">
            </div>
        </div>
        <div class="categories">
            <div class="category">
                <h3>Mobiles</h3>
                <p>Latest and greatest mobile phones</p>
                <h4>Top Mobiles</h4>
                <div class="products">
                    <?php if (count($topMobiles) > 0): ?>
                        <?php foreach ($topMobiles as $mobile): ?>
                            <div class="product">
                                <img src="<?php echo htmlspecialchars($mobile['image_url'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($mobile['name'], ENT_QUOTES, 'UTF-8'); ?>">
                                <div class="product-info">
                                    <h3><?php echo htmlspecialchars($mobile['name'], ENT_QUOTES, 'UTF-8'); ?></h3>
                                    <p><?php echo htmlspecialchars($mobile['description'], ENT_QUOTES, 'UTF-8'); ?></p>
                                    <p>$<?php echo htmlspecialchars($mobile['price'], ENT_QUOTES, 'UTF-8'); ?></p>
                                </div>
                                <form method="POST" action="cart.php">
                                    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($mobile['id'], ENT_QUOTES, 'UTF-8'); ?>">
                                    <button type="submit">Add to Cart</button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No mobiles available.</p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="category">
                <h3>Laptops</h3>
                <p>High-performance laptops for all your needs</p>
                <h4>Top Laptops</h4>
                <div class="products">
                    <?php if (count($topLaptops) > 0): ?>
                        <?php foreach ($topLaptops as $laptop): ?>
                            <div class="product">
                                <img src="<?php echo htmlspecialchars($laptop['image_url'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($laptop['name'], ENT_QUOTES, 'UTF-8'); ?>">
                                <div class="product-info">
                                    <h3><?php echo htmlspecialchars($laptop['name'], ENT_QUOTES, 'UTF-8'); ?></h3>
                                    <p><?php echo htmlspecialchars($laptop['description'], ENT_QUOTES, 'UTF-8'); ?></p>
                                    <p>$<?php echo htmlspecialchars($laptop['price'], ENT_QUOTES, 'UTF-8'); ?></p>
                                </div>
                                <form method="POST" action="cart.php">
                                    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($laptop['id'], ENT_QUOTES, 'UTF-8'); ?>">
                                    <button type="submit">Add to Cart</button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No laptops available.</p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="category">
                <h3>Gaming Consoles</h3>
                <p>Top gaming consoles for an immersive experience</p>
                <h4>Top Gaming Consoles</h4>
                <div class="products">
                    <?php if (count($topConsoles) > 0): ?>
                        <?php foreach ($topConsoles as $console): ?>
                            <div class="product">
                                <img src="<?php echo htmlspecialchars($console['image_url'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($console['name'], ENT_QUOTES, 'UTF-8'); ?>">
                                <div class="product-info">
                                    <h3><?php echo htmlspecialchars($console['name'], ENT_QUOTES, 'UTF-8'); ?></h3>
                                    <p><?php echo htmlspecialchars($console['description'], ENT_QUOTES, 'UTF-8'); ?></p>
                                    <p>$<?php echo htmlspecialchars($console['price'], ENT_QUOTES, 'UTF-8'); ?></p>
                                </div>
                                <form method="POST" action="cart.php">
                                    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($console['id'], ENT_QUOTES, 'UTF-8'); ?>">
                                    <button type="submit">Add to Cart</button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No gaming consoles available.</p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="category">
                <h3>Accessories</h3>
                <p>Essential accessories for your gadgets</p>
                <h4>Top Accessories</h4>
                <div class="products">
                    <?php if (count($topAccessories) > 0): ?>
                        <?php foreach ($topAccessories as $accessory): ?>
                            <div class="product">
                                <img src="<?php echo htmlspecialchars($accessory['image_url'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($accessory['name'], ENT_QUOTES, 'UTF-8'); ?>">
                                <div class="product-info">
                                    <h3><?php echo htmlspecialchars($accessory['name'], ENT_QUOTES, 'UTF-8'); ?></h3>
                                    <p><?php echo htmlspecialchars($accessory['description'], ENT_QUOTES, 'UTF-8'); ?></p>
                                    <p>$<?php echo htmlspecialchars($accessory['price'], ENT_QUOTES, 'UTF-8'); ?></p>
                                </div>
                                <form method="POST" action="cart.php">
                                    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($accessory['id'], ENT_QUOTES, 'UTF-8'); ?>">
                                    <button type="submit">Add to Cart</button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No accessories available.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
    <footer>
        <p>Made by Syed Aman Shah</p>
    </footer>
    <script>
        let slideIndex = 0;
        showSlides();

        function showSlides() {
            let slides = document.querySelectorAll(".slides img");
            for (let i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }
            slideIndex++;
            if (slideIndex > slides.length) {slideIndex = 1}
            slides[slideIndex - 1].style.display = "block";
            setTimeout(showSlides, 3000); // Change image every 3 seconds
        }
    </script>
</body>
</html>
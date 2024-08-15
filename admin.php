<?php
require 'db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $stock_quantity = $_POST['stock_quantity'];
    $image_url = $_POST['image_url'];
    $brand = $_POST['brand'];

    // Insert product into database
    $sql = "INSERT INTO products (name, price, description, category, stock_quantity, image_url, brand)
            VALUES ('$name', '$price', '$description', '$category', '$stock_quantity', '$image_url', '$brand')";
    $pdo->exec($sql);

    header('Location: admin.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HackMeStore - Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>HackMeStore Admin</h1>
        <nav>
            <ul>
                <li><a href="products.php">View Products</a></li>
                <li><a href="cart.php">View Cart</a></li>
                <li><a href="history.php">Order History</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="admin.php">Admin</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <div class="form-container">
            <h2>Add New Product</h2>
            <form method="POST" action="admin.php">
                <div class="form-group">
                    <label for="name">Product Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="price">Price:</label>
                    <input type="text" id="price" name="price" required>
                </div>
                <div class="form-group">
                    <label for="description">Features:</label>
                    <textarea id="description" name="description" required></textarea>
                </div>
                <div class="form-group">
                    <label for="category">Category:</label>
                    <input type="text" id="category" name="category" required>
                </div>
                <div class="form-group">
                    <label for="stock_quantity">Stock Quantity:</label>
                    <input type="number" id="stock_quantity" name="stock_quantity" required>
                </div>
                <div class="form-group">
                    <label for="image_url">Image URL:</label>
                    <input type="text" id="image_url" name="image_url">
                </div>
                <div class="form-group">
                    <label for="brand">Brand:</label>
                    <input type="text" id="brand" name="brand" required>
                </div>
                <button type="submit">Add Product</button>
            </form>
        </div>
    </main>
    <footer>
        <p>Made by Syed Aman Shah</p>
    </footer>
</body>
</html>


<?php
require 'db.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$product_id = $_GET['id'];

// SQL Injection vulnerability
$sql = "SELECT * FROM products WHERE id = $product_id";
$stmt = $pdo->query($sql);
$product = $stmt->fetch();
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?></title>
</head>
<body>
    <h1><?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?></h1>
    <p><?php echo htmlspecialchars($product['description'], ENT_QUOTES, 'UTF-8'); ?></p>
    <p>Price: $<?php echo $product['price']; ?></p>
    
    <h2>Add to Cart</h2>
    <form action="cart.php" method="POST">
        Quantity: <input type="number" name="quantity" value="1"><br>
        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
        <input type="submit" value="Add to Cart">
    </form>
</body>
</html>


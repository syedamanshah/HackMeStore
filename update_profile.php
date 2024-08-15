<?php
require 'db.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$user = $_SESSION['user'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $password = $_POST['password'];
    
    // SQL Injection vulnerability
    $sql = "UPDATE users SET password = '$password' WHERE id = " . $user['id'];
    $pdo->exec($sql);
    
    header('Location: profile.php');
    exit;
}
?>


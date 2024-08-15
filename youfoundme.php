<?php
require 'db.php';

// Fetch all users from the database
$sql = "SELECT * FROM users";
$stmt = $pdo->query($sql);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Set the Content-Type header to application/json
header('Content-Type: application/json');

// Print the users data as JSON
echo json_encode($users, JSON_PRETTY_PRINT);
?>

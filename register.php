<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HackMeStore - Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <div class="login-form">
            <h2>Register at HackMeStore</h2>
            <form method="POST" action="register.php">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit">Register</button>
                <p>Already have an account? <a href="login.php">Login here</a></p>
            </form>
            <?php
            require 'db.php';

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $username = $_POST['username'];
                $password = $_POST['password'];

                // Store password in plain text (cryptographic failure)
                // SQL Injection vulnerability
                $sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
                $pdo->exec($sql);

                header('Location: login.php');
                exit;
            }
            ?>
        </div>
    </div>
    <footer>
        <p>Made by Syed Aman Shah</p>
    </footer>
</body>
</html>

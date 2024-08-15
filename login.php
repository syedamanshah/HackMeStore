<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HackMeStore - Login</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <div class="login-container">
        <h2>Login to HackMeStore</h2>
        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <p><a href="reset_password.php">Forgot password?</a></p> 


            </div>
            
            <button type="submit">Login</button>
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </form>
        <?php
        require 'db.php';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            // SQL Injection vulnerability
            $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
            $stmt = $pdo->query($sql);
            $user = $stmt->fetch();

            if ($user) {
                $_SESSION['user'] = $user;
                header('Location: index.php');
                exit;
            } else {
                echo "<p class='error'>Invalid username or password</p>";
                log_security_event("Failed login attempt for username: $username");
            }
        }
        function log_security_event($message) {
            $log_file = 'security.log';
            $timestamp = date('Y-m-d H:i:s');
            file_put_contents($log_file, "[$timestamp] $message\n", FILE_APPEND);
        }
        ?>
    </div>
    <footer>
        <p>Made by Syed Aman Shah</p>
    </footer>
</body>
</html>

<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Compliance Management System</title>
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
    <div class="container">
        <div class="loginbox">
            <div class="brand">
                <h1>CMS</h1>
                <p>Compliance Management System</p>
            </div>

            <?php
            if (isset($_SESSION['success_message'])) {
                echo '<div class="success-message">' . htmlspecialchars($_SESSION['success_message']) . '</div>';
                unset($_SESSION['success_message']);
            }
            if (isset($_SESSION['error'])) {
                echo '<div class="error-message">' . htmlspecialchars($_SESSION['error']) . '</div>';
                unset($_SESSION['error']);
            }
            ?>

            <form action="login_process.php" method="post">
                <div class="input-box">
                    <input type="text" id="username" name="username" placeholder="Enter your username" required>
                    <label for="username">Username</label>
                </div>

                <div class="input-box">
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    <label for="password">Password</label>
                </div>

                <div class="remember-forget">
                    <label>
                        <input type="checkbox" name="remember">
                        Remember me
                    </label>
                    <a href="forgot_password.php">Forgot Password?</a>
                </div>

                <div class="submit">
                    <button type="submit">Sign In</button>
                </div>

                <div class="register-link">
                    <p>Don't have an account? <a href="register.php">Register here</a></p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
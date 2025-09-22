<?php
session_start();

try {
    $pdo = require_once 'config/db.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = trim($_POST['username']);
        $password = $_POST['password'];
        $remember = isset($_POST['remember']) ? true : false;

        // Get user from database
        $stmt = $pdo->prepare("SELECT userid, username, password, email, role FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify password
        if ($user && password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['userid'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['logged_in'] = true;

            // Set remember me cookie if checked
            if ($remember) {
                $token = bin2hex(random_bytes(32));
                $expires = time() + (30 * 24 * 60 * 60); // 30 days

                // Store token in database
                $stmt = $pdo->prepare("UPDATE users SET remember_token = ?, token_expires = ? WHERE userid = ?");
                $stmt->execute([$token, date('Y-m-d H:i:s', $expires), $user['userid']]);

                // Set cookie
                setcookie('remember_token', $token, $expires, '/', '', true, true);
            }

            // Redirect based on role
            if ($user['role'] === 'admin') {
                header("Location: home.php");
            } else {
                header("Location: home2.php");
            }
            exit();
        } else {
            $_SESSION['error'] = "Invalid username or password";
            header("Location: index.php");
            exit();
        }
    }
} catch (PDOException $e) {
    $_SESSION['error'] = "Login failed: " . $e->getMessage();
    header("Location: index.php");
    exit();
}
?> 
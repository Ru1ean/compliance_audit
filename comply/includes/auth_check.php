<?php
session_start();

// Function to check if user is logged in
function is_logged_in() {
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

// Function to check if user is admin
function is_admin() {
    return is_logged_in() && $_SESSION['role'] === 'admin';
}

// Function to check if user has access to a page
function check_access($required_role = null) {
    if (!is_logged_in()) {
        header("Location: index.php");
        exit();
    }

    if ($required_role && $_SESSION['role'] !== $required_role) {
        header("Location: home.php");
        exit();
    }
}

// Check for remember me cookie
if (!is_logged_in() && isset($_COOKIE['remember_token'])) {
    try {
        $pdo = require_once __DIR__ . '/../config/db.php';
        
        $stmt = $pdo->prepare("SELECT userid, username, email, role FROM users WHERE remember_token = ? AND token_expires > NOW()");
        $stmt->execute([$_COOKIE['remember_token']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            // Set session variables
            $_SESSION['user_id'] = $user['userid'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['logged_in'] = true;
            
            // Refresh remember me token
            $token = bin2hex(random_bytes(32));
            $expires = time() + (30 * 24 * 60 * 60); // 30 days
            
            $stmt = $pdo->prepare("UPDATE users SET remember_token = ?, token_expires = ? WHERE userid = ?");
            $stmt->execute([$token, date('Y-m-d H:i:s', $expires), $user['userid']]);
            
            setcookie('remember_token', $token, $expires, '/', '', true, true);
        }
    } catch (PDOException $e) {
        // Silent fail - user will need to login manually
    }
}
?> 
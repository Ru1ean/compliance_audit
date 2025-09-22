<?php
session_start();

try {
    $pdo = require_once 'config/db.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        
        // Basic validation
        $errors = [];
        
        // Username validation
        if (empty($username)) {
            $errors[] = "Username is required";
        } elseif (strlen($username) < 3) {
            $errors[] = "Username must be at least 3 characters long";
        }
        
        // Email validation
        if (empty($email)) {
            $errors[] = "Email is required";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format";
        }
        
        // Password validation
        if (empty($password)) {
            $errors[] = "Password is required";
        } elseif (strlen($password) < 6) {
            $errors[] = "Password must be at least 6 characters long";
        } elseif (!preg_match("/\d/", $password)) {
            $errors[] = "Password must contain at least one number";
        } elseif (!preg_match("/[!@#$%^&*(),.?\":{}|<>]/", $password)) {
            $errors[] = "Password must contain at least one special character";
        } elseif ($password !== $confirm_password) {
            $errors[] = "Passwords do not match";
        }
        
        // Check if username already exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetchColumn() > 0) {
            $errors[] = "Username already exists";
        }
        
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetchColumn() > 0) {
            $errors[] = "Email already exists";
        }
        
        // If no errors, proceed with registration
        if (empty($errors)) {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Set default role as 'staff'
            $role = 'staff';
            
            // Check if this is the first user, make them admin
            $stmt = $pdo->query("SELECT COUNT(*) FROM users");
            if ($stmt->fetchColumn() == 0) {
                $role = 'admin';
            }
            
            // Insert new user
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->execute([$username, $email, $hashed_password, $role]);
            
            // Set success message
            $_SESSION['success_message'] = "Registration successful! Please login.";
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['error_messages'] = $errors;
            $_SESSION['form_data'] = [
                'username' => $username,
                'email' => $email
            ];
            header("Location: register.php");
            exit();
        }
    }
} catch (PDOException $e) {
    $_SESSION['error_messages'] = ["Registration failed: " . $e->getMessage()];
    header("Location: register.php");
    exit();
}
?> 
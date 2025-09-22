<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Compliance Management System</title>
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
            if (isset($_SESSION['error_messages']) && !empty($_SESSION['error_messages'])) {
                echo '<div class="error-messages"><ul>';
                foreach ($_SESSION['error_messages'] as $error) {
                    echo '<li>' . htmlspecialchars($error) . '</li>';
                }
                echo '</ul></div>';
                unset($_SESSION['error_messages']);
            }
            
            $form_data = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [];
            unset($_SESSION['form_data']);
            ?>

            <form action="register_process.php" method="post" id="registerForm">
                <div class="input-box">
                    <input type="text" id="username" name="username" 
                           placeholder="Choose a username" required 
                           value="<?php echo isset($form_data['username']) ? htmlspecialchars($form_data['username']) : ''; ?>">
                    <label for="username">Username</label>
                </div>

                <div class="input-box">
                    <input type="email" id="email" name="email" 
                           placeholder="Enter your email" required 
                           value="<?php echo isset($form_data['email']) ? htmlspecialchars($form_data['email']) : ''; ?>">
                    <label for="email">Email</label>
                </div>

                <div class="input-box">
                    <input type="password" id="password" name="password" 
                           placeholder="Create a password" required>
                    <label for="password">Password</label>
                    <div class="requirements">
                        <ul>
                            <li>At least 6 characters long</li>
                            <li>Contains at least one number</li>
                            <li>Contains at least one special character</li>
                        </ul>
                    </div>
                </div>

                <div class="input-box">
                    <input type="password" id="confirm_password" name="confirm_password" 
                           placeholder="Confirm your password" required>
                    <label for="confirm_password">Confirm Password</label>
                </div>

                <div class="submit">
                    <button type="submit">Create Account</button>
                </div>

                <div class="register-link">
                    <p>Already have an account? <a href="index.php">Sign in here</a></p>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                const errorDiv = document.createElement('div');
                errorDiv.className = 'error-messages';
                errorDiv.innerHTML = '<ul><li>Passwords do not match!</li></ul>';
                
                // Remove any existing error messages
                const existingError = document.querySelector('.error-messages');
                if (existingError) {
                    existingError.remove();
                }
                
                // Insert error message after the brand section
                document.querySelector('.brand').insertAdjacentElement('afterend', errorDiv);
                
                // Scroll to top to show the error
                window.scrollTo(0, 0);
            }
        });

        // Password strength validation
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const requirements = document.querySelector('.requirements');
            
            // Update requirements list based on password content
            const hasLength = password.length >= 6;
            const hasNumber = /\d/.test(password);
            const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(password);
            
            const requirementsList = requirements.querySelector('ul').children;
            requirementsList[0].style.color = hasLength ? '#28a745' : '#dc3545';
            requirementsList[1].style.color = hasNumber ? '#28a745' : '#dc3545';
            requirementsList[2].style.color = hasSpecial ? '#28a745' : '#dc3545';
        });
    </script>
</body>
</html> 
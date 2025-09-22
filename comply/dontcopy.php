<?php
if (isset($_REQUEST['btn'])) {
    $username = $_REQUEST['username'];
    $email = $_REQUEST['email'];
    $password = trim($_REQUEST['password']);
    $cpassword = trim($_REQUEST['cpassword']);

    if ($password !== $cpassword) {

        echo '<script>
            alert("your Password is incorrect")        
        </script>';

    } else {

        
        echo '<div style="width: 100%; height: auto; background-color: #f00; display: flex; justify-content: center;"> 

        <h1>Username: ' . $username . '</h1>
    
    </div>';

    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class="container">
        <form action="" method="post">
            <div class="content">
                <div class="input-box">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" placeholder="Input username" required>
                </div>

                <div class="input-box">
                    <label for="email">Email:</label>
                    <input type="text" id="email" name="email" placeholder="Input email" required>
                </div>

                <div class="input-box">
                    <label for="password">Password:</label>
                    <input type="pass" id="password" name="password" placeholder="Input password" required>
                </div>

                <div class="input-box">
                    <label for="cpassword">confirm Password:</label>
                    <input type="text" id="cpassword" name="cpassword" placeholder="Input cpassword" required>
                </div>
            </div>

            <div class="submit">
                <input type="submit" value="Submit" name="btn">
            </div>

            <div class="text1"><?php echo $_REQUEST['username'];?></div>
            <div class="text2"><?php echo $_REQUEST['email'];?></div>
            <div class="text3"><?php echo $_REQUEST['password'];?></div>
            <div class="text4"><?php echo $_REQUEST['cpassword'];?></div>

        </form>
    </div>
</body>
</html>
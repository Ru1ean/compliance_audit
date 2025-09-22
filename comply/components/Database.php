<?php
public class Database {
    public static function connectDB(){
        global $con;
   
        // Setting the timezone
        date_default_timezone_set('Asia/Manila');
   
        try {
            $db_host = 'localhost';
            $db_name = 'db_schoolmain';
            $db_user = 'root';
            $user_pw = '';
   
            // Creating a PDO instance
            $con = new PDO('mysql:host=' . $db_host . ';dbname=' . $db_name, $db_user, $user_pw);
   
            // Set PDO attributes for error reporting and character set
            $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $con->exec("SET CHARACTER SET utf8");
   
        } catch (PDOException $err) {
            // Output a user-friendly error message
            echo "<center><h3>You are currently denied access to the Liturgy Database. Contact the Web Administrator</h3></center>";
   
            // Log the error details to a file
            file_put_contents('PDOErrors.txt', '[' . date('Y-m-d H:i:s') . '] ' . $err->getMessage() . PHP_EOL, FILE_APPEND);
   
            // Terminate the script
            die();
        }
    }


}
?>






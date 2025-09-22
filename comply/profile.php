<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: index.php');
    exit();
}
$username = $_SESSION['username'] ?? 'Unknown';
$email = $_SESSION['email'] ?? 'Unknown';
$role = $_SESSION['role'] ?? 'Unknown';

// Database connection
function connectDB() {
    $host = 'localhost';
    $dbname = 'db_compliance_audit';
    $username = 'root';
    $password = '';
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}
$pdo = connectDB();

// Handle profile photo upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
    $fileTmp = $_FILES['profile_photo']['tmp_name'];
    $fileType = mime_content_type($fileTmp);
    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif'];
    if (isset($allowed[$fileType])) {
        $ext = $allowed[$fileType];
        $hash = md5($username . uniqid('', true));
        $filename = $username . '_' . $hash . '.' . $ext;
        $targetDir = 'img/';
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        $targetPath = $targetDir . $filename;
        if (move_uploaded_file($fileTmp, $targetPath)) {
            // Update DB
            $stmt = $pdo->prepare('UPDATE users SET profile = ? WHERE username = ?');
            $stmt->execute([$filename, $username]);
            $_SESSION['profile'] = $filename;
            header('Location: profile.php');
            exit();
        }
    }
}
// Get profile image from DB
$stmt = $pdo->prepare('SELECT profile FROM users WHERE username = ?');
$stmt->execute([$username]);
$profileImg = $stmt->fetchColumn();
$imgPath = ($profileImg && file_exists('img/' . $profileImg)) ? 'img/' . $profileImg : 'img/default.png';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile | Compliance Audit System</title>
    <link rel="stylesheet" href="css/profile.css">

</head>
<body>
    <div class="profile-container">
        <div class="profile-title">My Profile</div>
        <img src="<?php echo htmlspecialchars($imgPath); ?>" alt="Profile Photo" class="profile-photo">
        <form class="profile-upload-form" method="post" enctype="multipart/form-data">
            <input type="file" name="profile_photo" accept="image/*" required>
            <button type="submit">Upload Photo</button>
        </form>
        <div class="profile-info"><span class="profile-label">Username:</span> <?php echo htmlspecialchars($username); ?></div>
        <div class="profile-info"><span class="profile-label">Email:</span> <?php echo htmlspecialchars($email); ?></div>
        <div class="profile-info"><span class="profile-label">Role:</span> <?php echo htmlspecialchars($role); ?></div>
    </div>
</body>
</html> 
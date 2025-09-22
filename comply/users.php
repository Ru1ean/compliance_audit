<?php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

try {
    $pdo = require_once 'config/db.php';

    // Handle role update for all users
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_all_roles'])) {
        $userids = $_POST['userid'];
        $roles = $_POST['role'];
        $success = 0;
        $fail = 0;
        foreach ($userids as $i => $userid) {
            // Don't allow changing own role
            if ($userid == $_SESSION['user_id']) continue;
            $new_role = $roles[$i];
            $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE userid = ?");
            if ($stmt->execute([$new_role, $userid])) {
                $success++;
            } else {
                $fail++;
            }
        }
        if ($success > 0) {
            $_SESSION['success'] = "Updated $success user role(s) successfully.";
        }
        if ($fail > 0) {
            $_SESSION['error'] = "Failed to update $fail user role(s).";
        }
        header("Location: users.php");
        exit();
    }

    // Get all users
    $stmt = $pdo->query("SELECT userid, username, email, role FROM users ORDER BY username");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $_SESSION['error'] = "Database error: " . $e->getMessage();
    $users = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - Compliance Management System</title>
 <link rel="stylesheet" href="css/users.css">
</head>
<body>
    <form action="users.php" method="post" id="rolesForm">
        <div class="page-header">
            <div class="box123" style">
            <div class="page-title">User Management</div>
            <button type="submit" name="update_all_roles" class="update-all-btn">Update All Roles</button>
            </div>
        </div>
        <div class="container">
            <div class="card">
                <?php if (isset($_SESSION['success'])): ?>
                    <!-- Success alert removed -->
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-error">
                        <?php 
                            echo $_SESSION['error']; 
                            unset($_SESSION['error']); 
                        ?>
                    </div>
                <?php endif; ?>
                <table>
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (
                            $users as $i => $user): ?>
                        <tr<?php if ($user['userid'] == $_SESSION['user_id']) echo ' class="current-user-row"'; ?>>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <input type="hidden" name="userid[]" value="<?php echo $user['userid']; ?>">
                                <?php if ($user['userid'] != $_SESSION['user_id']): ?>
                                    <select name="role[]" class="role-select">
                                        <option value="staff" <?php echo $user['role'] === 'staff' ? 'selected' : ''; ?>>Staff</option>
                                        <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                                    </select>
                                <?php else: ?>
                                    <span class="badge badge-<?php echo $user['role']; ?>">
                                        <?php echo ucfirst($user['role']); ?> (Current User)
                                    </span>
                                    <input type="hidden" name="role[]" value="<?php echo $user['role']; ?>">
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </form>
</body>
</html>

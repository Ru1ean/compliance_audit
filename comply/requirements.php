<?php
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

// Get database connection
$pdo = connectDB();

// Handle add requirement form submission
if (isset($_POST['add_requirement'])) {
    $title = trim($_POST['req_title'] ?? '');
    $desc = trim($_POST['req_description'] ?? '');
    if ($title && $desc) {
        // Check for duplicate req_name
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM requirements WHERE req_name = ?");
        $stmt->execute([$title]);
        if ($stmt->fetchColumn() > 0) {
            // Duplicate found, set error message
            $error_message = "Requirement with this title already exists.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO requirements (req_name, req_description) VALUES (?, ?)");
            $stmt->execute([$title, $desc]);
            header('Location: requirements.php');
            exit;
        }
    }
}

// Get requirements
$stmt = $pdo->query("SELECT * FROM requirements ORDER BY req_id");
$requirements = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requirements | Compliance Audit System</title>
    <link rel="stylesheet" href="css/requirements.css">

</head>
<body>
    <div class="page-header">
        <div class="boxw">
            <h1>Requirements</h1>
            <button id="addRequirementBtn" class="add-req-btn">+ Add Requirement</button>
        </div>
    </div>
    <div class="container">
        <?php if (isset($error_message)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        <?php if (empty($requirements)): ?>
            <p>No requirements found in the database.</p>
        <?php else: ?>
            <div class="requirements">
                <?php foreach ($requirements as $req): ?>
                    <div class="requirement-item">
                        <div class="req-info">
                            <span class="req-text"><?php echo htmlspecialchars($req['req_name']); ?></span>
                            <p class="req-description"><?php echo htmlspecialchars($req['req_description']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <div id="addRequirementModal" class="modal-overlay" style="display:none;">
        <div class="modal-content">
            <h2>Add New Requirement</h2>
            <form method="post" action="requirements.php" id="addRequirementForm">
                <label for="req_title">Title:</label>
                <input type="text" id="req_title" name="req_title" required>
                <label for="req_description">Description:</label>
                <textarea id="req_description" name="req_description" rows="4" required></textarea>
                <div class="modal-actions">
                    <button type="submit" name="add_requirement" class="submit-btn">Add</button>
                    <button type="button" id="closeModalBtn" class="cancel-btn">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    <script src="js/addrequirments.js"></script>
</body>
</html> 
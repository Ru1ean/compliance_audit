<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_compliance_audit";

// Initialize variables
$logs = [];
$error = null;

// Filter parameters
$filter_action = isset($_GET['action_type']) ? $_GET['action_type'] : '';
$filter_entity = isset($_GET['entity_name']) ? $_GET['entity_name'] : '';
$filter_date = isset($_GET['date']) ? $_GET['date'] : '';

try {
    // Create database connection
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if the audit_logs table exists
    $stmt = $conn->prepare("SHOW TABLES LIKE 'audit_logs'");
    $stmt->execute();
    $tableExists = $stmt->rowCount() > 0;
    
    if ($tableExists) {
        // Get unique values for filters
        $stmt = $conn->prepare("SELECT DISTINCT action_type FROM audit_logs ORDER BY action_type");
        $stmt->execute();
        $action_types = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        $stmt = $conn->prepare("SELECT DISTINCT entity_name FROM audit_logs WHERE entity_name IS NOT NULL ORDER BY entity_name");
        $stmt->execute();
        $entity_names = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        // Build query with filters
        $query = "SELECT * FROM audit_logs WHERE 1=1";
        $params = [];
        
        if (!empty($filter_action)) {
            $query .= " AND action_type = :action_type";
            $params[':action_type'] = $filter_action;
        }
        
        if (!empty($filter_entity)) {
            $query .= " AND entity_name = :entity_name";
            $params[':entity_name'] = $filter_entity;
        }
        
        if (!empty($filter_date)) {
            $query .= " AND DATE(timestamp) = :date";
            $params[':date'] = $filter_date;
        }
        
        $query .= " ORDER BY timestamp DESC LIMIT 100";
        
        // Fetch logs from the database (most recent first)
        $stmt = $conn->prepare($query);
        $stmt->execute($params);
        $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    } else {
        // Create the audit_logs table if it doesn't exist
        $sql = "CREATE TABLE audit_logs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            action_type VARCHAR(50) NOT NULL,
            action_description TEXT NOT NULL,
            entity_name VARCHAR(50),
            entity_id INT,
            timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        $conn->exec($sql);
        $action_types = [];
        $entity_names = [];
    }
    
} catch(PDOException $e) {
    $error = "Database connection failed: " . $e->getMessage();
    $action_types = [];
    $entity_names = [];
}

// Helper function to determine icon and class based on action type
function getActionStyles($actionType) {
    $actionType = strtolower($actionType ?? '');
    $styles = [
        'icon' => 'bi-question-circle',
        'class' => 'action-view',
        'description' => 'Viewed information'
    ];
    
    if (strpos($actionType, 'add') !== false || strpos($actionType, 'create') !== false) {
        $styles['icon'] = 'bi-plus-circle-fill';
        $styles['class'] = 'action-add';
        $styles['description'] = 'Added new item';
    } elseif (strpos($actionType, 'delete') !== false || strpos($actionType, 'remove') !== false) {
        $styles['icon'] = 'bi-trash-fill';
        $styles['class'] = 'action-delete';
        $styles['description'] = 'Deleted item';
    } elseif (strpos($actionType, 'update') !== false || strpos($actionType, 'edit') !== false) {
        $styles['icon'] = 'bi-pencil-fill';
        $styles['class'] = 'action-update';
        $styles['description'] = 'Updated information';
    } elseif (strpos($actionType, 'setup') !== false) {
        $styles['icon'] = 'bi-gear-fill';
        $styles['class'] = 'action-setup';
        $styles['description'] = 'System setup';
    } elseif (strpos($actionType, 'submit') !== false) {
        $styles['icon'] = 'bi-check2-circle';
        $styles['class'] = 'action-update';
        $styles['description'] = 'Submitted information';
    } elseif (strpos($actionType, 'login') !== false) {
        $styles['icon'] = 'bi-box-arrow-in-right';
        $styles['class'] = 'action-login';
        $styles['description'] = 'User logged in';
    } elseif (strpos($actionType, 'logout') !== false) {
        $styles['icon'] = 'bi-box-arrow-right';
        $styles['class'] = 'action-logout';
        $styles['description'] = 'User logged out';
    } elseif (strpos($actionType, 'role change') !== false) {
        $styles['icon'] = 'bi-person-badge';
        $styles['class'] = 'action-role';
        $styles['description'] = 'User role changed';
    } elseif (strpos($actionType, 'compliance status') !== false) {
        $styles['icon'] = 'bi-check-circle';
        $styles['class'] = 'action-compliance';
        $styles['description'] = 'Compliance status updated';
    } elseif (strpos($actionType, 'requirement') !== false) {
        $styles['icon'] = 'bi-list-check';
        $styles['class'] = 'action-requirement';
        $styles['description'] = 'Requirement updated';
    }
    
    return $styles;
}

// Function to format dates nicely
function formatDate($timestamp) {
    $date = new DateTime($timestamp);
    $now = new DateTime();
    $diff = $now->diff($date);
    
    if ($diff->days == 0) {
        return 'Today at ' . $date->format('g:i A');
    } elseif ($diff->days == 1) {
        return 'Yesterday at ' . $date->format('g:i A');
    } else {
        return $date->format('M j, Y g:i A');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Logs | Compliance Management System</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/logs2.css">
</head>
<body>
    <div class="page-header">
        <div class="container">
                <h1> Audit Logs</h1>
                <a href="logs.php" class="btn btn-primary">
                    <i class="bi bi-arrow-clockwise"></i> Refresh
                </a>
        </div>
    </div>

    <div class="container mb-5">
        <!-- Filters Section -->
        <?php if ($error): ?>
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <?php echo $error; ?>
            </div>
        <?php else: ?>
            <div class="card shadow">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">System Activity Log</h5>
                    <span class="badge bg-secondary"><?php echo count($logs); ?> record(s)</span>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($logs)): ?>
                        <div class="empty-state">
                            <i class="bi bi-journal"></i>
                            <h4>No Activity Logs Found</h4>
                            <p class="text-muted">
                                <?php if (!empty($filter_action) || !empty($filter_entity) || !empty($filter_date)): ?>
                                    No logs match your current filters. Try changing or clearing the filters.
                                <?php else: ?>
                                    System activities will be recorded here as you use the application.
                                <?php endif; ?>
                            </p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 20%">Action</th>
                                        <th style="width: 35%">Description</th>
                                        <th style="width: 15%">Entity</th>
                                        <th style="width: 10%">ID</th>
                                        <th style="width: 20%">Timestamp</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($logs as $log): 
                                        $styles = getActionStyles($log['action_type']);
                                    ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="action-icon <?php echo $styles['class']; ?>">
                                                        <i class="bi <?php echo $styles['icon']; ?>"></i>
                                                    </div>
                                                    <div>
                                                        <div><?php echo htmlspecialchars($log['action_type']); ?></div>
                                                        <small class="text-muted"><?php echo $styles['description']; ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($log['action_description']); ?>
                                            </td>
                                            <td>
                                                <?php if (!empty($log['entity_name'])): ?>
                                                    <span class="action-tag bg-light"><?php echo htmlspecialchars($log['entity_name'] ?? 'N/A'); ?></span>
                                                <?php else: ?>
                                                    <span class="text-muted">N/A</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if (!empty($log['entity_id'])): ?>
                                                    <code><?php echo $log['entity_id']; ?></code>
                                                <?php else: ?>
                                                    <span class="text-muted">N/A</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div data-bs-toggle="tooltip" data-bs-placement="left" 
                                                     title="<?php echo date('F j, Y g:i:s A', strtotime($log['timestamp'])); ?>">
                                                    <?php echo formatDate($log['timestamp']); ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>


</html> 
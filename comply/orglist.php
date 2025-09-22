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

// Filter parameters
$filter_name = isset($_GET['org_name']) ? $_GET['org_name'] : '';
$filter_id = isset($_GET['org_id']) ? $_GET['org_id'] : '';

// Build query with filters
$query = "SELECT * FROM organizations WHERE 1=1";
$params = [];

if (!empty($filter_name)) {
    $query .= " AND org_name LIKE :org_name";
    $params[':org_name'] = "%$filter_name%";
}

if (!empty($filter_id)) {
    $query .= " AND org_id = :org_id";
    $params[':org_id'] = $filter_id;
}

// Add order by
$query .= " ORDER BY org_name ASC";

// Prepare and execute the statement
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$organizations = $stmt->fetchAll();

// Get unique org IDs for filter dropdown
$stmt = $pdo->query("SELECT DISTINCT org_id FROM organizations ORDER BY org_id ASC");
$org_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organization List</title>
    <link rel="stylesheet" href="css/orglistog.css">

</head>
<body>
    <div class="container">
    <form method="get">
        <div class="page-header">
            <div class="box123">
                <h1>Organizations</h1>
                    <div class="filter-row">
                        <div>
                            <input type="text" id="org_name" name="org_name" class="filter-control" 
                                value="<?php echo htmlspecialchars($filter_name); ?>" placeholder="Search Organization name">
                        </div>
                        <button type="submit" class="btn btn-primary" style="color: #0FA4AF; border:none;height:40px; width: 70px; margin-left:40px; border-radius:4px 4px 4px 0px ;">
                            Search
                        </button>
                    </div>
            </div>
        </div>
            
            <!-- Filter Section -->
    <div class="filter-container" style="margin-top: 120px;">

                
    </form>
    </div>
        
        <?php if (empty($organizations)): ?>
            <div class="no-orgs">
                <p>No organizations found matching your criteria. Please try different filters or add a new organization.</p>
            </div>
        <?php else: ?>
            <div class="org-grid">
                <?php foreach ($organizations as $org): ?>
                    <div class="org-card">
                        <div class="org-name">
                            <?php echo htmlspecialchars($org['org_name']); ?>
                        </div>
                        <div class="org-details">
                            <p>ID: <?php echo $org['org_id']; ?></p>
                            <?php if(isset($org['org_description'])): ?>
                                <p><?php echo htmlspecialchars($org['org_description']); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="org-actions">
                            <a href="organization2.php?org_id=<?php echo $org['org_id']; ?>" class="view-btn">
                                <i class="fas fa-eye"></i> View Details
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
    </div>


</body>
</html>

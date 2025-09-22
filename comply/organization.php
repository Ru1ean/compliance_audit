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

// Get organizations
$stmt = $pdo->query("SELECT * FROM organizations ORDER BY org_id");
$organizations = $stmt->fetchAll();

// Get requirements
$stmt = $pdo->query("SELECT * FROM requirements ORDER BY req_id");
$requirements = $stmt->fetchAll();

// Get compliance status
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compliance Audit System</title>
    <link rel="stylesheet" href="css/org.css">
</head>
<body>
    <div class="page-header">
        <div class="box123">
            <h1>Compliance Audit System</h1>
            <div class="button-container">
                <button id="add-org-btn" class="add-org-btn">+ Add Organization</button>
                <button type="submit" form="audit-form" name="submit_audit" class="submit-btn">Submit Audit</button>
            </div>
        </div>
    </div>
    <div class="org-req-grids" style="display: flex; flex-wrap: wrap; gap: 32px; justify-content: center; margin: 40px 0;">


<!-- Add Organization Modal -->
<?php
// Handle form submission (at the top of the file, before any HTML output)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_org_submit'])) {
    $orgName = trim($_POST['orgName']);
    $selectedReqs = isset($_POST['requirements']) ? $_POST['requirements'] : [];
    if ($orgName !== '') {
        // Insert organization
        $stmt = $pdo->prepare("INSERT INTO organizations (org_name) VALUES (?)");
        $stmt->execute([$orgName]);
        $orgId = $pdo->lastInsertId();
        // Insert requirements
        foreach ($selectedReqs as $reqId) {
            $stmt = $pdo->prepare("INSERT INTO compliance_status (org_id, req_id, status) VALUES (?, ?, '')");
            $stmt->execute([$orgId, $reqId]);
        }
        // Redirect to avoid resubmission
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Handle audit form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_audit'])) {
    if (isset($_POST['compliance']) && is_array($_POST['compliance'])) {
        foreach ($_POST['compliance'] as $orgId => $reqs) {
            foreach ($reqs as $reqId => $status) {
                // Update compliance_status for this org/req
                $stmt = $pdo->prepare("UPDATE compliance_status SET status = ? WHERE org_id = ? AND req_id = ?");
                $stmt->execute([$status, $orgId, $reqId]);
            }
        }
    }
    // Redirect to avoid resubmission
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Handle deleting an organization
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_org_id'])) {
    $deleteOrgId = intval($_POST['delete_org_id']);
    // Delete from compliance_status first (to avoid foreign key constraint)
    $stmt = $pdo->prepare("DELETE FROM compliance_status WHERE org_id = ?");
    $stmt->execute([$deleteOrgId]);
    // Delete from organizations
    $stmt = $pdo->prepare("DELETE FROM organizations WHERE org_id = ?");
    $stmt->execute([$deleteOrgId]);
    // Redirect to avoid resubmission
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}
?>

<!-- Modal Structure -->
<div id="addOrgModal" class="modal-overlay" style="display:none;">
    <div class="modal-content">
        <h2>Add Organization</h2>
        <form method="post" action="">
            <label for="orgName">Organization Name:</label>
            <input type="text" id="orgName" name="orgName" required>
            <label>Select Requirements:</label>
            <div style="font-size:0.95em; color:#666; margin-bottom:6px;">
                Only checked requirements will be added to this organization.
            </div>
            <div style="max-height:150px; overflow-y:auto; border:1px solid #eee; padding:8px; margin-bottom:10px;">
                <?php foreach ($requirements as $req): ?>
                    <div>
                        <input type="checkbox" name="requirements[]" value="<?= $req['req_id'] ?>" id="req<?= $req['req_id'] ?>">
                        <label for="req<?= $req['req_id'] ?>"><?= htmlspecialchars($req['req_name']) ?></label>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="modal-actions">
                <button type="submit" name="add_org_submit" class="submit-btn">Add</button>
                <button type="button" class="cancel-btn" onclick="document.getElementById('addOrgModal').style.display='none'">Cancel</button>
            </div>
        </form>
    </div>
</div>

<form id="audit-form" method="post" action="">
<div class="org-grid">
<?php
// Get all organizations
$orgs = $pdo->query("SELECT * FROM organizations ORDER BY org_id")->fetchAll();

foreach ($orgs as $org) {
    echo '<div class="org-card">';
    echo '<div style="display:flex;align-items:center;justify-content:space-between;background:#fafbfc;border-bottom:1px solid #eee;position:sticky;top:0;z-index:2;">';
    echo '<h3 style="margin:0;padding:16px 0 10px 20px;font-size:1.2em;background:none;border:none;">' . htmlspecialchars($org['org_name']) . '</h3>';
    echo '<button class="org-delete-btn" data-org-id="' . $org['org_id'] . '" data-org-name="' . htmlspecialchars($org['org_name']) . '" style="margin-right:16px;background:#964734;color:#fff;border:none;border-radius:4px;padding:5px 12px;cursor:pointer;">Delete</button>';
    echo '</div>';
    echo '<div class="scroll-table">';
    echo '<table>';
    echo '<tr>
            <th style="width:55%;">Requirement</th>
            <th style="width:20%;">Comply</th>
            <th style="width:25%;">Not Comply</th>
          </tr>';
    // Only show requirements that are in compliance_status for this org
    $stmt = $pdo->prepare("SELECT r.req_id, r.req_name, cs.status FROM compliance_status cs JOIN requirements r ON cs.req_id = r.req_id WHERE cs.org_id = ?");
    $stmt->execute([$org['org_id']]);
    $orgReqs = $stmt->fetchAll();
    foreach ($orgReqs as $row) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['req_name']) . '</td>';
        echo '<td style="text-align:center;"><input type="radio" name="compliance[' . $org['org_id'] . '][' . $row['req_id'] . ']" value="comply" ' . ($row['status'] === 'comply' ? 'checked' : '') . '></td>';
        echo '<td style="text-align:center;"><input type="radio" name="compliance[' . $org['org_id'] . '][' . $row['req_id'] . ']" value="not_comply" ' . ($row['status'] === 'not_comply' ? 'checked' : '') . '></td>';
        echo '</tr>';
    }
    echo '</table>';
    echo '</div>';
    echo '</div>';
}
?>
</div>
</form>
<script src="js/organization.js"></script>
<!-- Delete Organization Modal -->
<div id="deleteOrgModal" class="modal-overlay" style="display:none;">
    <div class="modal-content">
        <h2>Delete Organization</h2>
        <p>Are you sure you want to delete <span id="deleteOrgName" style="font-weight:bold;"></span>?</p>
        <form method="post" action="">
            <input type="hidden" name="delete_org_id" id="deleteOrgIdInput">
            <div class="modal-actions">
                <button type="submit" class="submit-btn">Yes, Delete</button>
                <button type="button" class="cancel-btn" id="cancelDeleteOrgBtn">No</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
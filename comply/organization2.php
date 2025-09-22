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
            </div>
        </div>
    </div>
    <div class="org-req-grids" style="display: flex; flex-wrap: wrap; gap: 32px; justify-content: center; margin: 40px 0;">
<style>
.org-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 30px;
    max-width: 1000px;
  
  
}
.org-card {
    background: #fff;
    border: 1px solid #ccc;
    border-radius: 8px;
    padding: 0;
    min-height: 180px;
    max-height: 320px; /* Adjust as needed */
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    position: relative;
    width: 100%;
}
.org-card h3 {
    margin: 0;
    padding: 16px 20px 10px 20px;
    font-size: 1.2em;
    background: #fafbfc;
    border-bottom: 1px solid #eee;
    position: sticky;
    top: 0;
    z-index: 2;
}
.org-card .scroll-table {
    overflow-y: auto;
    flex: 1 1 auto;
    padding: 0 10px 10px 10px;
    background: #fff;
}
.org-card table {
    width: 100%;
    border-collapse: collapse;
    font-size: 1em;
}
.org-card th, .org-card td {
    padding: 8px 10px;
    text-align: left;
}
.org-card th {
    background: #f5f5f5;
    font-weight: bold;
    text-align: center;
    border-bottom: 2px solid #ddd;
    position: sticky;
    top: 0;
    z-index: 1;
}
.org-card td {
    border-bottom: 1px solid #eee;
    vertical-align: middle;
}
.org-card tr:last-child td {
    border-bottom: none;
}
.org-card tr:hover {
    background: #fafbfc;
}
.org-card input[type="radio"] {
    transform: scale(1.2);
    margin: 0 8px 0 0;
    vertical-align: middle;
}
@media (max-width: 600px) {
    .org-card table, .org-card thead, .org-card tbody, .org-card th, .org-card td, .org-card tr {
        display: block;
    }
    .org-card th, .org-card td {
        text-align: left;
        padding: 8px 5px;
    }
    .org-card th {
        border-bottom: none;
    }
}
.page-header {
   
    top: 0;
    left: 0;
    width: 100%;
    z-index: 1000;
    background: #fff;
    border-bottom: 1px solid #eee;
    padding: 20px 0 10px 0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.03);
}
.box123 {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
  
    margin: 0 auto;
    height: auto;
    padding: 0 20px;
}
.page-header h1 {
    margin: 0;
    font-size: 2em;
    font-weight: bold;
}
.button-container {
    display: flex;
    gap: 10px;
}
.modal-actions {
    display: flex;
    justify-content: space-between;
    gap: 10px;
    align-items: center;
    margin-top: 8px;
    position: relative;
    width: 100%;
}
.modal-actions .submit-btn {
    /* Optionally, you can add flex: 0 0 auto; to prevent stretching */
}
.modal-actions .cancel-btn {
    margin-right: 0;
    width: 30%;
    margin-left: auto;

}

input[type="radio"] {
  pointer-events: none;     /* prevent interaction */
  accent-color: #ccc;       /* default gray color */
}

input[type="radio"]:checked {
  accent-color: #007bff;    /* highlighted blue for the selected one */
}
</style>

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
   htmlspecialchars($org['org_name']) . '" style="margin-right:16px;background:#964734;color:#fff;border:none;border-radius:4px;padding:5px 12px;cursor:pointer;">Delete</button>';
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
<script src="js/organization2"></script>
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
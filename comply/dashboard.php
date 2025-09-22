<?php
// Database connection configuration
$servername = "localhost";
$username = "root"; // default XAMPP username
$password = ""; // default XAMPP password
$dbname = "db_compliance_audit"; // your database name

try {
    // Create connection with PDO
    $dsn = "mysql:host=$servername;dbname=$dbname;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    $pdo = new PDO($dsn, $username, $password, $options);
    
    // Initialize statistics
    $stats = [
        'organizations' => 0,
        'users' => 0,
        'audits_count' => 0,
        'pending_audits' => 0
    ];
    
    // Get organization count
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM organizations");
    if ($stmt) {
        $stats['organizations'] = $stmt->fetch()['count'];
    }
    
    // Try to get users count (if table exists)
    try {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
        if ($stmt && $stmt->rowCount() > 0) {
            $stats['users'] = $stmt->fetch()['count'];
        }
    } catch(PDOException $e) {
        // Table might not exist - fallback to 0
    }
    
    // Try to get audits count
    try {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM audits");
        if ($stmt) {
            $stats['audits_count'] = $stmt->fetch()['count'];
        }
        
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM audits WHERE status = 'pending'");
        if ($stmt) {
            $stats['pending_audits'] = $stmt->fetch()['count'];
        }
    } catch(PDOException $e) {
        // Table might not exist
    }
    
    // Get audit log data for the area chart
    try {
        $monthlyData = [];
        $stmt = $pdo->query("SELECT 
                              DATE_FORMAT(timestamp, '%b') as month,
                              COUNT(*) as count 
                            FROM audit_logs 
                            GROUP BY MONTH(timestamp) 
                            ORDER BY MONTH(timestamp) 
                            LIMIT 12");
        
        if ($stmt && $stmt->rowCount() > 0) {
            while ($row = $stmt->fetch()) {
                $monthlyData[$row['month']] = $row['count'];
            }
        }
        
        // Create full month labels and data
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $monthCounts = [];
        
        foreach ($months as $month) {
            $monthCounts[] = isset($monthlyData[$month]) ? $monthlyData[$month] : 0;
        }
    } catch(PDOException $e) {
        // Fallback if query fails
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $monthCounts = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
    }
    
    // Get entity data for the pie chart
    try {
        $pieData = [];
        $pieLabels = [];
        
        $stmt = $pdo->query("SELECT 
                             entity_name, 
                             COUNT(*) as count 
                             FROM audit_logs 
                             WHERE entity_name IS NOT NULL 
                             GROUP BY entity_name 
                             ORDER BY count DESC 
                             LIMIT 3");
        
        if ($stmt && $stmt->rowCount() > 0) {
            while ($row = $stmt->fetch()) {
                $pieLabels[] = $row['entity_name'];
                $pieData[] = $row['count'];
            }
        } else {
            // Default data if no results
            $pieLabels = ['No Data'];
            $pieData = [100];
        }
    } catch(PDOException $e) {
        // Default data if query fails
        $pieLabels = ['No Data'];
        $pieData = [100];
    }
    
    // Get recent organizations
    try {
        $organizations = [];
        $stmt = $pdo->query("SELECT * FROM organizations ORDER BY org_id DESC LIMIT 5");
        if ($stmt) {
            $organizations = $stmt->fetchAll();
        }
    } catch(PDOException $e) {
        // No organizations or table doesn't exist
    }
    
    // Get recent audit logs (activities)
    try {
        $recentLogs = [];
        $stmt = $pdo->query("SELECT * FROM audit_logs ORDER BY timestamp DESC LIMIT 5");
        if ($stmt) {
            $recentLogs = $stmt->fetchAll();
        }
    } catch(PDOException $e) {
        // No logs or table doesn't exist
    }
    
} catch(PDOException $e) {
    $error = "Database connection failed: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Compliance Management System Dashboard">
    <meta name="author" content="">

    <title>Compliance Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="bootstrap/css2/sb-admin-2.min.css" rel="stylesheet">
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->
                    <form
                        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                                aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Compliance Management Dashboard</h1>
                        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
                    </div>

                    <?php if (isset($error)): ?>
                    <div class="alert alert-danger">
                        <strong>Error:</strong> <?php echo $error; ?>
                    </div>
                    <?php endif; ?>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Organizations Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Organizations</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $stats['organizations']; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-building fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Users Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Users</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $stats['users']; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-users fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Audits Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Audits
                                            </div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $stats['audits_count']; ?></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pending Audits Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Pending Audits</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $stats['pending_audits']; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-tasks fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Area Chart -->
                        <div class="col-xl-8 col-lg-7">
                            <div class="card shadow mb-4">
                                <!-- Card Header -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Monthly Audit Activity</h6>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-area">
                                        <canvas id="myAreaChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pie Chart -->
                        <div class="col-xl-4 col-lg-5">
                            <div class="card shadow mb-4">
                                <!-- Card Header -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Entity Distribution</h6>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-pie pt-4 pb-2">
                                        <canvas id="myPieChart"></canvas>
                                    </div>
                                    <div class="mt-4 text-center small">
                                        <?php foreach ($pieLabels as $index => $label): ?>
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-<?php echo $index == 0 ? 'primary' : ($index == 1 ? 'success' : 'info'); ?>"></i> <?php echo htmlspecialchars($label); ?>
                                        </span>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Organizations Column -->
                        <div class="col-lg-6 mb-4">
                            <!-- Organizations Card -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Recent Organizations</h6>
                                </div>
                                <div class="card-body">
                                    <?php if (empty($organizations)): ?>
                                    <p class="text-center">No organizations found.</p>
                                    <?php else: ?>
                                    <div class="table-responsive">
                                        <table class="table table-bordered" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Name</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($organizations as $org): ?>
                                                <tr>
                                                    <td><?php echo $org['org_id']; ?></td>
                                                    <td><?php echo htmlspecialchars($org['org_name']); ?></td>
                                                    <td>
                                                        <a href="../organization.php?org_id=<?php echo $org['org_id']; ?>" class="btn btn-primary btn-sm">
                                                            View
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Activity Column -->
                        <div class="col-lg-6 mb-4">
                            <!-- Recent Activity Card -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Recent Activity</h6>
                                </div>
                                <div class="card-body">
                                    <?php if (empty($recentLogs)): ?>
                                    <p class="text-center">No recent activity found.</p>
                                    <?php else: ?>
                                    <div class="list-group">
                                        <?php foreach ($recentLogs as $log): 
                                            $type = strtolower($log['action_type'] ?? '');
                                            if (strpos($type, 'add') !== false || strpos($type, 'create') !== false) {
                                                $color = 'success';
                                                $icon = 'plus-circle';
                                            } elseif (strpos($type, 'delete') !== false || strpos($type, 'remove') !== false) {
                                                $color = 'danger';
                                                $icon = 'trash';
                                            } elseif (strpos($type, 'update') !== false || strpos($type, 'edit') !== false) {
                                                $color = 'info';
                                                $icon = 'pencil-alt';
                                            } else {
                                                $color = 'primary';
                                                $icon = 'info-circle';
                                            }
                                        ?>
                                        <div class="list-group-item">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 class="mb-1">
                                                    <i class="fas fa-<?php echo $icon; ?> text-<?php echo $color; ?> mr-2"></i>
                                                    <?php echo htmlspecialchars($log['action_type']); ?>
                                                </h6>
                                                <small><?php echo date('M j, g:i a', strtotime($log['timestamp'])); ?></small>
                                            </div>
                                            <p class="mb-1"><?php echo htmlspecialchars($log['action_description']); ?></p>
                                            <?php if (!empty($log['entity_name'])): ?>
                                            <small class="text-muted">
                                                <?php echo htmlspecialchars($log['entity_name']); ?>
                                                <?php if (!empty($log['entity_id'])): ?>
                                                #<?php echo $log['entity_id']; ?>
                                                <?php endif; ?>
                                            </small>
                                            <?php endif; ?>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Compliance Management System <?php echo date('Y'); ?></span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    <script src="bootstrap/vendor/jquery/jquery.min.js"></script>
    <script src="bootstrap/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="bootstrap/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="bootstrap/js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="bootstrap/vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script>
    // Area Chart
    var ctx = document.getElementById("myAreaChart");
    var myLineChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($months); ?>,
            datasets: [{
                label: "Audit Entries",
                lineTension: 0.3,
                backgroundColor: "rgba(78, 115, 223, 0.05)",
                borderColor: "rgba(78, 115, 223, 1)",
                pointRadius: 3,
                pointBackgroundColor: "rgba(78, 115, 223, 1)",
                pointBorderColor: "rgba(78, 115, 223, 1)",
                pointHoverRadius: 3,
                pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                pointHitRadius: 10,
                pointBorderWidth: 2,
                data: <?php echo json_encode($monthCounts); ?>,
            }],
        },
        options: {
            maintainAspectRatio: false,
            layout: {
                padding: {
                    left: 10,
                    right: 25,
                    top: 25,
                    bottom: 0
                }
            },
            scales: {
                xAxes: [{
                    time: {
                        unit: 'date'
                    },
                    gridLines: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        maxTicksLimit: 7
                    }
                }],
                yAxes: [{
                    ticks: {
                        maxTicksLimit: 5,
                        padding: 10,
                        beginAtZero: true,
                        callback: function(value, index, values) {
                            return value;
                        }
                    },
                    gridLines: {
                        color: "rgb(234, 236, 244)",
                        zeroLineColor: "rgb(234, 236, 244)",
                        drawBorder: false,
                        borderDash: [2],
                        zeroLineBorderDash: [2]
                    }
                }],
            },
            legend: {
                display: false
            },
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                titleMarginBottom: 10,
                titleFontColor: '#6e707e',
                titleFontSize: 14,
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                intersect: false,
                mode: 'index',
                caretPadding: 10,
                callbacks: {
                    label: function(tooltipItem, chart) {
                        var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                        return datasetLabel + ': ' + tooltipItem.yLabel;
                    }
                }
            }
        }
    });

    // Pie Chart
    var ctx = document.getElementById("myPieChart");
    var myPieChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode($pieLabels); ?>,
            datasets: [{
                data: <?php echo json_encode($pieData); ?>,
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
                hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                caretPadding: 10,
            },
            legend: {
                display: false
            },
            cutoutPercentage: 80,
        },
    });
    </script>
</body>

</html>
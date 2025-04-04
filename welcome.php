<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Determine current page
$current_page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo ucfirst($current_page); ?> - Inspector Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #f8f9fc;
            --accent-color: #2e59d9;
            --sidebar-width: 250px;
        }
        
        body {
            padding-top: 56px;
            background-color: #f8f9fc;
        }
        
        .navbar-brand {
            font-weight: 800;
            letter-spacing: 0.5px;
        }
        
        .nav-pills .nav-link.active {
            background-color: var(--primary-color);
        }
        
        .sidebar {
            background: white;
            border-right: 1px solid #e3e6f0;
            height: calc(100vh - 56px);
            position: fixed;
            top: 56px;
            width: var(--sidebar-width);
            overflow-y: auto;
            transition: all 0.3s;
            z-index: 1000;
        }
        
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 20px;
            transition: all 0.3s;
        }
        
        .card {
            border: none;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            margin-bottom: 1.5rem;
            transition: transform 0.2s;
        }
        
        .card:hover {
            transform: translateY(-2px);
        }
        
        .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
            font-weight: 700;
            padding: 1rem 1.35rem;
        }
        
        .stat-card {
            border-left: 4px solid;
            transition: all 0.3s;
        }
        
        .stat-card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        }
        
        .border-left-primary {
            border-left-color: var(--primary-color);
        }
        
        .border-left-success {
            border-left-color: #1cc88a;
        }
        
        .border-left-info {
            border-left-color: #36b9cc;
        }
        
        .border-left-warning {
            border-left-color: #f6c23e;
        }
        
        .page-title {
            font-weight: 600;
            color: #2e3d4f;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                margin-left: -var(--sidebar-width);
            }
            
            .sidebar.active {
                margin-left: 0;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .main-content.active {
                margin-left: var(--sidebar-width);
            }
        }
        
        /* Animation for content loading */
        .fade-in {
            animation: fadeIn 0.3s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .report-table {
            font-size: 0.85rem;
        }
        
        .report-table th {
            white-space: nowrap;
            position: sticky;
            top: 0;
            background: white;
        }
        
        .chart-container {
            position: relative;
            height: 300px;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
    <!-- Top Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm fixed-top">
        <div class="container-fluid">
            <button class="navbar-toggler me-2" type="button" id="sidebarToggle">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand fw-bold" href="#">
            Inspector Portal
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTop">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarTop">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i>
                            <span class="d-none d-lg-inline"><?php echo htmlspecialchars($_SESSION["full_name"]); ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i> Profile</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cogs me-2"></i> Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar Navigation -->
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block sidebar">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <img src="https://via.placeholder.com/100" class="rounded-circle mb-2" width="80" alt="Profile">
                        <h6 class="fw-bold"><?php echo htmlspecialchars($_SESSION["full_name"]); ?></h6>
                        <small class="text-muted"><?php echo htmlspecialchars($_SESSION["inspector_id"]); ?></small>
                    </div>
                    
                    <ul class="nav flex-column">
                        <li class="nav-item">
                          <a class="nav-link <?php echo $current_page == 'dashboard' ? 'active' : ''; ?>" href="itrform.php"> 
                                <i class="fas fa-fw fa-tachometer-alt me-2"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $current_page == 'inspections' ? 'active' : ''; ?>" href="welcome.php?page=inspections">
                                <i class="fas fa-fw fa-clipboard-check me-2"></i>
                                Inspections
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $current_page == 'reports' ? 'active' : ''; ?>" href="testsqlite.php?page=reports">
                                <i class="fas fa-fw fa-chart-bar me-2"></i>
                                Reports
                            </a>
                        </li>
                    </ul>
                    
                    <hr class="my-3">
                    
                    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mb-1 text-muted text-uppercase">
                        <span>Account</span>
                    </h6>
                    <ul class="nav flex-column mb-2">
                        <li class="nav-item">
                            <a class="nav-link <?php echo $current_page == 'profile' ? 'active' : ''; ?>" href="welcome.php?page=profile">
                                <i class="fas fa-fw fa-user me-2"></i>
                                Profile
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $current_page == 'settings' ? 'active' : ''; ?>" href="welcome.php?page=settings">
                                <i class="fas fa-fw fa-cog me-2"></i>
                                Settings
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
              <!-- Main Content -->
              <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2 page-title"><?php echo ucfirst($current_page); ?></h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <?php if($current_page == 'inspections'): ?>
                        <!-- [Previous inspections button group remains the same] -->
                        <?php elseif($current_page == 'reports'): ?>
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="exportReport()">
                                <i class="fas fa-file-export me-1"></i> Export
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="printReport()">
                                <i class="fas fa-print me-1"></i> Print
                            </button>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="reportDropdown" data-bs-toggle="dropdown">
                                <i class="fas fa-plus me-1"></i> Generate Report
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#" onclick="generateReport('daily')">Daily Summary</a></li>
                                <li><a class="dropdown-item" href="#" onclick="generateReport('weekly')">Weekly Summary</a></li>
                                <li><a class="dropdown-item" href="#" onclick="generateReport('monthly')">Monthly Summary</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#" onclick="generateReport('custom')">Custom Report</a></li>
                            </ul>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if($current_page == 'dashboard'): ?>
                <!-- Dashboard Content -->
                <div class="fade-in">
                    <!-- Welcome Card -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-info-circle me-1"></i>
                            Welcome
                        </div>
                        <div class="card-body">
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i> Welcome back, <?php echo htmlspecialchars($_SESSION["full_name"]); ?>!
                            </div>
                            
                            <div class="row">
                                <div class="col-xl-3 col-md-6 mb-4">
                                    <div class="card stat-card border-left-primary h-100">
                                        <div class="card-body">
                                            <div class="row no-gutters align-items-center">
                                                <div class="col mr-2">
                                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                        Pending Inspections</div>
                                                    <div class="h5 mb-0 font-weight-bold text-gray-800">4</div>
                                                </div>
                                                <div class="col-auto">
                                                    <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-xl-3 col-md-6 mb-4">
                                    <div class="card stat-card border-left-success h-100">
                                        <div class="card-body">
                                            <div class="row no-gutters align-items-center">
                                                <div class="col mr-2">
                                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                        Completed Inspections</div>
                                                    <div class="h5 mb-0 font-weight-bold text-gray-800">12</div>
                                                </div>
                                                <div class="col-auto">
                                                    <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-xl-3 col-md-6 mb-4">
                                    <div class="card stat-card border-left-info h-100">
                                        <div class="card-body">
                                            <div class="row no-gutters align-items-center">
                                                <div class="col mr-2">
                                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                        Reports Generated</div>
                                                    <div class="h5 mb-0 font-weight-bold text-gray-800">8</div>
                                                </div>
                                                <div class="col-auto">
                                                    <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-xl-3 col-md-6 mb-4">
                                    <div class="card stat-card border-left-warning h-100">
                                        <div class="card-body">
                                            <div class="row no-gutters align-items-center">
                                                <div class="col mr-2">
                                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                        Messages</div>
                                                    <div class="h5 mb-0 font-weight-bold text-gray-800">3</div>
                                                </div>
                                                <div class="col-auto">
                                                    <i class="fas fa-comments fa-2x text-gray-300"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php elseif($current_page == 'inspections'): ?>
                <!-- Inspections Content -->
                <div class="fade-in">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-clipboard-check me-1"></i>
                                Inspection Records
                            </div>
                            <div class="d-flex">
                                <input type="text" class="form-control form-control-sm me-2" placeholder="Search inspections..." style="width: 200px;">
                                <button class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-filter"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php include('tables.html'); ?>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <!-- Default Content for other pages -->
                <div class="fade-in">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-fw fa-<?php 
                                echo $current_page == 'reports' ? 'chart-bar' : 
                                    ($current_page == 'profile' ? 'user' : 'cog'); 
                            ?> me-1"></i>
                            <?php echo ucfirst($current_page); ?>
                        </div>
                        <div class="card-body">
                            <p>This is the <?php echo $current_page; ?> page content. Add your specific content here.</p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </main>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script>
        // Enable tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        
        // Toggle sidebar on mobile
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
            document.querySelector('.main-content').classList.toggle('active');
        });
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            
            if (window.innerWidth < 768 && 
                !sidebar.contains(event.target) && 
                !sidebarToggle.contains(event.target) &&
                sidebar.classList.contains('active')) {
                sidebar.classList.remove('active');
                document.querySelector('.main-content').classList.remove('active');
            }
        });
        
        // Resize handler
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 768) {
                document.getElementById('sidebar').classList.remove('active');
                document.querySelector('.main-content').classList.remove('active');
            }
        });
    </script>
</body>
</html>
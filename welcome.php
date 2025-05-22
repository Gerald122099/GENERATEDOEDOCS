<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FuelSafe | Retail Fuel Inspection System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary: #2c3e50;
            --secondary: #3498db;
            --accent: #e74c3c;
            --light: #ecf0f1;
            --dark: #1a252f;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f7fa;
            color: var(--dark);
        }
        
        .container {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: 100%;
            background: var(--primary);
            color: white;
            position: relative;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }
        
        .sidebar-header {
            padding: 15px 20px;
            background: var(--dark);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .sidebar-header h3 {
            margin-left: 10px;
            font-weight: 600;
        }
        
        .menu-toggle {
            display: block;
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 5px;
        }
        
        .sidebar-menu {
            padding: 0;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }
        
        .sidebar-menu.active {
            max-height: 1000px;
            padding: 10px 0;
        }
        
        .sidebar-menu li {
            list-style: none;
        }
        
        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: var(--light);
            text-decoration: none;
            transition: all 0.3s;
            font-size: 15px;
        }
        
        .sidebar-menu a:hover, .sidebar-menu a.active {
            background: rgba(255, 255, 255, 0.1);
            border-left: 4px solid var(--secondary);
        }
        
        .sidebar-menu a i {
            margin-right: 10px;
            font-size: 18px;
        }
        
        /* Main Content Styles */
        .main-content {
            flex: 1;
            padding: 15px;
            background-color: #f5f7fa;
        }
        
        .header {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: flex-start;
            padding: 15px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 15px;
        }
        
        .header h2 {
            font-size: 1.2rem;
            margin-bottom: 10px;
        }
        
        .user-actions {
            display: flex;
            align-items: center;
            width: 100%;
            justify-content: flex-end;
        }
        
        .logout-btn {
            background: var(--accent);
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
            display: flex;
            align-items: center;
            transition: background 0.3s;
            font-size: 0.9rem;
        }
        
        .logout-btn:hover {
            background: #c0392b;
        }
        
        .logout-btn i {
            margin-right: 5px;
        }
        
        /* Dashboard Content */
        .dashboard-content {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            min-height: calc(100vh - 150px);
        }
        
        .welcome-section {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .welcome-section h1 {
            color: var(--primary);
            margin-bottom: 10px;
            font-size: 1.5rem;
        }
        
        .welcome-section p {
            color: #7f8c8d;
            font-size: 0.95rem;
            max-width: 100%;
            margin: 0 auto;
        }
        
        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }
        
        .stat-card {
            background: white;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
            border-top: 4px solid var(--secondary);
        }
        
        .stat-card h3 {
            color: #7f8c8d;
            font-size: 0.9rem;
            margin-bottom: 8px;
        }
        
        .stat-card p {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary);
        }
        
        .recent-activities {
            margin-top: 25px;
        }
        
        .recent-activities h2 {
            color: var(--primary);
            margin-bottom: 15px;
            font-size: 1.3rem;
        }
        
        .activity-list {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
        }
        
        .activity-item {
            display: flex;
            flex-direction: column;
            padding: 12px;
            border-bottom: 1px solid #eee;
        }
        
        .activity-item:last-child {
            border-bottom: none;
        }
        
        .activity-main {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
        }
        
        .activity-icon {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: rgba(52, 152, 219, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            color: var(--secondary);
            flex-shrink: 0;
        }
        
        .activity-details {
            flex: 1;
        }
        
        .activity-details h4 {
            font-size: 0.95rem;
            margin-bottom: 3px;
            color: var(--primary);
        }
        
        .activity-details p {
            font-size: 0.8rem;
            color: #7f8c8d;
        }
        
        .activity-time {
            font-size: 0.75rem;
            color: #95a5a6;
            align-self: flex-end;
        }
        
        /* Media Queries */
        @media (min-width: 576px) {
            .header {
                flex-direction: row;
                align-items: center;
            }
            
            .header h2 {
                margin-bottom: 0;
            }
            
            .activity-item {
                flex-direction: row;
                align-items: center;
                padding: 15px 20px;
            }
            
            .activity-main {
                margin-bottom: 0;
                flex: 1;
            }
            
            .activity-time {
                margin-left: 15px;
            }
        }
        
        @media (min-width: 768px) {
            .container {
                flex-direction: row;
            }
            
            .sidebar {
                width: 250px;
                height: 100vh;
                position: sticky;
                top: 0;
            }
            
            .menu-toggle {
                display: none;
            }
            
            .sidebar-menu {
                max-height: none;
                padding: 20px 0;
                display: block !important;
            }
            
            .main-content {
                padding: 20px;
            }
            
            .header {
                padding: 15px 20px;
            }
            
            .welcome-section h1 {
                font-size: 2rem;
            }
            
            .welcome-section p {
                font-size: 1.1rem;
            }
        }
        
        @media (min-width: 992px) {
            .welcome-section h1 {
                font-size: 2.2rem;
            }
            
            .stats-cards {
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 20px;
            }
            
            .stat-card {
                padding: 20px;
            }
            
            .stat-card p {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div style="display: flex; align-items: center;">
                    <i class="fas fa-gas-pump fa-2x" style="color: var(--secondary);"></i>
                    <h3>FuelSafe</h3>
                </div>
                <button class="menu-toggle" id="menuToggle">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            
            <ul class="sidebar-menu" id="sidebarMenu">
                <li>
                    <a href="home.html" class="active">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="import_sql_lite.php">
                        <i class="fas fa-database"></i>
                        <span>Import Data</span>
                    </a>
                </li>
                <li>
                    <a href="itr_form.php">
                        <i class="fas fa-file-alt"></i>
                        <span>ITR Form</span>
                    </a>
                </li>
                <li>
                    <a href="tables.html">
                        <i class="fas fa-table"></i>
                        <span>Inspection Tables</span>
                    </a>
                </li>
                <li>
                    <a href="logout.php">
                         <i class="fa-solid fa-right-from-bracket"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </aside>
        
        <!-- Main Content Area -->
        <main class="main-content">
            <div class="header">
                <h2>Welcome to Fuel Inspection System</h2>
        
            </div>
            
            <div class="dashboard-content">
                <div class="welcome-section">
                    <h1>Retail Fuel Outlet Compliance Dashboard</h1>
                    <p>Monitor, manage, and maintain compliance across all retail fuel outlets with our comprehensive inspection system</p>
                </div>
                
                <div class="stats-cards">
                    <div class="stat-card">
                        <h3>Total Inspections</h3>
                        <p>1,248</p>
                    </div>
                    <div class="stat-card">
                        <h3>Compliance Rate</h3>
                        <p>92%</p>
                    </div>
                    <div class="stat-card">
                        <h3>Pending Actions</h3>
                        <p>18</p>
                    </div>
                    <div class="stat-card">
                        <h3>Outlets Monitored</h3>
                        <p>347</p>
                    </div>
                </div>
                
                <div class="recent-activities">
                    <h2>Recent Activities</h2>
                    <div class="activity-list">
                        <div class="activity-item">
                            <div class="activity-main">
                                <div class="activity-icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="activity-details">
                                    <h4>Inspection Completed</h4>
                                    <p>Shell Station #45 - Full compliance</p>
                                </div>
                            </div>
                            <div class="activity-time">2 hours ago</div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-main">
                                <div class="activity-icon">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div class="activity-details">
                                    <h4>Non-compliance Detected</h4>
                                    <p>BP Outlet #12 - Storage tank issue</p>
                                </div>
                            </div>
                            <div class="activity-time">1 day ago</div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-main">
                                <div class="activity-icon">
                                    <i class="fas fa-file-import"></i>
                                </div>
                                <div class="activity-details">
                                    <h4>Data Imported</h4>
                                    <p>New inspection records from mobile app</p>
                                </div>
                            </div>
                            <div class="activity-time">2 days ago</div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-main">
                                <div class="activity-icon">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="activity-details">
                                    <h4>New Inspector Added</h4>
                                    <p>John Smith joined the inspection team</p>
                                </div>
                            </div>
                            <div class="activity-time">3 days ago</div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Toggle mobile menu
        const menuToggle = document.getElementById('menuToggle');
        const sidebarMenu = document.getElementById('sidebarMenu');
        
        // Initialize menu state based on screen size
        function initMenu() {
            if (window.innerWidth >= 768) {
                sidebarMenu.classList.add('active');
            } else {
                sidebarMenu.classList.remove('active');
            }
        }
        
        // Set initial state
        initMenu();
        
        // Toggle menu when button is clicked
        menuToggle.addEventListener('click', function() {
            sidebarMenu.classList.toggle('active');
        });
        
        // Close menu when clicking on a link (for mobile)
        const menuLinks = document.querySelectorAll('.sidebar-menu a');
        menuLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth < 768) {
                    sidebarMenu.classList.remove('active');
                }
            });
        });
        
        // Update menu state when window is resized
        window.addEventListener('resize', function() {
            initMenu();
        });
    </script>
</body>
</html>
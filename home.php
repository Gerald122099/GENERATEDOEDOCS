<?php

require "config.php";
checkLogin();
allowAccess();




// Query to count all itr_form_num entries in businessinfo table
$sql = "SELECT COUNT(itr_form_num) AS total_inspections FROM businessinfo";
$result = $conn->query($sql);

if ($result) {
    $row = $result->fetch_assoc();
    $total_inspections = $row['total_inspections'];
} else {
    $total_inspections = 0; // Default value if query fails
}

// Fetch upcoming inspections (for calendar)
$upcoming_inspections = [];
$sql_upcoming = "SELECT * FROM inspections WHERE inspection_date >= CURDATE() ORDER BY inspection_date ASC LIMIT 5";
$result_upcoming = $conn->query($sql_upcoming);
if ($result_upcoming && $result_upcoming->num_rows > 0) {
    while($row = $result_upcoming->fetch_assoc()) {
        $upcoming_inspections[] = $row;
    }
}

// Fetch announcements
$announcements = [];
$sql_announcements = "SELECT * FROM announcements ORDER BY created_at DESC LIMIT 5";
$result_announcements = $conn->query($sql_announcements);
if ($result_announcements && $result_announcements->num_rows > 0) {
    while($row = $result_announcements->fetch_assoc()) {
        $announcements[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FuelSafe | Retail Fuel Inspection System</title>
    <link rel="icon" type="image/x-icon" href="..\itr\assets\img\inspectlogo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
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
            line-height: 1.6;
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
            background: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .sidebar-header h3 {
            margin-left: 10px;
            font-size: 1.5rem;
            font-weight: 200;
            color: rgb(24, 15, 103);
            font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif;
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
        
        /* New Calendar and Announcements Section */
        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
            margin-top: 25px;
        }
        
        @media (min-width: 992px) {
            .dashboard-grid {
                grid-template-columns: 2fr 1fr;
            }
        }
        
        .calendar-section, .announcements-section {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
        }
        
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .section-header h2 {
            color: var(--primary);
            font-size: 1.3rem;
        }
        
        .add-btn {
            background: var(--secondary);
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
        }
        
        .add-btn i {
            margin-right: 5px;
        }
        
        .add-btn:hover {
            background: #2980b9;
        }
        
        #calendar {
            margin-bottom: 15px;
        }
        
        .fc .fc-button {
            background-color: var(--secondary);
            border: none;
        }
        
        .fc .fc-button:hover {
            background-color: #2980b9;
        }
        
        .fc .fc-button-primary:not(:disabled).fc-button-active {
            background-color: var(--primary);
        }
        
        .upcoming-inspections {
            margin-top: 20px;
        }
        
        .inspection-item {
            padding: 10px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .inspection-item:last-child {
            border-bottom: none;
        }
        
        .inspection-details h4 {
            font-size: 0.9rem;
            margin-bottom: 3px;
            color: var(--primary);
        }
        
        .inspection-details p {
            font-size: 0.8rem;
            color: #7f8c8d;
        }
        
        .inspection-date {
            font-size: 0.8rem;
            color: var(--accent);
            font-weight: 600;
        }
        
        .announcement-item {
            padding: 12px 0;
            border-bottom: 1px solid #eee;
        }
        
        .announcement-item:last-child {
            border-bottom: none;
        }
        
        .announcement-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        
        .announcement-title {
            font-weight: 600;
            color: var(--primary);
        }
        
        .announcement-date {
            font-size: 0.75rem;
            color: #95a5a6;
        }
        
        .announcement-content {
            font-size: 0.85rem;
            color: #7f8c8d;
        }
        
        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        
        .modal-content {
            background-color: white;
            margin: 10% auto;
            padding: 20px;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .modal-header h3 {
            color: var(--primary);
        }
        
        .close-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #7f8c8d;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-size: 0.9rem;
            color: #7f8c8d;
        }
        
        .form-group input, 
        .form-group textarea, 
        .form-group select {
            width: 100%;
            padding: 8px 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 0.9rem;
        }
        
        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }
        
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }
        
        .submit-btn {
            background: var(--secondary);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .submit-btn:hover {
            background: #2980b9;
        }
        
        .cancel-btn {
            background: #95a5a6;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .cancel-btn:hover {
            background: #7f8c8d;
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
        
        /* Filter container styles */
        .filter-container {
            margin-bottom: 20px;
            background: white;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        
        .filter-container form {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: flex-end;
        }
        
        .filter-container .form-group {
            flex: 1;
            min-width: 200px;
        }
        
        .filter-container label {
            display: block;
            margin-bottom: 5px;
            font-size: 0.9rem;
            color: #7f8c8d;
        }
        
        .filter-container input[type="date"] {
            width: 100%;
            padding: 8px 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 0.9rem;
        }
        
        .filter-btn {
            padding: 8px 15px;
            background: var(--secondary);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.3s;
            font-size: 0.9rem;
        }
        
        .filter-btn:hover {
            background: #2980b9;
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
                    <img src="..\itr\assets\img\inspectlogo.png" alt="Logo" class="mb-3" width="65px">
                    <h3>DataSpect</h3>
                </div>
                <button class="menu-toggle" id="menuToggle">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            
            <ul class="sidebar-menu" id="sidebarMenu">
                <li>
                    <a href="home.php" >
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
                     <a href="itr_form.php" >
                        <i class="fas fa-file-alt"></i>
                        <span>New Entry</span>
                    </a>
                </li>
                <li>
                    <a href="tables.php">
                        <i class="fas fa-table"></i>
                        <span>Inspection Tables</span>
                    </a>
                </li>
                   <li>
                    <a href="admin_panel.php">
                        <i class="fas fa-table"></i>
                        <span>Admin Panel</span>
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
                        <p><?php echo $total_inspections; ?></p>
                    </div>
                    
                    <!-- Filtered inspections card - initially hidden -->
                    <div class="stat-card" id="total-inspections-filtered-card" style="display: none;">
                        <h3>Filtered Inspections</h3>
                        <p id="total-inspections-filtered">0</p>
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
                
                <!-- New Calendar and Announcements Section -->
                <div class="dashboard-grid">
                    <div class="calendar-section">
                        <div class="section-header">
                            <h2>Inspection Calendar</h2>
                            <button class="add-btn" id="addScheduleBtn">
                                <i class="fas fa-plus"></i> Add Schedule
                            </button>
                        </div>
                        <div id="calendar"></div>
                        
                        <div class="upcoming-inspections">
                            <h3>Upcoming Inspections</h3>
                            <?php if (!empty($upcoming_inspections)): ?>
                                <?php foreach ($upcoming_inspections as $inspection): ?>
                                    <div class="inspection-item">
                                        <div class="inspection-details">
                                            <h4><?php echo htmlspecialchars($inspection['location']); ?></h4>
                                            <p>Inspector: <?php echo htmlspecialchars($inspection['inspector']); ?></p>
                                        </div>
                                        <div class="inspection-date">
                                            <?php echo date('M j, Y', strtotime($inspection['inspection_date'])); ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>No upcoming inspections scheduled.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="announcements-section">
                        <div class="section-header">
                            <h2>Announcements</h2>
                            <button class="add-btn" id="addAnnouncementBtn">
                                <i class="fas fa-plus"></i> Add
                            </button>
                        </div>
                        
                        <?php if (!empty($announcements)): ?>
                            <?php foreach ($announcements as $announcement): ?>
                                <div class="announcement-item">
                                    <div class="announcement-header">
                                        <div class="announcement-title"><?php echo htmlspecialchars($announcement['title']); ?></div>
                                        <div class="announcement-date"><?php echo date('M j, Y', strtotime($announcement['created_at'])); ?></div>
                                    </div>
                                    <div class="announcement-content">
                                        <?php echo htmlspecialchars($announcement['content']); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No announcements available.</p>
                        <?php endif; ?>
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

    <!-- Add Schedule Modal -->
    <div id="scheduleModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add Inspection Schedule</h3>
                <button class="close-btn" id="closeScheduleModal">&times;</button>
            </div>
            <form id="scheduleForm">
                <div class="form-group">
                    <label for="inspectionLocation">Location</label>
                    <input type="text" id="inspectionLocation" required>
                </div>
                <div class="form-group">
                    <label for="inspectorName">Inspector</label>
                    <input type="text" id="inspectorName" required>
                </div>
                <div class="form-group">
                    <label for="inspectionDate">Date</label>
                    <input type="date" id="inspectionDate" required>
                </div>
                <div class="form-group">
                    <label for="inspectionNotes">Notes</label>
                    <textarea id="inspectionNotes"></textarea>
                </div>
                <div class="form-actions">
                    <button type="button" class="cancel-btn" id="cancelSchedule">Cancel</button>
                    <button type="submit" class="submit-btn">Save Schedule</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Announcement Modal -->
    <div id="announcementModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add Announcement</h3>
                <button class="close-btn" id="closeAnnouncementModal">&times;</button>
            </div>
            <form id="announcementForm">
                <div class="form-group">
                    <label for="announcementTitle">Title</label>
                    <input type="text" id="announcementTitle" required>
                </div>
                <div class="form-group">
                    <label for="announcementContent">Content</label>
                    <textarea id="announcementContent" required></textarea>
                </div>
                <div class="form-actions">
                    <button type="button" class="cancel-btn" id="cancelAnnouncement">Cancel</button>
                    <button type="submit" class="submit-btn">Post Announcement</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
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
        
        // Date filter functionality
        document.getElementById('filter-btn').addEventListener('click', function() {
            const start_date = document.getElementById('start_date').value;
            const end_date = document.getElementById('end_date').value;
            
            // Check if both start and end dates are selected
            if (!start_date || !end_date) {
                alert('Please select both start and end dates.');
                return;
            }
            
            // Send a POST request to fetch the count for the selected date range
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'get_filtered_inspections.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        document.getElementById('total-inspections-filtered').textContent = response.total_filtered;
                        document.getElementById('total-inspections-filtered-card').style.display = 'block';
                    } catch (e) {
                        console.error('Error parsing response:', e);
                        alert('Error processing the response');
                    }
                } else {
                    alert('Error fetching data');
                }
            };
            xhr.onerror = function() {
                alert('Request failed');
            };
            xhr.send('start_date=' + start_date + '&end_date=' + end_date);
        });
        
        // Initialize Calendar
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: [
                    <?php foreach ($upcoming_inspections as $inspection): ?>
                    {
                        title: '<?php echo addslashes($inspection['location']); ?>',
                        start: '<?php echo $inspection['inspection_date']; ?>',
                        extendedProps: {
                            inspector: '<?php echo addslashes($inspection['inspector']); ?>',
                            notes: '<?php echo addslashes($inspection['notes'] ?? ''); ?>'
                        }
                    },
                    <?php endforeach; ?>
                ],
                eventClick: function(info) {
                    alert(
                        'Inspection at: ' + info.event.title + '\n' +
                        'Inspector: ' + info.event.extendedProps.inspector + '\n' +
                        'Notes: ' + (info.event.extendedProps.notes || 'N/A')
                    );
                }
            });
            calendar.render();
            
            // Check for upcoming inspections and show reminders
            checkUpcomingInspections();
        });
        
        // Modal handling for schedule
        const scheduleModal = document.getElementById('scheduleModal');
        const addScheduleBtn = document.getElementById('addScheduleBtn');
        const closeScheduleModal = document.getElementById('closeScheduleModal');
        const cancelSchedule = document.getElementById('cancelSchedule');
        
        addScheduleBtn.onclick = function() {
            scheduleModal.style.display = 'block';
        }
        
        closeScheduleModal.onclick = function() {
            scheduleModal.style.display = 'none';
        }
        
        cancelSchedule.onclick = function() {
            scheduleModal.style.display = 'none';
        }
        
        // Modal handling for announcement
        const announcementModal = document.getElementById('announcementModal');
        const addAnnouncementBtn = document.getElementById('addAnnouncementBtn');
        const closeAnnouncementModal = document.getElementById('closeAnnouncementModal');
        const cancelAnnouncement = document.getElementById('cancelAnnouncement');
        
        addAnnouncementBtn.onclick = function() {
            announcementModal.style.display = 'block';
        }
        
        closeAnnouncementModal.onclick = function() {
            announcementModal.style.display = 'none';
        }
        
        cancelAnnouncement.onclick = function() {
            announcementModal.style.display = 'none';
        }
        
        // Close modals when clicking outside
        window.onclick = function(event) {
            if (event.target == scheduleModal) {
                scheduleModal.style.display = 'none';
            }
            if (event.target == announcementModal) {
                announcementModal.style.display = 'none';
            }
        }
        
       // Form submission for schedule
document.getElementById('scheduleForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const location = document.getElementById('inspectionLocation').value;
    const inspector = document.getElementById('inspectorName').value;
    const date = document.getElementById('inspectionDate').value;
    const notes = document.getElementById('inspectionNotes').value;
    
    fetch('add_schedule.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `location=${encodeURIComponent(location)}&inspector=${encodeURIComponent(inspector)}&date=${encodeURIComponent(date)}&notes=${encodeURIComponent(notes)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Add to calendar
            const calendar = FullCalendar.getApi(document.getElementById('calendar'));
            calendar.addEvent({
                id: data.id,
                title: location,
                start: date,
                extendedProps: {
                    inspector: inspector,
                    notes: notes
                }
            });
            
            // Add to upcoming inspections list
            const upcomingList = document.querySelector('.upcoming-inspections');
            const inspectionItem = document.createElement('div');
            inspectionItem.className = 'inspection-item';
            inspectionItem.innerHTML = `
                <div class="inspection-details">
                    <h4>${location}</h4>
                    <p>Inspector: ${inspector}</p>
                </div>
                <div class="inspection-date">
                    ${new Date(date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}
                </div>
            `;
            
            if (upcomingList.children.length > 1) {
                upcomingList.insertBefore(inspectionItem, upcomingList.querySelector('h3').nextSibling);
            } else {
                upcomingList.appendChild(inspectionItem);
            }
            
            this.reset();
            scheduleModal.style.display = 'none';
            alert('Inspection schedule added successfully!');
            checkUpcomingInspections();
        } else {
            alert('Error: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while adding the schedule');
    });
});

// Form submission for announcement
document.getElementById('announcementForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const title = document.getElementById('announcementTitle').value;
    const content = document.getElementById('announcementContent').value;
    
    fetch('add_announcement.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `title=${encodeURIComponent(title)}&content=${encodeURIComponent(content)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const announcementsSection = document.querySelector('.announcements-section');
            const announcementItem = document.createElement('div');
            announcementItem.className = 'announcement-item';
            announcementItem.innerHTML = `
                <div class="announcement-header">
                    <div class="announcement-title">${title}</div>
                    <div class="announcement-date">${new Date().toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</div>
                </div>
                <div class="announcement-content">
                    ${content}
                </div>
            `;
            
            const sectionHeader = announcementsSection.querySelector('.section-header');
            if (announcementsSection.children.length > 1) {
                announcementsSection.insertBefore(announcementItem, sectionHeader.nextSibling);
            } else {
                const noAnnouncements = announcementsSection.querySelector('p');
                if (noAnnouncements) announcementsSection.removeChild(noAnnouncements);
                announcementsSection.appendChild(announcementItem);
            }
            
            this.reset();
            announcementModal.style.display = 'none';
            alert('Announcement posted successfully!');
        } else {
            alert('Error: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while posting the announcement');
    });
});
        
        // Function to check for upcoming inspections and show reminders
        function checkUpcomingInspections() {
            const calendar = FullCalendar.getApi(document.getElementById('calendar'));
            const events = calendar.getEvents();
            const now = new Date();
            const oneDay = 24 * 60 * 60 * 1000; // milliseconds in one day
            
            events.forEach(event => {
                const eventDate = new Date(event.start);
                const diffDays = Math.round((eventDate - now) / oneDay);
                
                if (diffDays === 1) {
                    // Inspection is tomorrow
                    alert(`Reminder: Inspection at ${event.title} is scheduled for tomorrow!\nInspector: ${event.extendedProps.inspector}`);
                } else if (diffDays === 0) {
                    // Inspection is today
                    alert(`Reminder: Inspection at ${event.title} is scheduled for today!\nInspector: ${event.extendedProps.inspector}`);
                }
            });
        }
        
        // Check for reminders every hour
        setInterval(checkUpcomingInspections, 60 * 60 * 1000);





        
    </script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get elements
    const scheduleModal = document.getElementById('scheduleModal');
    const announcementModal = document.getElementById('announcementModal');
    const addScheduleBtn = document.getElementById('addScheduleBtn');
    const addAnnouncementBtn = document.getElementById('addAnnouncementBtn');
    const closeBtns = document.getElementsByClassName('close');

    // Event listeners
    addScheduleBtn.onclick = function() {
        scheduleModal.style.display = 'block';
    }

    addAnnouncementBtn.onclick = function() {
        announcementModal.style.display = 'block';
    }

    // Close modals when clicking X
    for (let i = 0; i < closeBtns.length; i++) {
        closeBtns[i].onclick = function() {
            scheduleModal.style.display = 'none';
            announcementModal.style.display = 'none';
        }
    }

    // Close when clicking outside
    window.onclick = function(event) {
        if (event.target == scheduleModal) {
            scheduleModal.style.display = 'none';
        }
        if (event.target == announcementModal) {
            announcementModal.style.display = 'none';
        }
    }
});
</script>
</body>
</html>
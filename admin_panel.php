<?php
require "config.php";
checkLogin();
allowAccess();

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    logout();
}



// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

 // Prevent admins from modifying their own status/role
if(isset($_POST['update'])) {
    if($_POST['id'] == $_SESSION['id'] && ($_POST['role'] != 'admin' || $_POST['status'] == 'disabled')) {
        die("Cannot modify your own admin status/role");
    }
}






// Delete user if delete button is clicked
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM user WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        header("Location: ".$_SERVER['PHP_SELF']."?status=deleted");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

// Toggle user status if enable/disable button is clicked
if (isset($_GET['toggle_status'])) {
    $id = $_GET['toggle_status'];
    
    // First get current status
    $status_query = "SELECT status FROM user WHERE id = ?";
    $stmt = $conn->prepare($status_query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    // Set new status to opposite of current status
    $new_status = ($row['status'] == 'active') ? 'disabled' : 'active';
    
    // Update the status
    $sql = "UPDATE user SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $new_status, $id);
    
    if ($stmt->execute()) {
        $action = ($new_status == 'active') ? 'enabled' : 'disabled';
        header("Location: ".$_SERVER['PHP_SELF']."?status=$action");
        exit();
    } else {
        echo "Error updating status: " . $conn->error;
    }
}

// Update user if update form is submitted
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $user_id = $_POST['user_id'];
    $password = $_POST['password'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $contact_number = $_POST['contact_number'];
    $role = $_POST['role'];
    $status = $_POST['status'];
    
    // Check if password was changed
    if (!empty($password)) {
        // Hash the password if it was changed
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE user SET user_id=?, password=?, full_name=?, email=?, contact_number=?, role=?, status=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssi", $user_id, $hashed_password, $full_name, $email, $contact_number, $role, $status, $id);
    } else {
        // Don't update password if field was left blank
        $sql = "UPDATE user SET user_id=?, full_name=?, email=?, contact_number=?, role=?, status=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssi", $user_id, $full_name, $email, $contact_number, $role, $status, $id);
    }
    
    if ($stmt->execute()) {
        header("Location: ".$_SERVER['PHP_SELF']."?status=updated");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

// Insert new user if add form is submitted
if (isset($_POST['add'])) {
    $user_id = $_POST['user_id'];
    $password = $_POST['password'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $contact_number = $_POST['contact_number'];
    $role = $_POST['role'];
    $status = $_POST['status'];
    
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO user (user_id, password, full_name, email, contact_number, role, status) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $user_id, $hashed_password, $full_name, $email, $contact_number, $role, $status);
    
    if ($stmt->execute()) {
        header("Location: ".$_SERVER['PHP_SELF']."?status=added");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fetch user data
$sql = "SELECT * FROM user";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COMDIMS | Admin Panel</title>
    <link rel="icon" type="image/x-icon" href="..\itr\assets\img\inspectlogo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="globalcss.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Russo+One&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #2c3e50;
            --secondary: #3498db;
            --accent: #e74c3c;
            --light: #ecf0f1;
            --dark: #1a252f;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        .admin-container {
            display: flex;
            min-height: 100vh;
        }

        .main-content {
            flex: 1;
            padding: 20px;
            background-color: #f8f9fa;
        }

        .admin-header {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .admin-header h1 {
            font-size: 1.8rem;
            color: var(--primary);
            margin: 0;
        }

        .action-buttons .btn {
            margin-right: 5px;
        }

        .table-responsive {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 15px;
        }

        .table thead {
            background-color: var(--primary);
            color: white;
        }

        .table th {
            font-weight: 500;
        }

        .badge {
            font-weight: 500;
            padding: 5px 10px;
        }

        .status-message {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            animation: fadeInOut 3s ease-in-out;
        }

        @keyframes fadeInOut {
            0% { opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { opacity: 0; }
        }

        /* Modal styling */
        .modal-content {
            border-radius: 10px;
            border: none;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            background-color: var(--primary);
            color: white;
            border-radius: 10px 10px 0 0 !important;
        }

        .modal-title {
            font-weight: 500;
        }

        .btn-close {
            filter: invert(1);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .admin-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .action-buttons {
                margin-top: 15px;
                width: 100%;
            }
            
            .action-buttons .btn {
                margin-bottom: 5px;
                width: 100%;
            }
            
            .table-responsive {
                overflow-x: auto;
                padding: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div style="display: flex; align-items: center;">
                    <img src="..\itr\assets\img\inspectlogo.png" alt="Logo" class="mb-3" width="65px">
                     <h3 style="font-family: 'Russo One', sans-serif;">CoMDiMS</h3>
                </div>
                <button class="menu-toggle" id="menuToggle">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            
            <ul class="sidebar-menu" id="sidebarMenu">
                <li>
                    <a href="home.php">
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
                        <span>New Entry</span>
                    </a>
                </li>
                <li>
                    <a href="tables.php">
                        <i class="fas fa-table"></i>
                        <span>Inspection Tables</span>
                    </a>
                </li>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
                    echo '
                    <li>
                        <a href="admin_panel.php" class="active">
                            <i class="fa-solid fa-user-tie"></i>
                            <span>Admin Panel</span>
                        </a>
                    </li>
                    ';
                } ?>
                <li>
                    <a href="?action=logout">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </aside>
        
        <!-- Main Content Area -->
        <main class="main-content">
            <div class="admin-header">
                <h1><i class="fa-solid fa-user-tie me-2"></i>User Management</h1>
                <div class="action-buttons">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                        <i class="fas fa-plus me-1"></i> Add New User
                    </button>
                    <a href="home.php" class="btn btn-outline-secondary">
                        <i class="fas fa-home me-1"></i> Dashboard
                    </a>
                </div>
            </div>
            
            <?php
            // Display status messages
            if (isset($_GET['status'])) {
                $status = $_GET['status'];
                $message = "";
                $alert_class = "alert-success";
                
                switch ($status) {
                    case "added":
                        $message = "User added successfully!";
                        break;
                    case "updated":
                        $message = "User updated successfully!";
                        break;
                    case "deleted":
                        $message = "User deleted successfully!";
                        break;
                    case "enabled":
                        $message = "User account enabled successfully!";
                        break;
                    case "disabled":
                        $message = "User account disabled successfully!";
                        break;
                    default:
                        $message = "";
                }
                
                if (!empty($message)) {
                    echo '<div class="alert ' . $alert_class . ' status-message alert-dismissible fade show" role="alert">
                            ' . $message . '
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                          </div>';
                }
            }
            ?>
            
            <!-- User Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User ID</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Updated</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>".$row["id"]."</td>";
                                echo "<td>".$row["user_id"]."</td>";
                                echo "<td>".$row["full_name"]."</td>";
                                echo "<td>".$row["email"]."</td>";
                                echo "<td>".$row["contact_number"]."</td>";
                                echo "<td><span class='badge bg-info'>".ucfirst($row["role"])."</span></td>";
                                
                                // Status column with appropriate styling
                                $status_badge = ($row["status"] == 'active') ? 
                                               '<span class="badge bg-success">Active</span>' : 
                                               '<span class="badge bg-danger">Disabled</span>';
                                echo "<td>".$status_badge."</td>";
                                
                                echo "<td>".date('M d, Y', strtotime($row["created_at"]))."</td>";
                                echo "<td>".(!empty($row["updated_at"]) ? date('M d, Y', strtotime($row["updated_at"])) : '-')."</td>";
                                echo "<td class='action-buttons'>
                                        <button class='btn btn-sm btn-outline-primary edit-btn' 
                                                data-id='".$row["id"]."'
                                                data-user_id='".$row["user_id"]."'
                                                data-full_name='".$row["full_name"]."'
                                                data-email='".$row["email"]."'
                                                data-contact_number='".$row["contact_number"]."'
                                                data-role='".$row["role"]."'
                                                data-status='".$row["status"]."'
                                                data-bs-toggle='modal' 
                                                data-bs-target='#editUserModal'>
                                            <i class='fas fa-edit'></i>
                                        </button>";
                                
                                // Toggle status button
                                if ($row["status"] == 'active') {
                                    echo "<a href='?toggle_status=".$row["id"]."' class='btn btn-sm btn-outline-warning' 
                                              onclick='return confirm(\"Are you sure you want to disable this account?\")'>
                                              <i class='fas fa-ban'></i>
                                          </a>";
                                } else {
                                    echo "<a href='?toggle_status=".$row["id"]."' class='btn btn-sm btn-outline-success' 
                                              onclick='return confirm(\"Are you sure you want to enable this account?\")'>
                                              <i class='fas fa-check'></i>
                                          </a>";
                                }
                                
                                echo "<a href='?delete=".$row["id"]."' class='btn btn-sm btn-outline-danger' 
                                        onclick='return confirm(\"Are you sure you want to delete this user?\")'>
                                        <i class='fas fa-trash'></i>
                                      </a>
                                    </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='10' class='text-center py-4'>No users found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Add User Modal -->
            <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><i class="fas fa-user-plus me-2"></i>Add New User</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="post">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="user_id" class="form-label">User ID</label>
                                        <input type="text" class="form-control" id="user_id" name="user_id" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="full_name" class="form-label">Full Name</label>
                                        <input type="text" class="form-control" id="full_name" name="full_name" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="contact_number" class="form-label">Contact Number</label>
                                        <input type="text" class="form-control" id="contact_number" name="contact_number" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="role" class="form-label">Role</label>
                                        <select class="form-select" id="role" name="role">
                                            <option value="admin">Admin</option>
                                            <option value="inspector" selected>Inspector</option>
                                            <option value="legal">Legal</option>
                                            <option value="head">Head</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="active" selected>Active</option>
                                        <option value="disabled">Disabled</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" name="add" class="btn btn-primary">Add User</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Edit User Modal -->
            <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><i class="fas fa-user-edit me-2"></i>Edit User</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="post">
                            <div class="modal-body">
                                <input type="hidden" id="edit_id" name="id">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="edit_user_id" class="form-label">User ID</label>
                                        <input type="text" class="form-control" id="edit_user_id" name="user_id" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="edit_password" class="form-label">Password (leave blank to keep current)</label>
                                        <input type="password" class="form-control" id="edit_password" name="password">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="edit_full_name" class="form-label">Full Name</label>
                                        <input type="text" class="form-control" id="edit_full_name" name="full_name" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="edit_email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="edit_email" name="email" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="edit_contact_number" class="form-label">Contact Number</label>
                                        <input type="text" class="form-control" id="edit_contact_number" name="contact_number" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="edit_role" class="form-label">Role</label>
                                        <select class="form-select" id="edit_role" name="role">
                                            <option value="admin">Admin</option>
                                            <option value="inspector">Inspector</option>
                                            <option value="legal">Legal</option>
                                            <option value="head">Head</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_status" class="form-label">Status</label>
                                    <select class="form-select" id="edit_status" name="status">
                                        <option value="active">Active</option>
                                        <option value="disabled">Disabled</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" name="update" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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

        // Fill edit modal with user data
        document.addEventListener('DOMContentLoaded', function() {
            const editButtons = document.querySelectorAll('.edit-btn');
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const user_id = this.getAttribute('data-user_id');
                    const full_name = this.getAttribute('data-full_name');
                    const email = this.getAttribute('data-email');
                    const contact_number = this.getAttribute('data-contact_number');
                    const role = this.getAttribute('data-role');
                    const status = this.getAttribute('data-status');
                    
                    document.getElementById('edit_id').value = id;
                    document.getElementById('edit_user_id').value = user_id;
                    document.getElementById('edit_full_name').value = full_name;
                    document.getElementById('edit_email').value = email;
                    document.getElementById('edit_contact_number').value = contact_number;
                    document.getElementById('edit_role').value = role;
                    document.getElementById('edit_status').value = status;
                });
            });
            
            // Auto-dismiss alerts after 3 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.classList.remove('show');
                    alert.classList.add('fade');
                }, 3000);
            });
        });
    </script>
</body>
</html>